<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Booking;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Model\Shop;
use App\Service\Booking\BookingAdminService;
use App\Service\Booking\BookingFlowService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use Mine\Access\Attribute\Permission;
use Mine\Swagger\Attributes\ResultResponse;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
final class BookingController extends AbstractController
{
    public function __construct(
        private readonly BookingAdminService $service,
        private readonly BookingFlowService $bookingFlowService,
    ) {}

    #[Get(
        path: '/admin/booking/list',
        operationId: 'bookingList',
        summary: '预约列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['booking'],
    )]
    #[Permission(code: 'booking:booking:list')]
    #[ResultResponse(instance: new Result())]
    public function list(): Result
    {
        return $this->success(
            $this->service->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize())
        );
    }

    #[Get(
        path: '/admin/booking/calendar',
        operationId: 'bookingCalendar',
        summary: '预约日历数据',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['booking'],
    )]
    #[Permission(code: 'booking:calendar:list')]
    #[ResultResponse(instance: new Result())]
    public function calendar(): Result
    {
        $params = $this->getRequestData();
        $list = $this->service->getList($params)->sortBy('start_at')->values();

        return $this->success(['list' => $list]);
    }

    #[Post(
        path: '/admin/booking/{id}/cancel',
        operationId: 'bookingCancel',
        summary: '取消预约',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['booking'],
    )]
    #[Permission(code: 'booking:booking:cancel')]
    #[ResultResponse(instance: new Result())]
    public function cancel(int $id): Result
    {
        $params = $this->getRequestData();
        $shopId = (int) ($params['shop_id'] ?? 0);
        $shop = Shop::query()->whereKey($shopId)->first();
        if ($shop === null) {
            return $this->error('Shop not found');
        }

        return $this->success($this->bookingFlowService->cancelBooking($shop, $id));
    }
}
