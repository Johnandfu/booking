<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Cms;

use App\Http\Common\Request\Traits\NoAuthorizeTrait;
use Hyperf\Validation\Request\FormRequest;

class TeamMemberRequest extends FormRequest
{
    use NoAuthorizeTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'position' => 'sometimes|nullable|string|max:100',
            'phone' => 'sometimes|nullable|string|max:50',
            'avatar' => 'sometimes|nullable|string|max:500',
            'sort' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ];
    }
}
