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
        $defaultLocale = CmsContentService::frontendDefaultLocale();
        $zhLocale = $this->localeByColumn('zh') ?? $defaultLocale;
        $defaults = require __DIR__ . '/data/cms_frontend_i18n.php';

        foreach (($defaults[$defaultLocale] ?? []) as $groupType => $messages) {
            foreach ($messages as $key => $en) {
                CmsFrontendI18n::query()->updateOrCreate(
                    ['group_type' => $groupType, 'key' => $key],
                    [
                        'title' => $this->title($groupType, $key),
                        'en' => $en,
                        'zh' => $defaults[$zhLocale][$groupType][$key] ?? '',
                        'status' => CmsFrontendI18n::STATUS_ENABLED,
                        'sort' => $sort += 10,
                    ]
                );
            }
        }
    }

    private function title(string $groupType, string $key): string
    {
        return $groupType . ' / ' . ucwords((string) preg_replace('/(?<!^)[A-Z]/', ' $0', $key));
    }

    private function localeByColumn(string $column): ?string
    {
        foreach (CmsContentService::frontendLocales() as $locale => $config) {
            if (($config['column'] ?? null) === $column) {
                return $locale;
            }
        }

        return null;
    }
}
