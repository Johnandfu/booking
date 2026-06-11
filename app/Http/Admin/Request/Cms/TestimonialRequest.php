<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Cms;

use App\Http\Common\Request\Traits\NoAuthorizeTrait;
use Hyperf\Validation\Request\FormRequest;

class TestimonialRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'position' => 'sometimes|nullable|string|max:100',
            'avatar' => 'sometimes|nullable|string|max:500',
            'content' => 'sometimes|nullable|string',
            'sort' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ];
    }
}
