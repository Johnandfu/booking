<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Cms;

use App\Http\Common\Request\Traits\NoAuthorizeTrait;
use Hyperf\Validation\Request\FormRequest;

class BannerRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'image' => 'required|string|max:500',
            'link' => 'sometimes|nullable|string|max:500',
            'description' => 'sometimes|nullable|string|max:500',
            'sort' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ];
    }
}
