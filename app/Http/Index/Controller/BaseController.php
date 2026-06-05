<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Model\Cms\CmsCategory;
use App\Model\Cms\CmsLanguage;
use App\Service\Cms\CmsCategoryService;
use App\Service\Cms\CmsContentService;
use App\Service\Cms\CmsLanguageService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseController
{
    public function __construct(
        protected readonly CmsLanguageService $cmsLangcode,
        protected readonly CmsCategoryService $cmsCategory,
        protected readonly RequestInterface $request,
    ) {}

    protected function view(RenderInterface $render, string $template, array $data = []): ResponseInterface
    {
        $locale = $this->request->getAttribute('frontend_locale', 'en');
        $copy = $this->cmsLangcode->copy([
            'language_code' => $locale,
            'status' => CmsLanguage::STATUS_ENABLED,
        ]);

        $viewData = array_merge($this->sharedViewData($locale, $copy), $data);
        $viewData['pageTitle'] = $viewData['pageTitle'] ?? 'Home';
        $viewData['seoTitle'] = $this->seoText(
            (string) ($viewData['title'] ?? ''),
            $copy,
            'main-title',
            (string) ($viewData['titleFallback'] ?? '')
        );
        $viewData['seoDescription'] = $this->seoText(
            (string) ($viewData['description'] ?? ''),
            $copy,
            'main-description',
            (string) ($viewData['descriptionFallback'] ?? '')
        );
        if (($viewData['category'] ?? null) instanceof CmsCategory) {
            $viewData['categoryCopy'] = $this->categoryCopy($viewData['category'], $copy);
        }
        $viewData['seoUrl'] = $this->seoUrl();

        return $render->render($template, $viewData);
    }

    protected function notFound(RenderInterface $render): ResponseInterface
    {
        return $this->view($render, 'index.error', [
            'title' => 'error-title',
            'description' => 'error-text',
            'activeNav' => null,
            'errorCode' => 404,
            'errorMessage' => null,
        ])->withStatus(404);
    }

    private function sharedViewData(string $locale, array $copy): array
    {
        return [
            'activeNav' => null,
            'locale' => $locale,
            'htmlLang' => $locale,
            'frontendLocales' => CmsContentService::FRONTEND_LOCALES,
            'frontendCategories' => $this->cmsCategory->enabledCategories(),
            'copy' => $copy
        ];
    }

    private function seoText(string $value, array $copy, string $defaultKey, string $fallback = ''): string
    {
        if ($value !== '') {
            return $copy[$value] ?? ($fallback !== '' ? $fallback : $value);
        }

        return $copy[$defaultKey] ?? $defaultKey;
    }

    private function categoryCopy(CmsCategory $category, array $copy): array
    {
        $slug = (string) $category->slug;

        return [
            'cardEyebrow' => $this->copyText($copy, $slug, 'cardEyebrow', 'category-serviceCardEyebrow'),
            'defaultSummary' => $this->copyText($copy, $slug, 'defaultSummary', 'category-serviceDefaultSummary'),
            'description' => $this->copyText($copy, $slug, 'description', (string) ($category->description ?? $category->name)),
            'empty' => $this->copyText($copy, $slug, 'empty', 'category-empty'),
            'featuredKicker' => $this->copyText($copy, $slug, 'featuredKicker', 'category-serviceFeaturedKicker'),
            'featuredTitle' => $this->copyText($copy, $slug, 'featuredTitle', 'category-serviceFeaturedTitle'),
            'filterBudgetLabel' => $this->copyText($copy, $slug, 'filterBudgetLabel', 'category-serviceFilterBudgetLabel'),
            'filterLocationLabel' => $this->copyText($copy, $slug, 'filterLocationLabel', 'category-newsFilterLocationLabel'),
            'gridKicker' => $this->copyText($copy, $slug, 'gridKicker', 'category-serviceGridKicker'),
            'gridTitle' => $this->copyText($copy, $slug, 'gridTitle', 'category-serviceGridTitle'),
            'heroKicker' => $this->copyText($copy, $slug, 'heroKicker', 'category-serviceHeroKicker'),
            'heroText' => $this->copyText($copy, $slug, 'heroText', 'category-serviceHeroText'),
            'heroTitle' => $this->copyText($copy, $slug, 'heroTitle', 'category-serviceHeroTitle'),
            'kicker' => $this->copyText($copy, $slug, 'kicker', 'category-kicker'),
            'listingBeachfront' => $this->copyText($copy, $slug, 'listingBeachfront', 'category-listingBeachfront'),
            'listingCityCoastal' => $this->copyText($copy, $slug, 'listingCityCoastal', 'category-listingCityCoastal'),
            'listingLocation' => $this->copyText($copy, $slug, 'listingLocation', 'category-listingLocation'),
            'listingRiverView' => $this->copyText($copy, $slug, 'listingRiverView', 'category-listingRiverView'),
            'title' => $this->copyText($copy, $slug, 'title', (string) $category->name),
            'viewDetails' => $this->copyText($copy, $slug, 'viewDetails', 'category-viewDetails'),
        ];
    }

    private function copyText(array $copy, string $slug, string $key, string $fallback): string
    {
        return $copy[$slug . '-' . $key] ?? $copy[$fallback] ?? $fallback;
    }

    private function seoUrl(): string
    {
        $uri = $this->request->getUri();
        $scheme = (string) ($this->request->header('x-forwarded-proto') ?: $uri->getScheme() ?: 'http');
        $host = (string) ($this->request->header('host') ?: $uri->getHost());
        $query = $uri->getQuery();

        return $scheme . '://' . $host . $uri->getPath() . ($query === '' ? '' : '?' . $query);
    }
}
