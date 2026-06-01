<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Http\Common\Middleware;

use App\Service\Cms\CmsContentService;
use Hyperf\Contract\TranslatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TranslationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $locale = $this->getLocale($request);
        $this->translator->setLocale($locale);

        return $handler->handle(
            $request->withAttribute('frontend_locale', $locale)
        );
    }

    protected function getLocale(ServerRequestInterface $request): string
    {
        $cookieLang = (string) ($request->getCookieParams()['frontend_lang'] ?? '');
        if ($cookieLang !== '') {
            return $this->normalizeLocale($cookieLang);
        }

        if ($request->hasHeader('Accept-Language')) {
            return $this->normalizeLocale($request->getHeaderLine('Accept-Language'));
        }

        return CmsContentService::frontendDefaultLocale();
    }

    protected function normalizeLocale(string $locale): string
    {
        $locale = strtolower(str_replace('_', '-', $locale));

        foreach (CmsContentService::frontendLocales() as $frontendLocale => $config) {
            foreach ($config['aliases'] ?? [$frontendLocale] as $alias) {
                if (str_starts_with($locale, strtolower((string) $alias))) {
                    return $frontendLocale;
                }
            }
        }

        return CmsContentService::frontendDefaultLocale();
    }
}
