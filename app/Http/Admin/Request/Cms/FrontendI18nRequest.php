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
            'group_type' => 'required|string|max:50',
            'key' => 'required|string|max:100',
            'title' => 'sometimes|nullable|string|max:100',
            'language_code' => 'required|string|max:20',
            'value' => 'sometimes|nullable|string',
            'status' => 'sometimes|integer',
            'sort' => 'sometimes|integer',
        ];
    }
}
