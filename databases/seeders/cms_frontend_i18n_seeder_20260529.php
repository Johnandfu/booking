<?php

declare(strict_types=1);

use App\Model\Cms\CmsFrontendI18n;
use App\Service\Cms\CmsContentService;
use Hyperf\Database\Seeders\Seeder;

class CmsFrontendI18nSeeder20260529 extends Seeder
{
    public function run(): void
    {
        $sort = 0;
        foreach (CmsContentService::FRONTEND_I18N_DEFAULTS['en'] as $key => $en) {
            CmsFrontendI18n::query()->updateOrCreate(
                ['key' => $key],
                [
                    'group_name' => $this->group($key),
                    'title' => $this->title($key),
                    'en' => $en,
                    'zh' => CmsContentService::FRONTEND_I18N_DEFAULTS['zh'][$key] ?? '',
                    'status' => CmsFrontendI18n::STATUS_ENABLED,
                    'sort' => $sort += 10,
                ]
            );
        }
    }

    private function group(string $key): string
    {
        return match (true) {
            str_starts_with($key, 'article') => 'article',
            str_starts_with($key, 'about') => 'about',
            str_starts_with($key, 'contact') || in_array($key, ['findUs', 'mapTitlePrefix', 'mapTitle', 'mapText', 'mapLocation', 'ready', 'address', 'emailBody', 'hotBody', 'hours', 'hoursBody', 'send', 'messageIntro', 'name', 'email', 'phone', 'message', 'submit'], true) => 'contact',
            str_starts_with($key, 'category') => 'category',
            str_starts_with($key, 'news') => 'news',
            str_starts_with($key, 'service') || str_starts_with($key, 'listing') || $key === 'viewDetails' => 'service',
            str_starts_with($key, 'page') || str_starts_with($key, 'notFound') => 'page',
            str_starts_with($key, 'footer') || str_starts_with($key, 'social') || in_array($key, ['siteName', 'description', 'home', 'portal', 'book', 'signin', 'navToggle', 'paginationLabel'], true) => 'layout',
            default => 'home',
        };
    }

    private function title(string $key): string
    {
        return ucwords((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $key));
    }
}
