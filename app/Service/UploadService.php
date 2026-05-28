<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Service;

use App\Factory\Log;
use App\Model\Attachment;
use App\Repository\AttachmentRepository;
use finfo;
use Mine\Upload\UploadInterface;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Finder\SplFileInfo;
use FriendsOfHyperf\Http\Client\Http;
use Hyperf\Cache\Cache;
use Hyperf\Collection\Arr;
use Intervention\Image\ImageManager;
use Psr\SimpleCache\CacheInterface;

use function Hyperf\Config\config;

/**
 * @extends IService<Attachment>
 */
final class UploadService extends IService
{
    public function __construct(
        protected readonly AttachmentRepository $repository,
        protected readonly UploadInterface $upload,
        protected readonly  CacheInterface $cache
    ) {}

    public function upload(SplFileInfo $fileInfo, UploadedFileInterface $uploadedFile, int $userId): Attachment
    {
        $fileHash = md5_file($fileInfo->getRealPath());
        if ($attachment = $this->repository->findByHash($fileHash)) {
            return $attachment;
        }
        $upload = $this->upload->upload(
            $fileInfo,
        );
        return $this->repository->create([
            'created_by' => $userId,
            'origin_name' => $uploadedFile->getClientFilename(),
            'storage_mode' => $upload->getStorageMode(),
            'object_name' => $upload->getObjectName(),
            'mime_type' => $upload->getMimeType(),
            'storage_path' => $upload->getStoragePath(),
            'hash' => $fileHash,
            'suffix' => $upload->getSuffix(),
            'size_byte' => $upload->getSizeByte(),
            'size_info' => $upload->getSizeInfo(),
            'url' => $upload->getUrl(),
        ]);
    }


    /**
     * ai滤镜
     */
    public function aiDesc($data = [])
    {


        $image_url = data_get($data, 'url') ?:  'https://cdn.jialenet.com/catalog/image/E1DRPuVXvJ1760075876.jpg';
        //$tempImage = Http::timeout(200)->retry(1, 100)->get($image_url)->body();
        $response = Http::timeout(200)->retry(1, 200)->withHeaders([
            'Authorization' => "Bearer " . config('chatgpt.token'),
        ])
            // ->attach('image', $tempImage, 'photo.jpg')
            // ->asMultipart()
            ->post('https://api.openai.com/v1/responses', [
                "model" => "gpt-4.1-mini",
                "input" => [
                    [
                        "role" => "user",
                        "content" => [
                            [
                                "type" => "input_text",
                                "text" => data_get($data, 'prompt') ?: 'Please describe the style of this image in detail:tones,light and shadow,brushstrokes,textures,grain',
                            ],
                            [
                                "type" => "input_image",
                                "image_url" => $image_url,
                            ],
                        ],
                    ],
                ],
            ])->json();



        if (data_get($response, 'error.message')) {
            return ['code' => 1, 'message' => data_get($response, 'error.message')];
        }
        $image = ['desc' => data_get($response, 'output.0.content.0.text')];
        return ['code' => 0, 'image' => $image];
    }


