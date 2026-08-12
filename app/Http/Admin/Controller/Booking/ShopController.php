<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Booking;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Repository\ShopRepository;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Mine\Access\Attribute\Permission;
use Mine\Swagger\Attributes\ResultResponse;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
final class ShopController extends AbstractController
{
    public function __construct(
        private readonly ShopRepository $shopRepository,
    ) {}

    #[Get(
        path: '/admin/booking/shop/list',
        operationId: 'bookingShopList',
        summary: '店铺列表',
        security: [['Bearer' => [], 'ApiKey' => []]],
        tags: ['booking'],
    )]
    #[Permission(code: 'booking:shop:list')]
    #[ResultResponse(instance: new Result())]
    public function list(): Result
    {
        return $this->success(
            $this->shopRepository->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize())
        );
    }
}
