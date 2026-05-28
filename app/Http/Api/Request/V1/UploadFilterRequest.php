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

namespace App\Http\Api\Request\V1;

use App\Http\Common\Request\Traits\NoAuthorizeTrait;
use Hyperf\Swagger\Annotation\Property;
use Hyperf\Swagger\Annotation\Schema;
use Hyperf\Validation\Request\FormRequest;


class UploadFilterRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'file' => ['required_without:image_origin_url', 'mimes:jpg,jpeg,png,gif,webp', 'max:20480'], // 最大20MB
            'image_origin_url' => ['required_without:file', 'url'], //
            'filter_id' => ['sometimes', 'string', 'nullable'],
            'face_limit' => ['sometimes', 'integer', 'nullable', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'image_origin_url' => trans('attachment.file'),
            'file' => trans('attachment.file'),
        ];
    }
}
