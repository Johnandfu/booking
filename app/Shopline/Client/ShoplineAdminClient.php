<?php

declare(strict_types=1);

namespace App\Shopline\Client;

use App\Shopline\Exception\ShoplineApiException;
use GuzzleHttp\ClientInterface;
use Hyperf\Guzzle\ClientFactory;

/**
 * SHOPLINE Admin OpenAPI Adapter（仅封装已查阅官方文档的端点）。
 *
 * - Subscribe Webhook: POST /webhooks.json
 * - Query store: GET /merchants/shop.json
 * - Create product: POST /products/products.json
 */
final class ShoplineAdminClient
{
    private ClientInterface $http;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->http = $clientFactory->create([
            'timeout' => (float) config('shopline.http_timeout', 15),
            'http_errors' => false,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function subscribeWebhook(
        string $handle,
        string $accessToken,
        string $topic,
        string $address,
        ?string $apiVersion = null,
    ): array {
        $version = $apiVersion ?: (string) config('shopline.webhook_api_version', 'v20240601');

        return $this->request($handle, $accessToken, 'POST', '/webhooks.json', [
            'webhook' => [
                'address' => $address,
                'api_version' => $version,
                'topic' => $topic,
            ],
        ]);
    }

    /**
     * Query store information.
     * https://developer.shopline.com/docs/admin-rest-api/store/query-store-information
     *
     * @return array<string, mixed>
     */
    public function getShop(string $handle, string $accessToken): array
    {
        $json = $this->request($handle, $accessToken, 'GET', '/merchants/shop.json');
        $data = $json['data'] ?? null;
        if (! is_array($data)) {
            throw new ShoplineApiException('Shop info response missing data', payload: $json);
        }

        return $data;
    }

    /**
     * Create a product.
     * https://developer.shopline.com/docs/admin-rest-api/product/product/create-a-product
     *
     * @param array<string, mixed> $product
     * @return array<string, mixed>
     */
    public function createProduct(string $handle, string $accessToken, array $product): array
    {
        $json = $this->request($handle, $accessToken, 'POST', '/products/products.json', [
            'product' => $product,
        ]);
        $created = $json['product'] ?? null;
        if (! is_array($created) || empty($created['id'])) {
            throw new ShoplineApiException('Create product response missing product.id', payload: $json);
        }

        return $created;
    }

    /**
     * @param null|array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function request(
        string $handle,
        string $accessToken,
        string $method,
        string $path,
        ?array $payload = null,
    ): array {
        $url = sprintf(
            'https://%s.myshopline.com/admin/openapi/%s%s',
            $handle,
            ltrim((string) config('shopline.api_version', 'v20260601'), '/'),
            $path
        );

        $options = [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ];
        if ($payload !== null) {
            $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($body === false) {
                throw new ShoplineApiException('Failed to encode Admin API body');
            }
            $options['body'] = $body;
        }

        $response = $this->http->request($method, $url, $options);
        $raw = (string) $response->getBody();
        $status = $response->getStatusCode();
        $json = json_decode($raw, true);
        if (! is_array($json)) {
            throw new ShoplineApiException('Invalid Admin API response', payload: $raw, code: $status);
        }

        if ($status >= 400 || isset($json['errors'])) {
            throw new ShoplineApiException(
                is_string($json['errors'] ?? null) ? (string) $json['errors'] : 'Admin API request failed',
                payload: $json,
                code: $status,
            );
        }

        return $json;
    }
}
