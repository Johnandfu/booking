<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Factory\Log;
use App\Service\Cms\CmsContentService;
use Hyperf\Database\Model\ModelNotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

final class FrontendExceptionHandler extends ExceptionHandler
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly RenderInterface $render,
    ) {}

    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();

        $statusCode = $this->statusCode($throwable);
        $locale = (string) $this->request->getAttribute('frontend_locale', CmsContentService::DEFAULT_FRONTEND_LOCALE);
        Log::error('error', ['throwable' => $throwable]);
        return $this->render->render('index.error', [
            'activeNav' => null,
            'locale' => $locale,
            'htmlLang' => $locale,
            'frontendLocales' => CmsContentService::FRONTEND_LOCALES,
            'frontendCategories' => [],
            'copy' => [],
            'seoTitle' => 'Error',
            'seoDescription' => 'Something went wrong.',
            'seoUrl' => '',
            'errorCode' => $statusCode,
        ])->withStatus($statusCode);
    }

    public function isValid(Throwable $throwable): bool
    {
        $path = '/' . ltrim($this->request->getUri()->getPath(), '/');

        return ! str_starts_with($path, '/admin')
            && ! str_starts_with($path, '/api');
    }

    private function statusCode(Throwable $throwable): int
    {
        if ($throwable instanceof HttpException) {
            return $throwable->getStatusCode();
        }

        if ($throwable instanceof ModelNotFoundException) {
            return 404;
        }

        return 500;
    }
}
