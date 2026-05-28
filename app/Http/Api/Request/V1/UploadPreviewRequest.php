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


class UploadPreviewRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:20480', 'min:1'], // 最大20MB
            //'url' => 'required_without:file|string|max:30000000', // 最大50MB
            // 'filter_id'=> 'sometimes|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => trans('attachment.file'),
            // 'image_base64' => trans('attachment.file'),
        ];
    }
}
