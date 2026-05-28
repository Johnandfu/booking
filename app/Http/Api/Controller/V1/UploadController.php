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

namespace App\Http\Api\Controller\V1;

use App\Factory\Log;
use App\Http\Api\Request\V1\UploadFilterRequest;
use App\Http\Api\Request\V1\UploadPreviewRequest;
use App\Http\Api\Request\V1\UserRequest;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\Common\ResultCode;
use App\Model\Attachment;
use App\Model\UserFilterLog;
use App\Service\UploadService;
use Carbon\Carbon;
use finfo;
use FriendsOfHyperf\Http\Client\Http;
use Hyperf\Cache\Cache;
use Hyperf\Carbon\Carbon as HyperfCarbon;
use Hyperf\Collection\Arr;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Get;

use Hyperf\RateLimit\Annotation\RateLimit;
use Psr\SimpleCache\CacheInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Stringable\Str;
use Psr\Log\LoggerInterface;

use function Hyperf\Config\config;

#[HyperfServer(name: 'http')]
final class UploadController extends AbstractController
{


    public function __construct(
        protected readonly UploadService $service,
        protected readonly CacheInterface $cache,
        protected readonly LoggerInterface $logger

    ) {}

    // #[Inject]
    // private UploadService $uploadService;
    #[Post(
        path: '/api/v1/preview',
        operationId: 'ApiV1Preview',
        summary: '上传图片预览',
        tags: ['api'],
    )]
    #[RateLimit(create: 1, consume: 3, capacity: 60, key: [AbstractController::class, "rateLimitKey"])]
    public function preview(UploadPreviewRequest $request, \Hyperf\Filesystem\FilesystemFactory $factory): Result
    {

        $uploadFile = $request->file('file');
        $ip = $this->realIP();
        $ip_num = $this->cache->get('upload_ip_' . $ip, 0);
        if ($ip_num >= 150  && !in_array($ip, ['172.104.172.135', '14.145.128.103', '14.145.143.155'])) {
            return $this->error('The number of uploads today has exceeded the limit');
        }

        $this->cache->set('upload_ip_' . $ip,  $ip_num + 1,  3600 * 24);

        $imageBase64 =  $request->input('image_base64') ?? '';
        // Write Files
        $fileName = Carbon::now()->format('Ymd') . '/';

        if (preg_match('/^data:(.*?);base64,(.*)$/', $imageBase64, $matches) && 0) {
            $mimeType = $matches[1];
            $data = base64_decode($matches[2]);
            // 生成文件名
            $extension = explode('/', $mimeType)[1] ?? 'png';
            $fileName .= md5($data) . '.' . $extension;
        } else {
            $data = file_get_contents($uploadFile->getRealPath());
            $extension = $uploadFile->getExtension();
            $fileName .= md5($data) . '.' . $extension;
            unlink($uploadFile->getRealPath());
        }
        $local = $factory->get('oss');
        $local->write($fileName, $data);
        $image_url = $local->publicUrl($fileName);
        return $this->json(ResultCode::JSON_CODE, [
            'path' => $image_url,
            'url' => $image_url
        ]);
    }

    // #[Inject]
    // private UploadService $uploadService;
    #[Post(
        path: '/api/v1/desc',
        operationId: 'ApiV1Desc',
        summary: '图片描述生成',
        tags: ['api'],
    )]
    public function desc(RequestInterface $request): Result
    {
        $token = $request->input('token');
        if ($token != 'tss') {
            return $this->error('token错误');
        }
        $data = $request->all();

        $result = $this->service->aiDesc($data);
        if (data_get($result, 'code', 0) != 0) {
            return $this->error(data_get($result, 'message', '图片描述生成失败'));
        }

        return $this->json(ResultCode::JSON_CODE, data_get($result, 'image', []), 'success');
    }

    // 使用滤镜生成图片
    #[Post(
        path: '/api/v1/filter',
        operationId: 'ApiV1Filter',
        summary: '图片滤镜生成',
        tags: ['api'],
    )]
    #[RateLimit(create: 1, consume: 5, capacity: 60, key: [AbstractController::class, "rateLimitKey"])]
    public function filter(UploadFilterRequest $request, \Hyperf\Filesystem\FilesystemFactory $factory): Result
    {

        $uploadFile = $request->file('file');
        $filterId = $request->input('filter_id') ?: '-1';
        $face_limit = $request->input('face_limit') ?: 0;
        $platform_id = $request->input('platform_id') ?: '';
        $upload_file_url = $request->input('image_origin_url') ?: '';

        $user_filter_log = null;

        $local = $factory->get(config('file.default', 'oss'));

        $ip = static::realIP();


        $token = $request->input('token') ?: '';
        $data = [
            'face_limit' => $face_limit,
            'prompt' => $request->input('prompt') ?: 'just create image by rand',
            'quality' => $request->input('quality'),
            'model' => $request->input('model'),
            'image_type_urls' => $request->input('image_type_urls'),
            'image_origin_url' => $upload_file_url
        ];

        $ip_num = $this->cache->get('filter_ip_' . $ip, 0);
        if ($ip_num >= 26 && $token != 'tss' && !in_array($ip, ['172.104.172.135', '14.145.128.103', '14.145.143.155'])) {
            Log::info('ip-limit', ['ip' => $ip, 'ip_num' => $ip_num]);

            return $this->error('The number of uploads today has exceeded the limit');
        }

        $this->cache->set('filter_ip_' . $ip, $ip_num + 1, 3600 * 24);

        $filter_ids = explode(',', $filterId);
        $result_image_url = '';

        if ($uploadFile && $uploadFile->getSize() <= 100) {
            Log::error('upload-file', [$uploadFile->getExtension(), $uploadFile->getSize()]);
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        foreach ($filter_ids as $key => $filter_id) {
            // filterId是可以传值多个的用,区分多个滤镜 使用上一个滤镜的结果作为下一个滤镜的输入
            if ($key > 4) {
                continue;
            }
            $start_time = Carbon::now()->getTimestamp();
            $user_filter_log = null;

            //使用返回的滤镜图
            if ($result_image_url) {
                // Log::info('data', [$data]);
                $data = Arr::only($data, ['face_limit', 'model', 'prompt']);
                $data['image_origin_url'] = $result_image_url;
            }
            $filter_data = [];

            // 开始记录日志
            if ($filter_id > 0) {

                $filter_data = $this->service->getFilter($filter_id);
                if ($result_image_url) {
                    $upload_file_url = $result_image_url;
                    $uploadFile = null;
                } else if ($uploadFile) {
                    $upload_file_name = Carbon::now()->format('Ymd') . '/' . Str::random(10) . Carbon::now()->getTimestamp() . '.' . $uploadFile->getExtension();
                    $local->write($upload_file_name, $uploadFile->getStream()->getContents());
                    $upload_file_url = $local->publicUrl($upload_file_name);
                }
                $user_filter_log =  UserFilterLog::create([
                    'platform_id' => $platform_id,
                    'origin_image' => $upload_file_url,
                    'filter_id' => $filter_id,
                    'ip_address' => $ip,
                    'os' => static::clientOS(),
                    'browser' => static::clientBrowser(),
                    'model' => Attachment::TYPE[data_get($filter_data, 'config.model') ?: '-1'] ?? data_get($filter_data, 'config.model'),
                    'status' => UserFilterLog::STATUS_FAIL
                ]);
            }

            if ($token != 'tss' && empty($filter_data)) {
                Log::info('filter-empty', ['ip' => $ip, 'filter_id' => $filter_id]);
                if ($user_filter_log) {
                    $user_filter_log->fill(['remark' => 'filter-empty'])->save();
                }
                //出现错误,直接略过
                if ($key == 0) {
                    return $this->error('filter id error');
                } else {
                    continue;
                }
            }

            // 如果前台用户只能传值file 使用特定的filter
            $data['model'] = data_get($filter_data, 'config.model') ?: data_get($data, 'model');
            $data['quality'] = (data_get($filter_data, 'config.filter_quality') ?: data_get($data, 'quality')) ?: 'medium';
            $data['prompt'] = data_get($filter_data, 'config.prompt') ?: data_get($data, 'prompt');

            if (data_get($filter_data, 'config.use_image_type') == 'on') {
                // 优先 config.image_type_data
                $image_type_data = data_get($filter_data, 'config.image_type_data') ?: [];
                if (!is_array($image_type_data)) {
                    $image_type_data = [];
                } else {
                    $image_type_data = array_column($image_type_data, 'path') ?: [];
                }
                $data['image_type_urls'] = ($image_type_data ?: data_get($filter_data, 'image_type.path')) ?: data_get($data, 'image_type_urls');
            }


            if (!empty($data['image_type_urls'])) {
                $data['image_type_urls'] = is_array($data['image_type_urls']) ? $data['image_type_urls'] : explode(',', $data['image_type_urls']);
            }

            try {

                $result = $this->service->useFilter($data, $uploadFile);
                if (data_get($result, 'code', 0) !== 0) {
                    if ($user_filter_log) {
                        $user_filter_log->fill(['remark' => data_get($result, 'message') ?: ''])->save();
                    }
                    Log::error('filter-error-result', [$filter_id]);
                    if ($key == 0) {
                        return $this->error(data_get($result, 'message', 'ai system error'));
                    }
                    continue;
                }
                $imageBase64 = data_get($result, 'image.image_base64') ?: '';
                if (!$imageBase64) {
                    Log::error('base64 empty', [$imageBase64, $result]);
                }

                $fileName = Carbon::now()->format('Ymd') . '/';

                if (Str::startsWith($imageBase64, 'data:')) {
                    $pos = strpos($imageBase64, ',');
                    $imageBase64 = substr($imageBase64, $pos + 1);
                }

                $image_data = base64_decode($imageBase64);
                if (!$image_data) {
                    Log::error('base64', [$image_data]);
                }

                $mimeType = $finfo->buffer($image_data) ?: 'image/png';
                $extension = explode('/', $mimeType)[1] ?? 'png';
                // 生成文件名
                $fileName .= md5($image_data) . '.' . $extension;

                $local->write($fileName, $image_data);
                $result_image_url = $local->publicUrl($fileName);
                if ($user_filter_log) {
                    $user_filter_log->fill([
                        'use_second' => Carbon::now()->getTimestamp()  - $start_time,
                        'filter_image' => $result_image_url,
                        'status' => UserFilterLog::STATUS_SUCCESS
                    ])->save();
                }
            } catch (\Throwable $th) {
                Log::error('filter-error', ['error' => $th->getMessage(), 'model' => data_get($data, 'model'),  'line' => $th->getLine(), 'file' => $th->getFile()]);
                if ($user_filter_log) {
                    $user_filter_log->fill(['use_second' => Carbon::now()->getTimestamp()  - $start_time, 'remark' => substr($th->getMessage(), 0, 1900)])->save();
                }
                if ($key == 0) {
                    return $this->error('ai system error');
                }
            }
        }

        if (!$result_image_url) {
            return $this->error('ai system error');
        }


        return $this->json(ResultCode::JSON_CODE, [
            'path' => $result_image_url,
            'url' => $result_image_url
        ]);
    }

    //清理滤镜信息
    #[Post(
        path: '/api/v1/filter-clear',
        operationId: 'ApiV1FilterClear',
        summary: '清理滤镜信息',
        tags: ['api'],
    )]

    #[RateLimit(create: 1, capacity: 1, key: [AbstractController::class, "rateLimitKey"])]
    public function filterClear(RequestInterface $request): Result
    {
        $token = $request->input('token');
        if ($token != 'tss') {
            return $this->error('同步失败token错误');
        }
        $result = $this->service->getFilter(0);
        if (!$result) {
            return $this->error('同步失败');
        }
        return $this->json(ResultCode::JSON_CODE, $result, '同步成功');
    }


    //清理滤镜信息
    #[Post(
        path: '/api/v1/template-clear',
        operationId: 'ApiV1TemplateClear',
        summary: '清理模板信息',
        tags: ['api'],
    )]
    #[RateLimit(create: 1, capacity: 1, key: [AbstractController::class, "rateLimitKey"])]
    public function templateClear(RequestInterface $request, \Hyperf\Filesystem\FilesystemFactory $factory): Result
    {

        $token = $request->input('token');
        if ($token != 'tss') {
            return $this->error('同步失败token错误');
        }
        $result = $this->service->getTemplate(0);
        if (!$result) {
            return $this->error('同步失败');
        }
        return $this->success($result);
    }

    //获取模板信息
    #[Get(
        path: '/api/v1/template/{id}',
        operationId: 'ApiV1Template',
        summary: '获取模板信息',
        tags: ['api'],
    )]
    public function template(int $id, RequestInterface $request): Result
    {

        if (!$id) {
            return $this->success();
        }
        $result = $this->service->getTemplate($id);
        if (!$result) {
            return $this->success();
        }
        return $this->success($result);
    }
}
