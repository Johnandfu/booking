<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Cms;

use App\Http\Common\Request\Traits\NoAuthorizeTrait;
use Hyperf\Validation\Request\FormRequest;

class ArticleRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer',
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200',
            'summary' => 'sometimes|nullable|string|max:500',
            'content' => 'sometimes|nullable|string',
            'cover' => 'sometimes|nullable|string|max:500',
            'author' => 'sometimes|nullable|string|max:50',
            'sort' => 'sometimes|integer',
            'status' => 'sometimes|integer',
            'published_at' => 'sometimes|nullable|date',
            'seo_title' => 'sometimes|nullable|string|max:200',
            'seo_description' => 'sometimes|nullable|string|max:500',
        ];
    }
}
