<?php

declare(strict_types=1);

namespace App\Shopline;

/**
 * SHOPLINE HMAC-SHA256 签名。
 *
 * 文档：https://developer.shopline.com/docs/apps/api-instructions-for-use/generate-and-verify-signatures
 */
final class ShoplineSignature
{
    public function hmacSha256(string $source, string $secret): string
    {
        return hash_hmac('sha256', $source, $secret);
    }

    /**
     * 校验 GET 回调/安装请求签名。
     *
     * @param array<string, mixed> $query
     */
    public function verifyGet(array $query, string $secret, string $receivedSign): bool
    {
        if ($receivedSign === '' || $secret === '') {
            return false;
        }

        $expected = $this->hmacSha256($this->buildGetSource($query), $secret);

        return hash_equals($expected, $receivedSign);
    }

    /**
     * POST 请求签名：source = body + timestamp(ms)。
     */
    public function signPost(string $body, string $timestampMs, string $secret): string
    {
        return $this->hmacSha256($body . $timestampMs, $secret);
    }

    /**
     * @param array<string, mixed> $query
     */
    public function buildGetSource(array $query): string
    {
        unset($query['sign']);
        ksort($query);

        $parts = [];
        foreach ($query as $key => $value) {
            if (is_array($value)) {
                $value = $value[0] ?? '';
            }
            $parts[] = $key . '=' . (string) $value;
        }

        return implode('&', $parts);
    }

    public function isTimestampFresh(string|int $timestampMs, int $maxSkewSeconds = 600): bool
    {
        $ts = (int) $timestampMs;
        if ($ts <= 0) {
            return false;
        }

        // SHOPLINE 使用毫秒时间戳
        if ($ts > 10_000_000_000) {
            $ts = (int) floor($ts / 1000);
        }

        return abs(time() - $ts) <= $maxSkewSeconds;
    }

    /**
     * 校验 Webhook：HMAC-SHA256(request body, app secret)。
     *
     * 文档：https://developer.shopline.com/docs/apps/api-instructions-for-use/webhooks/overview
     * 签名算法：https://developer.shopline.com/docs/apps/api-instructions-for-use/generate-and-verify-signatures
     *
     * Header: X-Shopline-Hmac-Sha256
     * 官方示例同时出现 hex 与 base64 形态，二者均按同一 HMAC 结果比对。
     */
    public function verifyWebhook(string $rawBody, string $receivedHmac, string $secret): bool
    {
        if ($receivedHmac === '' || $secret === '') {
            return false;
        }

        $raw = hash_hmac('sha256', $rawBody, $secret, true);
        $hex = bin2hex($raw);
        $b64 = base64_encode($raw);

        return hash_equals($hex, $receivedHmac)
            || hash_equals($b64, $receivedHmac)
            || hash_equals(strtolower($hex), strtolower($receivedHmac));
    }
}
