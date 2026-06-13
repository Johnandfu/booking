<?php

declare(strict_types=1);

namespace App\Support;

final class Slugifier
{
    public static function toSlug(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        $value = mb_strtolower($value, 'UTF-8');
        $value = preg_replace('/[\s_]+/u', '-', $value) ?? '';
        $value = preg_replace('/[^\p{L}\p{N}-]+/u', '', $value) ?? '';
        $value = preg_replace('/-+/', '-', $value) ?? '';

        return trim($value, '-');
    }

    /**
     * @param callable(string, int): bool $exists
     */
    public static function unique(string $source, int $id, callable $exists): string
    {
        $slug = self::toSlug($source);
        if ($slug === '') {
            $slug = (string) $id;
        }

        if (! $exists($slug, $id)) {
            return $slug;
        }

        $suffix = '-' . $id;
        $maxBaseLength = 200 - strlen($suffix);
        if ($maxBaseLength <= 0) {
            return (string) $id;
        }

        if (strlen($slug) > $maxBaseLength) {
            $slug = rtrim(substr($slug, 0, $maxBaseLength), '-');
        }

        return $slug . $suffix;
    }
}
