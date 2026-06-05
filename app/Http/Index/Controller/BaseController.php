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
        $viewData['seoTitle'] = $viewData['pageTitleOri'] ?? (data_get($copy, $data['pageTitle'] ?? 'main-title') ??  null);
        $viewData['seoDescription'] = $viewData['pageDescriptionOri'] ?? (data_get($copy, $data['pageDescription'] ?? 'main-description') ??  null);


        return $render->render($template, $viewData);
    }

    protected function notFound(RenderInterface $render): ResponseInterface
    {
        return $this->view($render, 'index.error', [
            'pageTitle' => 'error-title',
            'pageDescription' => 'error-text',
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
}
