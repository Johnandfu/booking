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

use Hyperf\Contract\TranslatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TranslationMiddleware implements MiddlewareInterface
{
    private const SUPPORTED_LOCALES = ['en', 'zh_CN', 'zh_TW'];

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->translator->setLocale($this->getLocale($request));

        return $handler->handle($request);
    }

    protected function getLocale(ServerRequestInterface $request): string
    {
        if ($request->hasHeader('Accept-Language')) {
            return $this->normalizeLocale($request->getHeaderLine('Accept-Language'));
        }

        return (string) config('translation.locale', 'en');
    }

    protected function normalizeLocale(string $locale): string
    {
        $primary = str_replace('-', '_', explode(',', $locale)[0]);
        $primary = explode(';', $primary)[0];
        $primary = trim($primary);

        if (in_array($primary, self::SUPPORTED_LOCALES, true)) {
            return $primary;
        }

        $short = explode('_', $primary)[0];
        foreach (self::SUPPORTED_LOCALES as $supported) {
            if (str_starts_with($supported, $short)) {
                return $supported;
            }
        }

        return (string) config('translation.locale', 'en');
    }
}
