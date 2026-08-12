<?php

declare(strict_types=1);

namespace App\Http\Admin\Controller\Booking;

use App\Http\Admin\Controller\AbstractController;
use App\Http\Admin\Middleware\PermissionMiddleware;
use App\Http\Common\Middleware\AccessTokenMiddleware;
use App\Http\Common\Middleware\OperationMiddleware;
use App\Http\Common\Result;
use App\Model\Booking\BookingLocation;
use App\Model\Booking\BookingSchedule;
use App\Model\Booking\BookingService;
use App\Model\Booking\BookingStaff;
use App\Service\Booking\BookingLocationAdminService;
use App\Service\Booking\BookingScheduleAdminService;
use App\Service\Booking\BookingServiceAdminService;
use App\Service\Booking\BookingStaffAdminService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\HyperfServer;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;
use Mine\Access\Attribute\Permission;
use Mine\Swagger\Attributes\ResultResponse;

#[HyperfServer(name: 'http')]
#[Middleware(middleware: AccessTokenMiddleware::class, priority: 100)]
#[Middleware(middleware: PermissionMiddleware::class, priority: 99)]
#[Middleware(middleware: OperationMiddleware::class, priority: 98)]
final class CatalogController extends AbstractController
{
    public function __construct(
        private readonly BookingServiceAdminService $serviceService,
        private readonly BookingLocationAdminService $locationService,
        private readonly BookingStaffAdminService $staffService,
        private readonly BookingScheduleAdminService $scheduleService,
    ) {}

    #[Get(path: '/admin/booking/service/list', operationId: 'bookingServiceList', summary: '服务列表', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:service:list')]
    #[ResultResponse(instance: new Result())]
    public function serviceList(): Result
    {
        return $this->success($this->serviceService->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/booking/service', operationId: 'bookingServiceCreate', summary: '创建服务', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:service:save')]
    #[ResultResponse(instance: new Result())]
    public function serviceCreate(): Result
    {
        $data = $this->getRequestData();
        $row = BookingService::query()->create([
            'shop_id' => (int) ($data['shop_id'] ?? 0),
            'name' => (string) ($data['name'] ?? ''),
            'description' => (string) ($data['description'] ?? ''),
            'duration_minutes' => max(1, (int) ($data['duration_minutes'] ?? 30)),
            'capacity' => max(1, (int) ($data['capacity'] ?? 1)),
            'buffer_minutes' => max(0, (int) ($data['buffer_minutes'] ?? 0)),
            'status' => (int) ($data['status'] ?? 1),
            'sort' => (int) ($data['sort'] ?? 0),
            'shopline_product_id' => (string) ($data['shopline_product_id'] ?? ''),
        ]);

        return $this->success($row);
    }

    #[Put(path: '/admin/booking/service/{id}', operationId: 'bookingServiceUpdate', summary: '更新服务', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:service:save')]
    #[ResultResponse(instance: new Result())]
    public function serviceUpdate(int $id): Result
    {
        $row = BookingService::query()->whereKey($id)->firstOrFail();
        $data = $this->getRequestData();
        $row->fill([
            'name' => (string) ($data['name'] ?? $row->name),
            'description' => (string) ($data['description'] ?? $row->description),
            'duration_minutes' => max(1, (int) ($data['duration_minutes'] ?? $row->duration_minutes)),
            'capacity' => max(1, (int) ($data['capacity'] ?? $row->capacity)),
            'buffer_minutes' => max(0, (int) ($data['buffer_minutes'] ?? $row->buffer_minutes)),
            'status' => (int) ($data['status'] ?? $row->status),
            'sort' => (int) ($data['sort'] ?? $row->sort),
            'shopline_product_id' => (string) ($data['shopline_product_id'] ?? $row->shopline_product_id),
        ])->save();

        return $this->success($row);
    }

    #[Delete(path: '/admin/booking/service/{id}', operationId: 'bookingServiceDelete', summary: '删除服务', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:service:delete')]
    #[ResultResponse(instance: new Result())]
    public function serviceDelete(int $id): Result
    {
        BookingService::query()->whereKey($id)->delete();

        return $this->success();
    }

    #[Get(path: '/admin/booking/location/list', operationId: 'bookingLocationList', summary: '门店列表', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:location:list')]
    #[ResultResponse(instance: new Result())]
    public function locationList(): Result
    {
        return $this->success($this->locationService->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/booking/location', operationId: 'bookingLocationCreate', summary: '创建门店', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:location:save')]
    #[ResultResponse(instance: new Result())]
    public function locationCreate(): Result
    {
        $data = $this->getRequestData();
        $row = BookingLocation::query()->create([
            'shop_id' => (int) ($data['shop_id'] ?? 0),
            'name' => (string) ($data['name'] ?? ''),
            'address' => (string) ($data['address'] ?? ''),
            'phone' => (string) ($data['phone'] ?? ''),
            'status' => (int) ($data['status'] ?? 1),
            'sort' => (int) ($data['sort'] ?? 0),
        ]);

        return $this->success($row);
    }