    /**
     * ai滤镜
     */
    public function faceCheck($data = [], $upload_file = null)
    {

        $face_limit = data_get($data, 'face_limit');

        $image_origin = data_get($data, 'image_origin_url');
        if (empty($image_origin) && !$upload_file) {
            return ['code' => 1, 'message' => 'image null'];
        }
        //crop	是否裁剪至最小非透明区域，url参数，加在url后面，false不裁剪，true裁剪，不填写不裁剪	否
        //bgcolor	填充背景色，url参数，加在url后面，十六进制大小RGB颜色，如FFFFFF，不填写不填充背景色	否
        //outputFormat	输出图片格式，url参数，加在url后面，可以为png,webp,jpg_$quality($quality是压缩图片的值，为0～100之间，比如jpg_75)，默认值为png	否
        //# 抠图类型， 1：人像，2：物体，3：头像，4：一键美化，6：通用抠图, 11：卡通化。

        if ($image_origin) {
            $response = Http::timeout(200)->retry(2, 100)->withHeaders([
                'APIKEY' => config('chatgpt.tukeli_token'),
            ])->get('https://picupapi.tukeli.net/api/v1/mattingByUrl', [

                'url' => $image_origin,
                'mattingType' => Attachment::TYPE_GPT_IMAGE_3,
                'crop' => true,
                'faceAnalysis' => true
            ])->json();
        } else {
            $response = Http::timeout(200)->retry(2, 100)->withHeaders([
                'APIKEY' => config('chatgpt.tukeli_token'),
            ])->attach('mattingType',  Attachment::TYPE_GPT_IMAGE_3)->attach('crop', true)->attach('faceAnalysis', true)
                ->attach('file', $upload_file->getStream()->getContents(), $upload_file->getClientFilename())
                ->post('https://picupapi.tukeli.net/api/v1/matting2')->json();
        }

        $image_base64 = data_get($response, 'data.imageBase64');
        if (data_get($response, 'code') !== 0 || empty($image_base64)) {
            // 异常不上报,继续往下
            // return ['code' => 1, 'message' => data_get($response, 'msg') ?: 'unknown error'];
            // return ['code' => 1, 'message' =>'Your profile picture is flagged as abnormal. Please change it.'];
            if (empty($image_base64)) {
                if ($image_origin) {

                    $image_base64 = base64_encode(Http::timeout(200)->retry(1, 100)->get($image_origin)->body());
                } else {
                    $image_base64 = base64_encode($upload_file->getStream()->getContents());
                }
            }
        }

        $face_num = data_get($response, 'data.faceAnalysis.face_num');
        if ($face_limit && $face_num > $face_limit) {
            return ['code' => 1, 'message' => 'The avatar exceeds the display quantity, please change the picture'];
        }

        return ['code' => 0, 'image' => ['image_base64' => $image_base64]];
    }

