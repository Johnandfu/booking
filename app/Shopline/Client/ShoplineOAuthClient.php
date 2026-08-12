<?php

declare(strict_types=1);

namespace App\Shopline\Client;

use App\Shopline\Dto\AccessTokenResult;
use App\Shopline\Exception\ShoplineApiException;
use App\Shopline\ShoplineSignature;
use GuzzleHttp\ClientInterface;
use Hyperf\Guzzle\ClientFactory;

/**
 * SHOPLINE OAuth HTTP Adapter（仅封装官方已文档化的端点）。
 *
 * 文档：https://developer.shopline.com/docs/apps/api-instructions-for-use/app-authorization
 * - POST /admin/oauth/token/create
 * - POST /admin/oauth/token/refresh
 */
final class ShoplineOAuthClient
{
    private ClientInterface $http;

    public function __construct(
        private readonly ShoplineSignature $signature,
        ClientFactory $clientFactory,
    ) {
        $this->http = $clientFactory->create([
            'timeout' => (float) config('shopline.http_timeout', 15),
            'http_errors' => false,
        ]);
    }

    public function createAccessToken(string $handle, string $code): AccessTokenResult
    {
        $body = json_encode(['code' => $code], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($body === false) {
            throw new ShoplineApiException('Failed to encode OAuth create token body');
        }

        return $this->postToken($handle, '/admin/oauth/token/create', $body);
    }

    public function refreshAccessToken(string $handle): AccessTokenResult
    {
        return $this->postToken($handle, '/admin/oauth/token/refresh', '{}');
    }

    public function buildAuthorizeUrl(string $handle, string $redirectUri, ?string $customField = null): string
    {
        $query = [
            'appKey' => (string) config('shopline.app_key'),
            'responseType' => 'code',
            'scope' => (string) config('shopline.scopes'),
            'redirectUri' => $redirectUri,
        ];
        if ($customField !== null && $customField !== '') {
            $query['customField'] = $customField;
        }

        return sprintf(
            'https://%s.myshopline.com/admin/oauth-web/#/oauth/authorize?%s',
            $handle,
            http_build_query($query)
        );
    }

    private function postToken(string $handle, string $path, string $body): AccessTokenResult
    {
        $appKey = (string) config('shopline.app_key');
        $appSecret = (string) config('shopline.app_secret');
        $timestamp = (string) (int) floor(microtime(true) * 1000);
        $sign = $this->signature->signPost($body, $timestamp, $appSecret);

        $url = sprintf('https://%s.myshopline.com%s', $handle, $path);
        $response = $this->http->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'appkey' => $appKey,
                'timestamp' => $timestamp,
                'sign' => $sign,
            ],
            'body' => $body,
        ]);

        $raw = (string) $response->getBody();
        $json = json_decode($raw, true);
        if (! is_array($json)) {
            throw new ShoplineApiException('Invalid SHOPLINE OAuth response', payload: $raw);
        }

        $code = (int) ($json['code'] ?? 0);
        if ($code !== 200) {
            throw new ShoplineApiException(
                (string) ($json['message'] ?? 'SHOPLINE OAuth request failed'),
                i18nCode: isset($json['i18nCode']) ? (string) $json['i18nCode'] : null,
                payload: $json,
                code: $code,
            );
        }

        $data = $json['data'] ?? null;
        if (! is_array($data) || empty($data['accessToken']) || empty($data['expireTime'])) {
            throw new ShoplineApiException('SHOPLINE OAuth response missing token data', payload: $json);
        }

        return new AccessTokenResult(
            accessToken: (string) $data['accessToken'],
            expireTime: (string) $data['expireTime'],
            scope: (string) ($data['scope'] ?? ''),
        );
    }
}
