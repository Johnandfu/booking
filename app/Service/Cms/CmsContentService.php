<?php

declare(strict_types=1);

namespace App\Service\Cms;

final class CmsContentService
{
    public const DEFAULT_FRONTEND_LOCALE = 'en';

    public const FRONTEND_LOCALES = [
        'en' => 'English',
        'zh-CN' => '中文',
        'zh-TW' => '中文繁體',
    ];

}
