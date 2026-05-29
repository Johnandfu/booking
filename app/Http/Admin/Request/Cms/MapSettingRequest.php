<?php

declare(strict_types=1);

namespace App\Http\Admin\Request\Cms;

use Hyperf\Validation\Request\FormRequest;

final class MapSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'zoom' => 'required|integer|between:1,20',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => '地图标题',
            'address' => '地址',
            'latitude' => '纬度',
            'longitude' => '经度',
            'zoom' => '缩放级别',
        ];
    }
}
