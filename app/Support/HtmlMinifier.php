<?php

declare(strict_types=1);

namespace App\Support;

final class HtmlMinifier
{
    public static function minify(string $html): string
    {
        if ($html === '') {
            return $html;
        }

        $blocks = [];
        $html = preg_replace_callback(
            '#<(script|style|pre|textarea)\b[^>]*>.*?</\1>#is',
            static function (array $matches) use (&$blocks): string {
                $key = '%%HTML_MINIFY_BLOCK_' . count($blocks) . '%%';
                $blocks[$key] = $matches[0];

                return $key;
            },
            $html
        ) ?? $html;

        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html) ?? $html;
        $html = preg_replace('/>\s+</', '><', $html) ?? $html;
        $html = preg_replace('/\s{2,}/', ' ', $html) ?? $html;
        $html = trim($html);

        return strtr($html, $blocks);
    }
}
