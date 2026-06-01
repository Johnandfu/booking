<?php

declare(strict_types=1);

namespace App\Service\Cms;

use App\Model\Cms\CmsArticle;
use App\Model\Cms\CmsBanner;
use App\Model\Cms\CmsCategory;
use App\Model\Cms\CmsContactMessage;
use App\Model\Cms\CmsFrontendI18n;
use App\Model\Cms\CmsPage;
use App\Repository\Cms\CmsArticleRepository;
use App\Repository\Cms\CmsBannerRepository;
use App\Repository\Cms\CmsCategoryRepository;
use App\Repository\Cms\CmsContactMessageRepository;
use App\Repository\Cms\CmsFrontendI18nRepository;
use App\Repository\Cms\CmsMapSettingRepository;
use App\Repository\Cms\CmsPageRepository;
use Hyperf\Collection\Collection;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Throwable;

final class CmsContentService
{
    public function __construct(
        private readonly CmsCategoryRepository $categoryRepository,
        private readonly CmsArticleRepository $articleRepository,
        private readonly CmsBannerRepository $bannerRepository,
        private readonly CmsPageRepository $pageRepository,
        private readonly CmsContactMessageRepository $contactMessageRepository,
        private readonly CmsFrontendI18nRepository $frontendI18nRepository,
        private readonly CmsMapSettingRepository $mapSettingRepository,
    ) {}

    /**
     * @return Collection<int, CmsCategory>
     */
    public function navCategories(): Collection
    {
        return $this->categoryRepository->listEnabled();
    }

