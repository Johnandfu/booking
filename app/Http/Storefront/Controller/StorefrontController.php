<?php

declare(strict_types=1);

namespace App\Http\Storefront\Controller;

use App\Exception\BusinessException;
use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\Common\ResultCode;
use App\Http\Storefront\Middleware\StorefrontSessionMiddleware;
use App\Model\Booking\BookingService;
use App\Model\Booking\BookingLocation;
use App\Model\Shop;
use App\Service\Booking\AvailabilityService;
use App\Service\Booking\BookingFlowService;
use App\Support\ShopSession;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Swagger\Annotation as OA;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\View\RenderInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * 店面选时：公开 picker 页 + 带短时 token 的 API。
 * Checkout Extension 尚未接入；预约创建为 pending，由 orders/create Webhook 确认。
 */
#[OA\HyperfServer(name: 'http')]
final class StorefrontController extends AbstractController
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly AvailabilityService $availabilityService,
        private readonly BookingFlowService $bookingFlowService,
    ) {}

    #[Get(
        path: '/storefront/{handle}/picker',
        operationId: 'storefrontPicker',
        summary: '店面日期时间选择页',
        tags: ['storefront'],
    )]
    public function picker(string $handle, RenderInterface $render): ResponseInterface
    {
        $shop = $this->findInstalledShop($handle);
        $token = ShopSession::issue($shop, 3600);

        return $render->render('storefront.picker', [
            'handle' => $shop->handle,
            'timezone' => (string) ($shop->timezone ?: 'UTC'),
            'token' => $token,
            'shopId' => (int) $shop->id,
        ]);
    }

    #[Get(
        path: '/storefront/api/{handle}/services',
        operationId: 'storefrontServices',
        summary: '店面服务列表',
        tags: ['storefront'],
    )]
    #[Middleware(middleware: StorefrontSessionMiddleware::class)]
    public function services(string $handle): Result
    {
        $shop = $this->shop();
        $list = BookingService::query()
            ->where('shop_id', $shop->id)
            ->where('status', 1)
            ->orderBy('sort')
            ->orderBy('id')
            ->get(['id', 'name', 'description', 'duration_minutes', 'capacity']);

        return $this->success(['list' => $list, 'timezone' => $shop->timezone]);
    }

    #[Get(
        path: '/storefront/api/{handle}/locations',
        operationId: 'storefrontLocations',
        summary: '店面门店列表',
        tags: ['storefront'],
    )]
    #[Middleware(middleware: StorefrontSessionMiddleware::class)]
    public function locations(string $handle): Result
    {
        $shop = $this->shop();
        $list = BookingLocation::query()
            ->where('shop_id', $shop->id)
            ->where('status', 1)
            ->orderBy('sort')
            ->orderBy('id')
            ->get(['id', 'name', 'address']);

        return $this->success(['list' => $list]);
    }

    #[Get(
        path: '/storefront/api/{handle}/availability',
        operationId: 'storefrontAvailability',
        summary: '店面可预约时段',
        tags: ['storefront'],
    )]
    #[Middleware(middleware: StorefrontSessionMiddleware::class)]
    public function availability(string $handle): Result
    {
        $shop = $this->shop();
        $slots = $this->availabilityService->listAvailableSlots(
            $shop,
            (int) $this->request->input('service_id'),
            (int) $this->request->input('location_id'),
            (string) $this->request->input('date'),
            $this->request->has('staff_id') ? (int) $this->request->input('staff_id') : null,
        );

        return $this->success(['list' => $slots, 'timezone' => $shop->timezone]);
    }

    #[Post(
        path: '/storefront/api/{handle}/slot-locks',
        operationId: 'storefrontLockSlot',
        summary: '店面锁定 Slot',
        tags: ['storefront'],
    )]
    #[Middleware(middleware: StorefrontSessionMiddleware::class)]
    public function lockSlot(string $handle): Result
    {
        $data = $this->request->all();

        return $this->success($this->bookingFlowService->lockSlot(
            $this->shop(),
            (int) ($data['slot_id'] ?? 0),
            (string) ($data['customer_key'] ?? ''),
        ));
    }

    #[Post(
        path: '/storefront/api/{handle}/bookings',
        operationId: 'storefrontCreateBooking',
        summary: '店面创建 pending 预约（待订单 Webhook 确认）',
        tags: ['storefront'],
    )]
    #[Middleware(middleware: StorefrontSessionMiddleware::class)]
    public function createBooking(string $handle): Result
    {
        $payload = $this->request->all();
        $payload['confirm'] = false;

        return $this->success($this->bookingFlowService->createBooking($this->shop(), $payload));
    }

    private function shop(): Shop
    {
        $shop = $this->request->getAttribute(StorefrontSessionMiddleware::ATTR_SHOP);
        if (! $shop instanceof Shop) {
            throw new BusinessException(ResultCode::UNAUTHORIZED, 'Shop session missing');
        }

        return $shop;
    }

    private function findInstalledShop(string $handle): Shop
    {
        $shop = Shop::query()
            ->where('handle', $handle)
            ->where('status', Shop::STATUS_INSTALLED)
            ->first();
        if ($shop === null) {
            throw new BusinessException(ResultCode::NOT_FOUND, 'Shop not found');
        }

        return $shop;
    }
}
