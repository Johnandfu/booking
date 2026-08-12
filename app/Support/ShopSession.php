<?php

declare(strict_types=1);

namespace App\Support;

use App\Model\Shop;
use RuntimeException;

/**
 * 内嵌应用店铺会话（HMAC），供 /shopline/api/* 鉴权。
 */
final class ShopSession
{
    public static function issue(Shop $shop, int $ttlSeconds = 86400): string
    {
        $payload = [
            'shop_id' => (int) $shop->id,
            'handle' => (string) $shop->handle,
            'exp' => time() + $ttlSeconds,
        ];
        $body = self::base64UrlEncode(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}');
        $sign = hash_hmac('sha256', $body, self::secret());

        return $body . '.' . $sign;
    }

    /**
     * @return array{shop_id:int,handle:string,exp:int}
     */
    public static function parse(string $token): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            throw new RuntimeException('Invalid shop session');
        }
        [$body, $sign] = $parts;
        $expected = hash_hmac('sha256', $body, self::secret());
        if (! hash_equals($expected, $sign)) {
            throw new RuntimeException('Invalid shop session signature');
        }
        $json = json_decode(self::base64UrlDecode($body), true);
        if (! is_array($json) || empty($json['shop_id']) || empty($json['handle']) || empty($json['exp'])) {
            throw new RuntimeException('Invalid shop session payload');
        }
        if ((int) $json['exp'] < time()) {
            throw new RuntimeException('Shop session expired');
        }

        return [
            'shop_id' => (int) $json['shop_id'],
            'handle' => (string) $json['handle'],
            'exp' => (int) $json['exp'],
        ];
    }

    private static function secret(): string
    {
        $secret = (string) config('shopline.app_secret');
        if ($secret === '') {
            $secret = (string) config('jwt.secret', 'booking-shop-session');
        }

        return $secret;
    }

    private static function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $value): string
    {
        $remainder = strlen($value) % 4;
        if ($remainder > 0) {
            $value .= str_repeat('=', 4 - $remainder);
        }

        return (string) base64_decode(strtr($value, '-_', '+/'), true);
    }
}
