<?php

declare(strict_types=1);

namespace App\Http\Index\Controller;

use App\Model\Cms\CmsLanguage;
use App\Service\Cms\CmsContentService;
use App\Service\Cms\CmsLanguageService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseController
{
    #[Inject]
    protected CmsLanguageService $cmsLangcode;
    #[Inject]
    protected RequestInterface $request;

    protected function view(RenderInterface $render, string $template, array $data = []): ResponseInterface
    {
        $locale = $this->request->getAttribute('frontend_locale', 'en');
        $copy = $this->cmsLangcode->copy([
            'language_code' => $locale,
            'status' => CmsLanguage::STATUS_ENABLED,
        ]);

        $viewData = array_merge($this->sharedViewData($locale, $copy), $data);
        $viewData['pageTitle'] = $this->pageTitle((string) ($viewData['title'] ?? ''));

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
            'copy' => $copy,
            'gaMeasurementId' => (string) env('GA_MEASUREMENT_ID', ''),
        ];
    }

    private function pageTitle(string $title): string
    {
        return [
            '首页' => 'Home',
            '关于我们' => 'About',
            '联系我们' => 'Contact',
        ][$title] ?? $title;
    }
}