    /**
     * ai滤镜
     */
    public function useFilter($data = [], $upload_file = null)
    {

        $image_origin_url = data_get($data, 'image_origin_url');
        $image_type_urls = data_get($data, 'image_type_urls');
        $face_limit = data_get($data, 'face_limit');
        $model = data_get($data, 'model') ?: 'gpt-image-1';
        $quality = data_get($data, 'quality') ?: 'low';
        $prompt = data_get($data, 'prompt') ?: 'just create image by rand';
        if ($image_type_urls) {
            $image_type_urls = is_array($image_type_urls) ? $image_type_urls : explode(',', $image_type_urls);
        }

        if (empty($image_origin_url) && empty($upload_file)) {
            return ['code' => 1, 'message' => 'image error'];
        }
        // 需要检测面部信息
        $return_base64 = '';
        if ($face_limit > 0) {
            $face_check_result = $this->faceCheck($data, $upload_file);

            if (data_get($face_check_result, 'code')) {
                return ['code' => 1, 'message' => data_get($face_check_result, 'message')];
            }
            // 检测了人头 而且滤镜就是人头的 可以直接返回了
            if (in_array($model, [Attachment::TYPE_GPT_IMAGE_3])) {
                $return_base64 = data_get($face_check_result, 'image.image_base64');
            }
        }
        if ($return_base64) {
            return ['code' => 0, 'image' => ['image_base64' => $return_base64]];
        }


        if (in_array($model, [Attachment::TYPE_GPT_IMAGE_2, Attachment::TYPE_GPT_IMAGE_3])) {

            //crop	是否裁剪至最小非透明区域，url参数，加在url后面，false不裁剪，true裁剪，不填写不裁剪	否
            //bgcolor	填充背景色，url参数，加在url后面，十六进制大小RGB颜色，如FFFFFF，不填写不填充背景色	否
            //outputFormat	输出图片格式，url参数，加在url后面，可以为png,webp,jpg_$quality($quality是压缩图片的值，为0～100之间，比如jpg_75)，默认值为png	否
            //# 抠图类型， 1：人像，2：物体，3：头像，4：一键美化，6：通用抠图, 11：卡通化。

            if ($upload_file) {
                $response = Http::timeout(200)->retry(2, 100)->withHeaders([
                    'APIKEY' => config('chatgpt.tukeli_token'),
                ])->attach('mattingType',  $model)->attach('crop', true)
                    ->attach('file', $upload_file->getStream()->getContents(), $upload_file->getClientFilename())
                    ->post('https://picupapi.tukeli.net/api/v1/matting2')
                    ->json();
            } else {
                $response = Http::timeout(200)->retry(2, 100)->withHeaders([
                    'APIKEY' =>  config('chatgpt.tukeli_token'),
                ])->get('https://picupapi.tukeli.net/api/v1/mattingByUrl', [
                    'url' => $image_origin_url,
                    'mattingType' => $model,
                    'crop' => true
                    // 'faceAnalysis' => true

                ])->json();
            }

            $return_base64 = data_get($response, 'data.imageBase64');
            if (data_get($response, 'code') !== 0 || empty($return_base64)) {
                Log::info('tukeli', [$response, 'image_url' => $image_origin_url]);
                return ['code' => 1, 'message' => 'Your profile picture is flagged as abnormal. Please change it.']; // data_get($response, 'msg') ?: '未知错误'];
            }
        } else if (in_array($model, [Attachment::TYPE_GPT_IMAGE_1, Attachment::TYPE_GPT_IMAGE_1_5, Attachment::TYPE_GPT_IMAGE_1_MINI])) {

            $image_type = 0;
            $http_attach = Http::timeout(200)->retry(2, 100)->withHeaders([
                'Authorization' => "Bearer " . config('chatgpt.token'),
            ])->asMultipart();

            if ($image_type_urls) {
                foreach ($image_type_urls as $image_type_url) {

                    $image_response = Http::timeout(200)->retry(3, 100)->get($image_type_url);
                    if ($image_response->successful()) {
                        $http_attach->attach('image[]', $image_response->body(), basename($image_type_url));
                        $image_type++;
                    }
                }
            }
            if (empty($upload_file)) {
                $http_attach->attach($image_type ? 'image[]' : 'image', Http::timeout(200)->retry(3, 100)->get($image_origin_url)->body(), basename($image_origin_url));
            } else {
                $http_attach->attach($image_type ? 'image[]' : 'image', $upload_file->getStream()->getContents(), $upload_file->getClientFilename());
            }

            $post_data = [
                'model' => $model,
                'quality' => $quality,
                'prompt' => $prompt,
            ];
            if (data_get($data, 'image_config.image_size')) {
                $post_data['size'] = $data['image_size'];
            }
            $response = $http_attach->post('https://api.openai.com/v1/images/edits', $post_data)->json();

            if (data_get($response, 'error.message')) {
                return ['code' => 1, 'message' => data_get($response, 'error.message')];
            }

            $return_base64 = data_get($response, 'data.0.b64_json');
        } else if (in_array($model, [Attachment::TYPE_GEMINI_IMAGE_2_5, Attachment::TYPE_GEMINI_IMAGE_3])) {


            $image_type = 0;
            $http_attach = Http::timeout(200)->retry(2, 100)->withHeaders([
                'x-goog-api-key' =>  config('chatgpt.gemini_api_key'),
                'Content-Type' =>  'application/json',
            ]);
            //{"inline_data":{"mime_type":"","data":""}}
            $inline_data_arr = [];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if ($image_type_urls) {
                foreach ($image_type_urls as $image_type_url) {
                    $image_response = Http::timeout(200)->retry(3, 100)->get($image_type_url);
                    if ($image_response->successful()) {
                        $image_body = $image_response->body();
                        $mimeType = $finfo->buffer($image_body);
                        $inline_data_arr[] = [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' =>  base64_encode($image_response->body())
                            ]
                        ];
                    }
                }
            }
            if (empty($upload_file)) {

                $image_response = Http::timeout(200)->retry(3, 100)->get($image_origin_url);
                if ($image_response->successful()) {
                    $image_body = $image_response->body();
                    $mimeType = $finfo->buffer($image_body);
                    $inline_data_arr[] = [
                        'inline_data' => [
                            'mime_type' => $mimeType,
                            'data' =>  base64_encode($image_response->body())
                        ]
                    ];
                }
            } else {

                $inline_data_arr[] = [
                    'inline_data' => [
                        'mime_type' => $upload_file->getMimeType(),
                        'data' =>  base64_encode($upload_file->getStream()->getContents())
                    ]
                ];
            }
            $inline_data_arr[] = ['text' => $prompt];
            //HARM_CATEGORY_HARASSMENT	Gemini - 骚扰内容。
            // HARM_CATEGORY_HATE_SPEECH	Gemini - 仇恨言论和内容。
            // HARM_CATEGORY_SEXUALLY_EXPLICIT	Gemini - 露骨色情内容。
            // HARM_CATEGORY_DANGEROUS_CONTENT	Gemini - 危险内容。
            //             关闭	OFF	关闭安全过滤条件
            // 全部不屏蔽	BLOCK_NONE	无论不安全内容的可能性如何，一律显示
            // 屏蔽少部分	BLOCK_ONLY_HIGH	在出现不安全内容的概率较高时屏蔽
            // 屏蔽一部分	BLOCK_MEDIUM_AND_ABOVE	当不安全内容的可能性为中等或较高时屏蔽
            // 屏蔽大部分	BLOCK_LOW_AND_ABOVE	当不安全内容的可能性为低、中或高时屏蔽
            $safetySettings = [
                [
                    "category" => "HARM_CATEGORY_HARASSMENT",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_HATE_SPEECH",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_DANGEROUS_CONTENT",
                    "threshold" => "BLOCK_NONE"
                ]
            ];



            $image_config = []; //['mimeType' => 'image/png'];
            $post_data =  [
                'contents' => ['parts' => $inline_data_arr],
                'generationConfig' => [
                    'responseModalities' => ['IMAGE'],
                    // 'imageConfig' => [
                    //     'aspectRatio' => '16:9',
                    //     'imageSize' => '2K' //gemini-3-pro-image-preview 支持

                    // ]
                ],
                // 开发者后台统一去设置
                // 'safetySettings' => $safetySettings,


            ];
            if (data_get($data, 'image_config.image_size')) {
                $image_config['imageSize'] = $data['image_config']['image_size'];
            }

            if (data_get($data, 'image_config.ratio')) {
                $image_config['aspectRatio'] = $data['image_config']['image_size'];
            }
            if ($image_config) {
                $post_data['generationConfig']['imageConfig'] = $image_config;
            }
            $response = $http_attach->post(
                config('chatgpt.gemini_api_url') . $model . ':generateContent',
                $post_data
            )->json();



            if (data_get($response, 'error.message')) {
                return ['code' => 1, 'message' => data_get($response, 'error.message')];
            }

            $return_base64 = data_get($response, 'candidates.0.content.parts.0.inlineData.data');
            if (empty($return_base64)) {
                Log::error('empty-gemini', [$response, 'image_original_url' => $image_origin_url]);
                return ['code' => 1, 'message' => 'gemini result empty'];
            }
        } else if (in_array($model, [Attachment::TYPE_VERTEX_AI_GEMINI_IMAGE_2_5, Attachment::TYPE_VERTEX_AI_GEMINI_IMAGE_3])) {

            $image_type = 0;
            $http_attach = Http::timeout(200)->retry(2, 100)->withHeaders([

                'Content-Type' =>  'application/json',
            ]);
            //{"inline_data":{"mime_type":"","data":""}}
            $inline_data_arr = [];
            //$finfo = new finfo(FILEINFO_MIME_TYPE);
            if ($image_type_urls) {
                foreach ($image_type_urls as $image_type_url) {
                    $image_response = Http::timeout(200)->retry(3, 100)->withOptions([
                        'allow_redirects' => true,
                    ])->head($image_type_url);
                    if (!$image_response->successful()) {
                        continue;
                    }
                    $inline_data_arr[] = [
                        'fileData' => [
                            'mimeType' => $image_response->header('Content-Type'),
                            'fileUri' => $image_type_url
                        ]
                    ];
                }
            }
            if (empty($upload_file)) {
                $image_response = Http::timeout(200)->retry(3, 100)->head($image_origin_url);

                if ($image_response->successful()) {
                    $inline_data_arr[] = [
                        'fileData' => [
                            'mimeType' => $image_response->header('Content-Type'),
                            'fileUri' => $image_origin_url
                        ]
                    ];
                }
            } else {

                $inline_data_arr[] = [
                    'inlineData' => [
                        'mimeType' => $upload_file->getMimeType(),
                        'data' =>  base64_encode($upload_file->getStream()->getContents())
                    ]
                ];
            }
            $inline_data_arr[] = ['text' => $prompt];
            //HARM_CATEGORY_HARASSMENT	Gemini - 骚扰内容。
            // HARM_CATEGORY_HATE_SPEECH	Gemini - 仇恨言论和内容。
            // HARM_CATEGORY_SEXUALLY_EXPLICIT	Gemini - 露骨色情内容。
            // HARM_CATEGORY_DANGEROUS_CONTENT	Gemini - 危险内容。
            //             关闭	OFF	关闭安全过滤条件
            // 全部不屏蔽	BLOCK_NONE	无论不安全内容的可能性如何，一律显示
            // 屏蔽少部分	BLOCK_ONLY_HIGH	在出现不安全内容的概率较高时屏蔽
            // 屏蔽一部分	BLOCK_MEDIUM_AND_ABOVE	当不安全内容的可能性为中等或较高时屏蔽
            // 屏蔽大部分	BLOCK_LOW_AND_ABOVE	当不安全内容的可能性为低、中或高时屏蔽
            $safetySettings = [
                [
                    "category" => "HARM_CATEGORY_HARASSMENT",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_HATE_SPEECH",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT",
                    "threshold" => "BLOCK_NONE"
                ],
                [
                    "category" => "HARM_CATEGORY_DANGEROUS_CONTENT",
                    "threshold" => "BLOCK_NONE"
                ]
            ];
            $safetySettings = [
                "category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT",
                "threshold" => "BLOCK_NONE"
            ];



            $image_config = []; // ['mimeType' => 'image/png'];
            $post_data =  [
                'contents' => ['parts' => $inline_data_arr, 'role' => 'USER'],
                'generationConfig' => [
                    'responseModalities' => ['IMAGE'],
                    // 'imageConfig' => [
                    //     'aspectRatio' => '16:9',
                    //     'imageSize' => '2K' //gemini-3-pro-image-preview 支持

                    // ]
                ],
                // 开发者后台统一去设置
                'safetySettings' => $safetySettings,


            ];
            if (data_get($data, 'image_config.image_size')) {
                $image_config['imageSize'] = $data['image_config']['image_size'];
            }

            if (data_get($data, 'image_config.ratio')) {
                $image_config['aspectRatio'] = $data['image_config']['image_size'];
            }
            if ($image_config) {
                $post_data['generationConfig']['imageConfig'] = $image_config;
            }

            $vertex_project_id = config('chatgpt.vertex_product_id');
            $vertex_key = config('chatgpt.vertex_key');
            $vertex_config = config('chatgpt.vertex_config') ?: [];
            $rand_num = rand(0, count($vertex_config) - 1);
            if ($vertex_config) {

                $vertex_key = $vertex_config[$rand_num]['key'] ?? '';
                $vertex_project_id = $vertex_config[$rand_num]['project_id'] ?? '';
            }
            Log::info('vertex_config', [$rand_num, $vertex_key, $vertex_project_id]);
            $vertex_url = "https://aiplatform.googleapis.com/v1beta1/projects/{$vertex_project_id}/locations/global/publishers/google/models/";
            $response = $http_attach->post(
                $vertex_url . preg_replace('/^VA-/', '', $model) . ':generateContent?key=' . $vertex_key,
                $post_data
            )->json();

            if (data_get($response, 'error.message')) {
                return ['code' => 1, 'message' => data_get($response, 'error.message')];
            }


            $return_base64 = data_get($response, 'candidates.0.content.parts.0.inlineData.data');
            if (empty($return_base64)) {
                Log::error('empty-ai-gemini', [$response, 'image_original_url' => $image_origin_url]);
                return ['code' => 1, 'message' => 'VA gemini result empty'];
            }
        } else {
            // 图片不需要任何处理,字节流转base64
            if ($upload_file) {
                // $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $return_base64 = ('data:' .  $upload_file->getMimeType() . ';base64,' . base64_encode(file_get_contents($upload_file->getRealPath())));
            } else {
                $return_base64 = base64_encode(Http::timeout(200)->retry(2, 100)->get($image_origin_url)->body());
            }
        }


