<?php

declare(strict_types=1);

namespace App\Http\Shopline\Controller;

use App\Exception\BusinessException;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\ResultCode;
use App\Model\Shop;
use App\Service\Shopline\ShoplineIntegrationService;
use App\Service\Shopline\ShoplineOAuthService;
use App\Shopline\Exception\ShoplineSignatureException;
use App\Support\ShopSession;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Swagger\Annotation as OA;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * SHOPLINE 内嵌应用入口与 OAuth 回调。
 *
 * - App Bridge 授权：https://developer.shopline.com/docs/apps/development-tool/app-bridge/actions/authorization
 * - OAuth：https://developer.shopline.com/docs/apps/api-instructions-for-use/app-authorization
 */
#[OA\HyperfServer(name: 'http')]
final class OAuthController extends AbstractController
{
    public function __construct(
        private readonly ShoplineOAuthService $oauthService,
        private readonly ShoplineIntegrationService $integrationService,
    ) {}

    #[Get(
        path: '/shopline/app',
        operationId: 'shoplineEmbeddedApp',
        summary: 'SHOPLINE 内嵌应用入口（Partner Portal App URL）',
        tags: ['shopline:oauth'],
    )]
    public function app(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        return $this->embeddedEntry($request, $render);
    }

    #[Get(
        path: '/shopline/install',
        operationId: 'shoplineInstall',
        summary: 'SHOPLINE 应用安装入口（同内嵌 App URL）',
        tags: ['shopline:oauth'],
    )]
    public function install(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        return $this->embeddedEntry($request, $render);
    }

    #[Get(
        path: '/shopline/oauth/callback',
        operationId: 'shoplineOAuthCallback',
        summary: 'SHOPLINE OAuth 授权回调',
        tags: ['shopline:oauth'],
    )]
    public function callback(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        try {
            $result = $this->oauthService->handleOAuthCallback($request->all());
        } catch (ShoplineSignatureException $e) {
            throw new BusinessException(ResultCode::UNAUTHORIZED, $e->getMessage());
        }

        $shop = Shop::query()->whereKey($result['shop_id'])->first();
        if ($shop !== null) {
            $this->integrationService->ensureOrderCreatedWebhook($shop);
            $this->integrationService->syncShopTimezoneQuietly($shop);
            $shop = Shop::query()->whereKey($result['shop_id'])->first() ?? $shop;
        }

        return $this->withEmbedHeaders($render->render('shopline.embedded.callback-success', [
            'handle' => $result['handle'],
            'host' => $result['host'],
            'shopId' => $result['shop_id'],
            'appKey' => (string) config('shopline.app_key'),
            'shopSession' => $shop ? ShopSession::issue($shop) : '',
        ]));
    }

    private function embeddedEntry(RequestInterface $request, RenderInterface $render): ResponseInterface
    {
        try {
            $entry = $this->oauthService->resolveEmbeddedEntry($request->all());
        } catch (ShoplineSignatureException $e) {
            throw new BusinessException(ResultCode::UNAUTHORIZED, $e->getMessage());
        }

        $shop = null;
        $shopSession = '';
        $timezone = 'UTC';
        if ($entry['authorized'] && $entry['shop_id']) {
            $shop = Shop::query()->whereKey($entry['shop_id'])->first();
            if ($shop !== null) {
                $shopSession = ShopSession::issue($shop);
                $timezone = (string) ($shop->timezone ?: 'UTC');
            }
        }

        $view = $entry['authorized'] ? 'shopline.embedded.home' : 'shopline.embedded.authorize';

        return $this->withEmbedHeaders($render->render($view, [
            'authorized' => $entry['authorized'],
            'handle' => $entry['handle'],
            'host' => $entry['host'],
            'locale' => $entry['locale'],
            'appKey' => $entry['app_key'],
            'scopes' => $entry['scopes'],
            'redirectUri' => $entry['redirect_uri'],
            'shopId' => $entry['shop_id'],
            'timezone' => $timezone,
            'shopSession' => $shopSession,
        ]));
    }

    private function withEmbedHeaders(ResponseInterface $response): ResponseInterface
    {
        return $response
            ->withoutHeader('X-Frame-Options')
            ->withHeader(
                'Content-Security-Policy',
                "frame-ancestors https://*.myshopline.com https://admin.myshopline.com 'self';"
            );
    }
}
