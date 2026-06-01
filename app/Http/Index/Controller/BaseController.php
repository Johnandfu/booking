<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Service\Cms\CmsContentService;
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
        $locale = $this->request->getAttribute('frontend_locale', 'en');
        $copy = $this->cmsContent->frontendI18n($locale);
        $viewData = array_merge($this->sharedViewData($locale, $copy), $data);

        $this->cmsContent->localizeFrontendViewData($viewData, $copy);
        $viewData['pageTitle'] = $this->pageTitle((string) ($viewData['title'] ?? ''), $locale);

        return $render->render($template, $viewData);
    }

    protected function notFound(RenderInterface $render): ResponseInterface
    {
        $locale = $this->request->getAttribute('frontend_locale', 'en');
        $copy = $this->cmsContent->frontendI18n($locale);

        return $this->view($render, 'index.not-found', [
            'title' => $copy['not-found-text'] ?? 'not-found-text',
            'activeNav' => null,
        ]);
    }



    private function sharedViewData(string $locale, array $copy): array
    {
        return [
            'categories' => $this->cmsContent->localizedNavCategories($copy),
            'activeNav' => null,
            'locale' => $locale,
            'htmlLang' => $locale,
            'frontendLocales' => array_keys(CmsContentService::frontendLocales()),
            'frontendI18n' => $copy,
            'copy' => $copy,
            'gaMeasurementId' => (string) env('GA_MEASUREMENT_ID', ''),

        ];
    }

    private function pageTitle(string $title, string $locale): string
    {


        return [
            '首页' => 'Home',
            '关于我们' => 'About',
            '联系我们' => 'Contact',
        ][$title] ?? $title;
    }
}