        return ['code' => 0, 'image' => ['image_base64' => $return_base64]];
    }


    /**
     * ai滤镜
     */
    public function getFilter($id = 0)
    {

        $all_data = $this->cache->get('filter_list', []);
        if ($id == 0 || empty($all_data)) {
            $data = Http::withHeaders(['key' => config('pdm.pdm_secret'), 'key' => config('pdm.pdm_key')])->retry(3, 300)
                ->get('https://pdm.jialenet.com/rest/custom/filter')->json();
            if (data_get($data, 'error') !== 0) {
                return [];
            }
            $data = data_get($data, 'data') ?: [];
            if ($data) {
                $data = array_column($data, null, 'id');
                $this->cache->set('filter_list', $data, 3600 * 24 * 30);
            }
            return array_values($data);
        } else {

            $data = data_get($all_data, $id) ?: [];
            //json解析出来 还原
        }

        return $data;
    }



    /**
     * ai滤镜
     */
    public function getTemplate($id = 0)
    {

        $all_data = $this->cache->get('template_list', []);
        if (!$id || empty($all_data)) {
            $data = Http::withHeaders(['key' => config('pdm.pdm_secret'), 'key' => config('pdm.pdm_key')])->retry(3, 300)
                ->get('https://pdm.jialenet.com/rest/custom/template')->json();
            if (data_get($data, 'error') !== 0) {
                return [];
            }
            $data = data_get($data, 'data', []);
            if ($data) {
                $data = array_column($data, null, 'id');
                $this->cache->set('template_list', $data, 3600 * 24 * 30);
            }

            if ($id) {
                $data = Arr::only(data_get($all_data, $id) ?: [], ['content', 'id']);
            } else {
                $data = ['content' => '', 'id' => 0];
            }
        } else {

            $data = Arr::only(data_get($all_data, $id) ?: [], ['content', 'id']);
        }

        return  $data;
    }



    /**
     * ai滤镜
     */
    public function useFilterGen($data = [])
    {


        $use_data = [
            'url' => data_get($data, 'url'),
            'url2' => data_get($data, 'url2'),
            'face_limit' => data_get($data, 'face_limit'),
            'image_base64' => data_get($data, 'image_base64'),
            'model' => data_get($data, 'model') ?: 'gpt-image-1',
            'quality' => data_get($data, 'quality') ?: 'low',
            'prompt' => data_get($data, 'prompt') ?: 'just create image by rand',
        ];
        if (empty($use_data['url']) && empty($use_data['image_base64'])) {
            return ['code' => 1, 'message' => 'image error'];
        }


        if (empty($image_base64)) {
            $image_base64 = Http::timeout(200)->retry(1, 100)->get($use_data['url'])->body();
        } else {
            $image_base64 =  base64_decode(preg_replace('/^data:\w+\/\w+;base64,/', '', $image_base64));
        }
        $response = Http::timeout(200)->withHeaders([
            'Authorization' => "Bearer " . config('chatgpt.token'),
        ])
            ->attach('image', Http::timeout(200)->retry(1, 100)->get($use_data['url'])->body(), '1.png')
            ->attach('mask', Http::timeout(200)->retry(1, 100)->get($use_data['url2'])->body(), '2.png')
            ->asMultipart()
            ->post('https://api.openai.com/v1/images/edits', [
                'model' => data_get($data, 'model') ?: 'gpt-image-1',
                'quality' => data_get($data, 'quality') ?: 'low',
                'prompt' => data_get($data, 'prompt'),
            ])->json();

        if (data_get($response, 'error.message')) {
            return ['code' => 1, 'message' => data_get($response, 'error.message')];
        }

        $return_base64 = data_get($response, 'data.0.b64_json');



        return ['code' => 0, 'image' => ['image_base64' => $return_base64]];
    }

    public function getRepository(): AttachmentRepository
    {
        return $this->repository;
    }
}
