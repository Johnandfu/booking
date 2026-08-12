<?php

declare(strict_types=1);

namespace App\Http\Storefront\Middleware;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Shop;
use App\Support\ShopSession;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Storefront 短时会话：Authorization Bearer / query token（复用 ShopSession HMAC）。
 */
final class StorefrontSessionMiddleware implements MiddlewareInterface
{
    public const ATTR_SHOP = 'booking.storefront.shop';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = '';
        $auth = $request->getHeaderLine('Authorization');
        if (str_starts_with($auth, 'Bearer ')) {
            $token = trim(substr($auth, 7));
        }
        if ($token === '') {
            $token = (string) ($request->getQueryParams()['token'] ?? '');
        }
        if ($token === '') {
            throw new BusinessException(ResultCode::UNAUTHORIZED, 'Storefront token required');
        }

        try {
            $payload = ShopSession::parse($token);
        } catch (\Throwable $e) {
            throw new BusinessException(ResultCode::UNAUTHORIZED, $e->getMessage());
        }

        $pathHandle = $this->pathHandle($request);
        if ($pathHandle !== '' && $pathHandle !== $payload['handle']) {
            throw new BusinessException(ResultCode::UNAUTHORIZED, 'Storefront handle mismatch');
        }

        $shop = Shop::query()
            ->whereKey($payload['shop_id'])
            ->where('handle', $payload['handle'])
            ->where('status', Shop::STATUS_INSTALLED)
            ->first();
        if ($shop === null) {
            throw new BusinessException(ResultCode::UNAUTHORIZED, 'Shop not found');
        }

        return $handler->handle($request->withAttribute(self::ATTR_SHOP, $shop));
    }

    private function pathHandle(ServerRequestInterface $request): string
    {
        $dispatched = $request->getAttribute(Dispatched::class);
        if ($dispatched instanceof Dispatched && is_array($dispatched->params ?? null)) {
            return (string) ($dispatched->params['handle'] ?? '');
        }

        return '';
    }
}
