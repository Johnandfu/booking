<?php

declare(strict_types=1);

namespace App\Support;

final class AdminUrl
{
    public static function resolve(): string
    {
        $domain = trim((string) env('ADMIN_DOMAIN', ''));
        if ($domain === '') {
            return '/admin';
        }

        return rtrim(self::scheme() . '://' . $domain, '/') . '/';
    }

    public static function domain(): string
    {
        return trim((string) env('ADMIN_DOMAIN', ''));
    }

    private static function scheme(): string
    {
        if (filter_var(env('ENABLE_SSL', false), FILTER_VALIDATE_BOOLEAN)) {
            return 'https';
        }

        $scheme = parse_url((string) env('APP_URL', ''), PHP_URL_SCHEME);

        return is_string($scheme) && $scheme !== '' ? $scheme : 'http';
    }
}
