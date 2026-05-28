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

namespace App\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model as MineModel;

/**
 * @property int $id 主键
 * @property int $storage_mode 存储模式 (1 本地 2 阿里云 3 七牛云 4 腾讯云)
 * @property string $origin_name 原文件名
 * @property string $object_name 新文件名
 * @property string $hash 文件hash
 * @property string $mime_type 资源类型
 * @property string $storage_path 存储目录
 * @property string $suffix 文件后缀
 * @property int $size_byte 字节数
 * @property string $size_info 文件大小
 * @property string $url url地址
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $remark 备注
 */
final class Attachment extends MineModel
{

    const TYPE_GPT_IMAGE_1 = 'gpt-image-1';
    const TYPE_GPT_IMAGE_1_5 = 'gpt-image-1.5';
    const TYPE_GPT_IMAGE_1_MINI = 'gpt-image-1-mini';
    const TYPE_GPT_IMAGE_2 = '1';
    const TYPE_GPT_IMAGE_3 = '3';
    const TYPE_GPT_IMAGE_4 = 'noting';
    const TYPE_GEMINI_IMAGE_2_5 = 'gemini-2.5-flash-image';
    const TYPE_GEMINI_IMAGE_3 = 'gemini-3-pro-image-preview';

    const TYPE_VERTEX_AI_GEMINI_IMAGE_2_5 = 'VA-gemini-2.5-flash-image';
    const TYPE_VERTEX_AI_GEMINI_IMAGE_3 = 'VA-gemini-3-pro-image-preview';

    const TYPE = [
        self::TYPE_GPT_IMAGE_1 => 'OpenAI GPT-image-1',
        self::TYPE_GPT_IMAGE_1_5 => 'OpenAI GPT-image-1.5',
        self::TYPE_GPT_IMAGE_1_MINI => 'OpenAI GPT-image-1-mini',
        self::TYPE_GPT_IMAGE_2 => '图可丽 人像抠图',
        self::TYPE_GPT_IMAGE_3 => '图可丽 头像抠图',
        self::TYPE_GPT_IMAGE_4 => '原图返回',
        self::TYPE_GEMINI_IMAGE_2_5 => 'gemini-2.5 flash-image',
        self::TYPE_GEMINI_IMAGE_3 => 'gemini-3-pro image-preview',
        self::TYPE_VERTEX_AI_GEMINI_IMAGE_2_5 => 'Vertex-AI Gemini2.5 flash image',
        self::TYPE_VERTEX_AI_GEMINI_IMAGE_3 => 'Vertex-AI Gemini3Pro image preview',

    ];
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'attachment';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'storage_mode', 'origin_name', 'object_name', 'hash', 'mime_type', 'storage_path', 'suffix', 'size_byte', 'size_info', 'url', 'created_by', 'updated_by', 'created_at', 'updated_at', 'remark'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'storage_mode' => 'integer', 'size_byte' => 'integer', 'created_by' => 'integer', 'updated_by' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
