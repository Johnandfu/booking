<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

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
        $viewData['seoTitle'] = $this->seoText((string) ($viewData['title'] ?? ''), $copy, 'main-title');
        $viewData['seoDescription'] = $this->seoText((string) ($viewData['description'] ?? ''), $copy, 'main-description');
        $viewData['seoUrl'] = $this->seoUrl();
        $viewData['seoImage'] = (string) ($viewData['seoImage'] ?? '');
        if ($viewData['seoImage'] === '' && ! empty($viewData['article']->cover)) {
            $viewData['seoImage'] = (string) $viewData['article']->cover;
        }

        return $render->render($template, $viewData);
    }

    protected function notFound(RenderInterface $render): ResponseInterface
    {
        return $this->view($render, 'index.not-found', [
            'title' => 'not-found-text',
            'activeNav' => null,
        ]);
    }

    private function sharedViewData(string $locale, array $copy): array
    {
        return [
            'activeNav' => null,
            'locale' => $locale,
            'htmlLang' => $locale,
            'frontendLocales' => array_keys(CmsContentService::frontendLocales()),
            'frontendCategories' => $this->cmsCategory->enabledCategories(),
            'copy' => $copy,
            'gaMeasurementId' => (string) env('GA_MEASUREMENT_ID', ''),
        ];
    }

    private function seoText(string $value, array $copy, string $defaultKey): string
    {
        if ($value !== '') {
            return $copy[$value] ?? $value;
        }

        return $copy[$defaultKey] ?? $defaultKey;
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