    /**
     * @return Collection<int, CmsCategory>
     */
    public function localizedNavCategories(array $copy): Collection
    {
        $categories = $this->navCategories();
        foreach ($categories as $category) {
            $this->localizeCategory($category, $copy);
        }

        return $categories;
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function latestArticles(int $limit = 6): Collection
    {
        return $this->articleRepository->latestPublished($limit);
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function serviceArticles(int $limit = 4): Collection
    {
        return $this->categoryArticles('service', $limit);
    }

    /**
     * @return Collection<int, CmsArticle>
     */
    public function categoryArticles(string $slug, int $limit = 10): Collection
    {
        $category = $this->categoryBySlug($slug);
        if ($category === null) {
            return new Collection();
        }

        return $this->articleRepository->latestPublishedByCategory($category->id, $limit);
    }

    /**
     * @return Collection<int, CmsBanner>
     */
    public function banners(): Collection
    {
        try {
            return $this->bannerRepository->listEnabled();
        } catch (Throwable) {
            return new Collection();
        }
    }

    public function categoryBySlug(string $slug): ?CmsCategory
    {
        return $this->categoryRepository->findBySlug($slug);
    }

    public function articleBySlug(string $slug): ?CmsArticle
    {
        return $this->articleRepository->findPublishedBySlug($slug);
    }

    public function pageBySlug(string $slug): ?CmsPage
    {
        return $this->pageRepository->findEnabledBySlug($slug);
    }

    public function articlesByCategory(int $categoryId, int $page, int $pageSize = 10): LengthAwarePaginatorInterface
    {
        return $this->articleRepository->paginatePublishedByCategory($categoryId, $page, $pageSize);
    }

    public function categoryPath(string $slug): string
    {
        return match ($slug) {
            'news' => '/news',
            'service' => '/service',
            default => '/category/' . $slug,
        };
    }

    public function localizeFrontendViewData(array &$viewData, array $copy): void
    {
        if (($viewData['category'] ?? null) instanceof CmsCategory) {
            $this->localizeCategory($viewData['category'], $copy);
            $viewData['title'] = $viewData['category']->name;
            $viewData['description'] = $viewData['category']->description;
        }

        foreach (['newsCategory', 'serviceCategory'] as $categoryKey) {
            if (($viewData[$categoryKey] ?? null) instanceof CmsCategory) {
                $this->localizeCategory($viewData[$categoryKey], $copy);
            }
        }

        foreach ($this->viewArticles($viewData) as $article) {
            if (
                $article instanceof CmsArticle
                && $article->relationLoaded('category')
                && $article->category instanceof CmsCategory
            ) {
                $this->localizeCategory($article->category, $copy);
            }
        }
    }

    public function recordArticleView(CmsArticle $article): void
    {
        $this->articleRepository->incrementViews($article->id);
    }

    public function createContactMessage(array $data): CmsContactMessage
    {
        return $this->contactMessageRepository->create($data);
    }

    private function localizeCategory(CmsCategory $category, array $copy): void
    {
        $key = match ($category->slug) {
            'news' => 'category-news',
            'service' => 'category-service',
            default => null,
        };

        $category->frontend_path = $this->categoryPath($category->slug);

        if ($key === null) {
            return;
        }

        $category->name = $copy[$key] ?? $category->name;
        $category->description = $copy[$key . 'Description'] ?? $category->description;
    }

    private function viewArticles(array $viewData): array
    {
        $articles = [];
        if (($viewData['article'] ?? null) instanceof CmsArticle) {
            $articles[] = $viewData['article'];
        }

        if (($viewData['articles'] ?? null) instanceof Collection) {
            foreach ($viewData['articles'] as $article) {
                $articles[] = $article;
            }
        }

        if (($viewData['paginator'] ?? null) instanceof LengthAwarePaginatorInterface) {
            foreach ($viewData['paginator']->items() as $article) {
                $articles[] = $article;
            }
        }

        return $articles;
    }

    public static function frontendDefaultLocale(): string
    {
        $locales = self::frontendLocales();
        $defaultLocale = (string) env('FRONTEND_DEFAULT_LOCALE', '');

        return isset($locales[$defaultLocale])
            ? $defaultLocale
            : (array_key_first($locales) ?: 'en');
    }

    public static function frontendLocales(): array
    {
        $locales = [];
        $configuredLocales = explode('|', (string) env('FRONTEND_LOCALES', ''));

        foreach ($configuredLocales as $configuredLocale) {
            $parts = array_map('trim', explode(':', $configuredLocale));
            $locale = $parts[0] ?? '';
            $column = $parts[1] ?? '';
            if ($locale === '' || $column === '') {
                continue;
            }

            $locales[$locale] = [
                'column' => $column,
                'aliases' => array_values(array_filter(array_map('trim', explode(',', $parts[2] ?? $locale)))),
            ];

            if (! empty($parts[3])) {
                $locales[$locale]['fallback'] = $parts[3];
            }
        }

        if ($locales !== []) {
            return $locales;
        }

        return [
            'en' => ['column' => 'en', 'aliases' => ['en']],
        ];
    }

    public function frontendI18n(string $locale): array
    {
        $locales = self::frontendLocales();
        $defaultLocale = self::frontendDefaultLocale();
        $locale = isset($locales[$locale]) ? $locale : $defaultLocale;
        $localeConfig = $locales[$locale];
        $fallbackLocale = $localeConfig['fallback'] ?? $locale;
        $column = $localeConfig['column'] ?? $locales[$defaultLocale]['column'];
        $fallbackColumn = $locales[$fallbackLocale]['column'] ?? null;
        $defaultColumn = $locales[$defaultLocale]['column'] ?? $column;
        $columns = array_values(array_unique(array_filter([$column, $fallbackColumn, $defaultColumn])));

        try {
            $messages = [];
            foreach ($this->frontendI18nRepository->listEnabled() as $item) {
                $this->fillFrontendMessage($messages, $item, $columns);
            }
        } catch (Throwable) {
            return [];
        }

        return $messages;
    }

    private function fillFrontendMessage(array &$messages, CmsFrontendI18n $item, array $columns): void
    {
        foreach ($columns as $column) {
            $value = trim((string) ($item->{$column} ?? ''));
            if ($value !== '') {
                $messages[$item->group_type . '-' . $item->key] = $value;
                return;
            }
        }
    }
}