    #[Put(path: '/admin/booking/location/{id}', operationId: 'bookingLocationUpdate', summary: '更新门店', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:location:save')]
    #[ResultResponse(instance: new Result())]
    public function locationUpdate(int $id): Result
    {
        $row = BookingLocation::query()->whereKey($id)->firstOrFail();
        $data = $this->getRequestData();
        $row->fill([
            'name' => (string) ($data['name'] ?? $row->name),
            'address' => (string) ($data['address'] ?? $row->address),
            'phone' => (string) ($data['phone'] ?? $row->phone),
            'status' => (int) ($data['status'] ?? $row->status),
            'sort' => (int) ($data['sort'] ?? $row->sort),
        ])->save();

        return $this->success($row);
    }

    #[Delete(path: '/admin/booking/location/{id}', operationId: 'bookingLocationDelete', summary: '删除门店', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:location:delete')]
    #[ResultResponse(instance: new Result())]
    public function locationDelete(int $id): Result
    {
        BookingLocation::query()->whereKey($id)->delete();

        return $this->success();
    }

    #[Get(path: '/admin/booking/staff/list', operationId: 'bookingStaffList', summary: '员工列表', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:staff:list')]
    #[ResultResponse(instance: new Result())]
    public function staffList(): Result
    {
        return $this->success($this->staffService->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/booking/staff', operationId: 'bookingStaffCreate', summary: '创建员工', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:staff:save')]
    #[ResultResponse(instance: new Result())]
    public function staffCreate(): Result
    {
        $data = $this->getRequestData();
        $row = BookingStaff::query()->create([
            'shop_id' => (int) ($data['shop_id'] ?? 0),
            'name' => (string) ($data['name'] ?? ''),
            'email' => (string) ($data['email'] ?? ''),
            'phone' => (string) ($data['phone'] ?? ''),
            'status' => (int) ($data['status'] ?? 1),
            'sort' => (int) ($data['sort'] ?? 0),
        ]);
        if (! empty($data['service_ids']) && is_array($data['service_ids'])) {
            $row->services()->sync(array_map('intval', $data['service_ids']));
        }
        if (! empty($data['location_ids']) && is_array($data['location_ids'])) {
            $row->locations()->sync(array_map('intval', $data['location_ids']));
        }

        return $this->success($row->load(['services', 'locations']));
    }

    #[Put(path: '/admin/booking/staff/{id}', operationId: 'bookingStaffUpdate', summary: '更新员工', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:staff:save')]
    #[ResultResponse(instance: new Result())]
    public function staffUpdate(int $id): Result
    {
        $row = BookingStaff::query()->whereKey($id)->firstOrFail();
        $data = $this->getRequestData();
        $row->fill([
            'name' => (string) ($data['name'] ?? $row->name),
            'email' => (string) ($data['email'] ?? $row->email),
            'phone' => (string) ($data['phone'] ?? $row->phone),
            'status' => (int) ($data['status'] ?? $row->status),
            'sort' => (int) ($data['sort'] ?? $row->sort),
        ])->save();
        if (array_key_exists('service_ids', $data) && is_array($data['service_ids'])) {
            $row->services()->sync(array_map('intval', $data['service_ids']));
        }
        if (array_key_exists('location_ids', $data) && is_array($data['location_ids'])) {
            $row->locations()->sync(array_map('intval', $data['location_ids']));
        }

        return $this->success($row->load(['services', 'locations']));
    }

    #[Delete(path: '/admin/booking/staff/{id}', operationId: 'bookingStaffDelete', summary: '删除员工', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:staff:delete')]
    #[ResultResponse(instance: new Result())]
    public function staffDelete(int $id): Result
    {
        BookingStaff::query()->whereKey($id)->delete();

        return $this->success();
    }

    #[Get(path: '/admin/booking/schedule/list', operationId: 'bookingScheduleList', summary: '排班列表', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:schedule:list')]
    #[ResultResponse(instance: new Result())]
    public function scheduleList(): Result
    {
        return $this->success($this->scheduleService->page($this->getRequestData(), $this->getCurrentPage(), $this->getPageSize()));
    }

    #[Post(path: '/admin/booking/schedule', operationId: 'bookingScheduleCreate', summary: '创建排班', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:schedule:save')]
    #[ResultResponse(instance: new Result())]
    public function scheduleCreate(): Result
    {
        $data = $this->getRequestData();
        $row = BookingSchedule::query()->create([
            'shop_id' => (int) ($data['shop_id'] ?? 0),
            'staff_id' => (int) ($data['staff_id'] ?? 0),
            'location_id' => isset($data['location_id']) && $data['location_id'] !== '' ? (int) $data['location_id'] : null,
            'weekday' => (int) ($data['weekday'] ?? 1),
            'start_time' => (string) ($data['start_time'] ?? '09:00:00'),
            'end_time' => (string) ($data['end_time'] ?? '18:00:00'),
            'status' => (int) ($data['status'] ?? 1),
        ]);

        return $this->success($row);
    }

    #[Delete(path: '/admin/booking/schedule/{id}', operationId: 'bookingScheduleDelete', summary: '删除排班', security: [['Bearer' => [], 'ApiKey' => []]], tags: ['booking'])]
    #[Permission(code: 'booking:schedule:delete')]
    #[ResultResponse(instance: new Result())]
    public function scheduleDelete(int $id): Result
    {
        BookingSchedule::query()->whereKey($id)->delete();

        return $this->success();
    }
}
