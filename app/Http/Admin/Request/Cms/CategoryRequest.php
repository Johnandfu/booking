<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Cms;

use App\Http\Common\Request\Traits\NoAuthorizeTrait;
use Hyperf\Validation\Request\FormRequest;

class CategoryRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
            'description' => 'sometimes|nullable|string|max:500',
            'sort' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ];
    }
}
