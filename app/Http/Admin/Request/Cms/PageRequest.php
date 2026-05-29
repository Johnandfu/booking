<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Cms;

use App\Http\Common\Request\Traits\NoAuthorizeTrait;
use Hyperf\Validation\Request\FormRequest;

class PageRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200',
            'content' => 'sometimes|nullable|string',
            'status' => 'sometimes|integer',
            'seo_title' => 'sometimes|nullable|string|max:200',
            'seo_description' => 'sometimes|nullable|string|max:500',
        ];
    }
}
