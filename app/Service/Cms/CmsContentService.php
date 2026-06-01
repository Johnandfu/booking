<?php

declare(strict_types=1);

namespace App\Service\Cms;

final class CmsContentService
{
    public static function frontendLocales(): array
    {
        return [
            'en' => ['aliases' => ['en']],
            'zh-CN' => ['aliases' => ['zh', 'zh-cn', 'zh-sg']],
            'zh-TW' => ['aliases' => ['zh-tw', 'zh-hk', 'zh-mo']],
        ];
    }
}
