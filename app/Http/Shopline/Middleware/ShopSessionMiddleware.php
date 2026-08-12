<?php

declare(strict_types=1);

namespace App\Http\Shopline\Middleware;

use App\Exception\BusinessException;
use App\Http\Common\ResultCode;
use App\Model\Shop;
use App\Support\ShopSession;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 校验内嵌会话 Cookie / Authorization Bearer shop session。
 */
final class ShopSessionMiddleware implements MiddlewareInterface
{
    public const ATTR_SHOP = 'booking.shop';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = (string) ($request->getCookieParams()['booking_shop_session'] ?? '');
        if ($token === '') {
            $auth = $request->getHeaderLine('Authorization');
            if (str_starts_with($auth, 'Bearer ')) {
                $token = trim(substr($auth, 7));
            }
        }
        if ($token === '') {
            throw new BusinessException(ResultCode::UNAUTHORIZED, 'Shop session required');
        }

        try {
            $payload = ShopSession::parse($token);
        } catch (\Throwable $e) {
            throw new BusinessException(ResultCode::UNAUTHORIZED, $e->getMessage());
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
}
