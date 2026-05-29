<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Model\Cms\CmsArticle;
use App\Model\Cms\CmsCategory;
use App\Service\Cms\CmsContentService;
use Hyperf\Collection\Collection;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseController
{
    #[Inject]
    protected CmsContentService $cmsContent;

    #[Inject]
    protected RequestInterface $request;

    protected function view(RenderInterface $render, string $template, array $data = []): ResponseInterface
    {
        $locale = $this->locale();
        $frontendI18n = $this->cmsContent->frontendI18n($locale);
        $viewData = array_merge([
            'categories' => $this->localizedCategories($frontendI18n),
            'activeNav' => null,
            'locale' => $locale,
            'htmlLang' => $locale === 'zh' ? 'zh-CN' : 'en',
            'langLabel' => $locale === 'zh' ? 'ZH' : 'EN',
            'frontendI18n' => $frontendI18n,
            'copy' => $frontendI18n,
            'gaMeasurementId' => (string) env('GA_MEASUREMENT_ID', ''),
            'mapSetting' => $this->cmsContent->mapSetting($locale),
        ], $data);
        $this->localizeViewData($viewData, $frontendI18n);

        $viewData['pageTitle'] = $this->pageTitle((string) ($viewData['title'] ?? ''), $locale);

        return $render->render($template, $viewData);
    }

    protected function locale(): string
    {
        return $this->request->getAttribute('frontend_locale', 'en') === 'zh' ? 'zh' : 'en';
    }

    protected function categoryPath(string $slug): string
    {
        return match ($slug) {
            'news' => '/news',
            'service' => '/service',
            default => '/category/' . $slug,
        };
    }

    private function pageTitle(string $title, string $locale): string
    {
        if ($locale === 'zh') {
            return $title;
        }

        return [
            '首页' => 'Home',
            '关于我们' => 'About',
            '联系我们' => 'Contact',
            '页面不存在' => 'Page Not Found',
        ][$title] ?? $title;
    }

    private function localizedCategories(array $copy): Collection
    {
        $categories = $this->cmsContent->navCategories();
        foreach ($categories as $category) {
            $this->localizeCategory($category, $copy);
        }

        return $categories;
    }

    private function localizeViewData(array &$viewData, array $copy): void
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

        if (($viewData['article'] ?? null) instanceof CmsArticle) {
            $this->localizeArticleCategory($viewData['article'], $copy);
        }

        if (($viewData['articles'] ?? null) instanceof Collection) {
            foreach ($viewData['articles'] as $article) {
                if ($article instanceof CmsArticle) {
                    $this->localizeArticleCategory($article, $copy);
                }
            }
        }

        if (($viewData['paginator'] ?? null) instanceof LengthAwarePaginatorInterface) {
            foreach ($viewData['paginator']->items() as $article) {
                if ($article instanceof CmsArticle) {
                    $this->localizeArticleCategory($article, $copy);
                }
            }
        }
    }

    private function localizeArticleCategory(CmsArticle $article, array $copy): void
    {
        if ($article->relationLoaded('category') && $article->category instanceof CmsCategory) {
            $this->localizeCategory($article->category, $copy);
        }
    }

    private function localizeCategory(CmsCategory $category, array $copy): void
    {
        $key = match ($category->slug) {
            'news' => 'categoryNews',
            'service' => 'categoryService',
            default => null,
        };

        $category->frontend_path = $this->categoryPath($category->slug);

        if ($key === null) {
            return;
        }

        $category->name = $copy[$key] ?? $category->name;
        $category->description = $copy[$key . 'Description'] ?? $category->description;
    }
}
