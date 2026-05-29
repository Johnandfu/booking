<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Cms;

use App\Http\Common\Request\Traits\NoAuthorizeTrait;
use Hyperf\Validation\Request\FormRequest;

class FrontendI18nRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'key' => 'required|string|max:100',
            'group_name' => 'required|string|max:50',
            'title' => 'required|string|max:100',
            'en' => 'sometimes|nullable|string',
            'zh' => 'sometimes|nullable|string',
            'status' => 'sometimes|integer',
            'sort' => 'sometimes|integer',
        ];
    }
}
