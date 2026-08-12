<?php

declare(strict_types=1);

namespace App\Http\Shopline\Controller;

use App\Http\Common\Controller\AbstractController;
use App\Http\Common\Result;
use App\Http\Shopline\Middleware\ShopSessionMiddleware;
use App\Model\Booking\Booking;
use App\Model\Booking\BookingBlackout;
use App\Model\Booking\BookingHoliday;
use App\Model\Booking\BookingLocation;
use App\Model\Booking\BookingSchedule;
use App\Model\Booking\BookingService;
use App\Model\Booking\BookingStaff;
use App\Model\Shop;
use App\Service\Booking\AvailabilityService;
use App\Service\Booking\BookingFlowService;
use App\Service\Shopline\ShoplineShopSyncService;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Swagger\Annotation as OA;
use Hyperf\Swagger\Annotation\Delete;
use Hyperf\Swagger\Annotation\Get;
use Hyperf\Swagger\Annotation\Post;
use Hyperf\Swagger\Annotation\Put;

/**
 * 内嵌商家 API（需 shop session）。
 */
#[OA\HyperfServer(name: 'http')]
#[Middleware(middleware: ShopSessionMiddleware::class, priority: 100)]
final class ShopApiController extends AbstractController
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
        private readonly BookingFlowService $bookingFlowService,
        private readonly ShoplineShopSyncService $shopSyncService,
        private readonly RequestInterface $request,
    ) {}

    private function shop(): Shop
    {
        /** @var Shop $shop */
        $shop = $this->request->getAttribute(ShopSessionMiddleware::ATTR_SHOP);

        return $shop;
    }

    #[Get(path: '/shopline/api/me', summary: '当前店铺', tags: ['shopline:api'])]
    public function me(): Result
    {
        $shop = $this->shop();

        return $this->success([
            'shop_id' => $shop->id,
            'handle' => $shop->handle,
            'timezone' => $shop->timezone,
            'locale' => $shop->locale,
        ]);
    }

    #[Get(path: '/shopline/api/services', summary: '服务列表', tags: ['shopline:api'])]
    public function services(): Result
    {
        $list = BookingService::query()->where('shop_id', $this->shop()->id)->orderBy('sort')->get();

        return $this->success(['list' => $list]);
    }

    #[Post(path: '/shopline/api/services', summary: '创建服务', tags: ['shopline:api'])]
    public function createService(): Result
    {
        $data = $this->request->all();
        $row = BookingService::query()->create([
            'shop_id' => $this->shop()->id,
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

    #[Put(path: '/shopline/api/services/{id}', summary: '更新服务', tags: ['shopline:api'])]
    public function updateService(int $id): Result
    {
        $row = BookingService::query()->where('shop_id', $this->shop()->id)->whereKey($id)->firstOrFail();
        $data = $this->request->all();
        $row->fill([
            'name' => (string) ($data['name'] ?? $row->name),
            'description' => (string) ($data['description'] ?? $row->description),
            'duration_minutes' => max(1, (int) ($data['duration_minutes'] ?? $row->duration_minutes)),
            'capacity' => max(1, (int) ($data['capacity'] ?? $row->capacity)),
            'buffer_minutes' => max(0, (int) ($data['buffer_minutes'] ?? $row->buffer_minutes)),
            'status' => (int) ($data['status'] ?? $row->status),
            'sort' => (int) ($data['sort'] ?? $row->sort),
        ])->save();

        return $this->success($row);
    }

    #[Delete(path: '/shopline/api/services/{id}', summary: '删除服务', tags: ['shopline:api'])]
    public function deleteService(int $id): Result
    {
        BookingService::query()->where('shop_id', $this->shop()->id)->whereKey($id)->delete();

        return $this->success();
    }

    #[Get(path: '/shopline/api/locations', summary: '门店列表', tags: ['shopline:api'])]
    public function locations(): Result
    {
        return $this->success(['list' => BookingLocation::query()->where('shop_id', $this->shop()->id)->orderBy('sort')->get()]);
    }

    #[Post(path: '/shopline/api/locations', summary: '创建门店', tags: ['shopline:api'])]
    public function createLocation(): Result
    {
        $data = $this->request->all();
        $row = BookingLocation::query()->create([
            'shop_id' => $this->shop()->id,
            'name' => (string) ($data['name'] ?? ''),
            'address' => (string) ($data['address'] ?? ''),
            'phone' => (string) ($data['phone'] ?? ''),
            'status' => (int) ($data['status'] ?? 1),
            'sort' => (int) ($data['sort'] ?? 0),
        ]);

        return $this->success($row);
    }

    #[Put(path: '/shopline/api/locations/{id}', summary: '更新门店', tags: ['shopline:api'])]
    public function updateLocation(int $id): Result
    {
        $row = BookingLocation::query()->where('shop_id', $this->shop()->id)->whereKey($id)->firstOrFail();
        $data = $this->request->all();
        $row->fill([
            'name' => (string) ($data['name'] ?? $row->name),
            'address' => (string) ($data['address'] ?? $row->address),
            'phone' => (string) ($data['phone'] ?? $row->phone),
            'status' => (int) ($data['status'] ?? $row->status),
            'sort' => (int) ($data['sort'] ?? $row->sort),
        ])->save();

        return $this->success($row);
    }

    #[Delete(path: '/shopline/api/locations/{id}', summary: '删除门店', tags: ['shopline:api'])]
    public function deleteLocation(int $id): Result
    {
        BookingLocation::query()->where('shop_id', $this->shop()->id)->whereKey($id)->delete();

        return $this->success();
    }

    #[Get(path: '/shopline/api/staff', summary: '员工列表', tags: ['shopline:api'])]
    public function staff(): Result
    {
        $list = BookingStaff::query()
            ->where('shop_id', $this->shop()->id)
            ->with(['services:id', 'locations:id'])
            ->orderBy('sort')
            ->get();

        return $this->success(['list' => $list]);
    }

    #[Post(path: '/shopline/api/staff', summary: '创建员工', tags: ['shopline:api'])]
    public function createStaff(): Result
    {
        $data = $this->request->all();
        $row = BookingStaff::query()->create([
            'shop_id' => $this->shop()->id,
            'name' => (string) ($data['name'] ?? ''),
            'email' => (string) ($data['email'] ?? ''),
            'phone' => (string) ($data['phone'] ?? ''),
            'status' => (int) ($data['status'] ?? 1),
            'sort' => (int) ($data['sort'] ?? 0),
        ]);
        $row->services()->sync(array_map('intval', (array) ($data['service_ids'] ?? [])));
        $row->locations()->sync(array_map('intval', (array) ($data['location_ids'] ?? [])));

        return $this->success($row->load(['services:id', 'locations:id']));
    }

    #[Put(path: '/shopline/api/staff/{id}', summary: '更新员工', tags: ['shopline:api'])]
    public function updateStaff(int $id): Result
    {
        $row = BookingStaff::query()->where('shop_id', $this->shop()->id)->whereKey($id)->firstOrFail();
        $data = $this->request->all();
        $row->fill([
            'name' => (string) ($data['name'] ?? $row->name),
            'email' => (string) ($data['email'] ?? $row->email),
            'phone' => (string) ($data['phone'] ?? $row->phone),
            'status' => (int) ($data['status'] ?? $row->status),
            'sort' => (int) ($data['sort'] ?? $row->sort),
        ])->save();
        if (array_key_exists('service_ids', $data)) {
            $row->services()->sync(array_map('intval', (array) $data['service_ids']));
        }
        if (array_key_exists('location_ids', $data)) {
            $row->locations()->sync(array_map('intval', (array) $data['location_ids']));
        }

        return $this->success($row->load(['services:id', 'locations:id']));
    }

    #[Delete(path: '/shopline/api/staff/{id}', summary: '删除员工', tags: ['shopline:api'])]
    public function deleteStaff(int $id): Result
    {
        BookingStaff::query()->where('shop_id', $this->shop()->id)->whereKey($id)->delete();

        return $this->success();
    }

    #[Get(path: '/shopline/api/schedules', summary: '排班列表', tags: ['shopline:api'])]
    public function schedules(): Result
    {
        return $this->success([
            'list' => BookingSchedule::query()->where('shop_id', $this->shop()->id)->orderBy('staff_id')->orderBy('weekday')->get(),
        ]);
    }

    #[Post(path: '/shopline/api/schedules', summary: '创建排班', tags: ['shopline:api'])]
    public function createSchedule(): Result
    {
        $data = $this->request->all();
        $row = BookingSchedule::query()->create([
            'shop_id' => $this->shop()->id,
            'staff_id' => (int) ($data['staff_id'] ?? 0),
            'location_id' => isset($data['location_id']) && $data['location_id'] !== '' ? (int) $data['location_id'] : null,
            'weekday' => (int) ($data['weekday'] ?? 1),
            'start_time' => (string) ($data['start_time'] ?? '09:00:00'),
            'end_time' => (string) ($data['end_time'] ?? '18:00:00'),
            'status' => (int) ($data['status'] ?? 1),
        ]);

        return $this->success($row);
    }

    #[Delete(path: '/shopline/api/schedules/{id}', summary: '删除排班', tags: ['shopline:api'])]
    public function deleteSchedule(int $id): Result
    {
        BookingSchedule::query()->where('shop_id', $this->shop()->id)->whereKey($id)->delete();

        return $this->success();
    }

    #[Get(path: '/shopline/api/availability', summary: '可预约时段', tags: ['shopline:api'])]
    public function availability(): Result
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

    #[Post(path: '/shopline/api/slot-locks', summary: '锁定 Slot', tags: ['shopline:api'])]
    public function lockSlot(): Result
    {
        $data = $this->request->all();

        return $this->success($this->bookingFlowService->lockSlot(
            $this->shop(),
            (int) ($data['slot_id'] ?? 0),
            (string) ($data['customer_key'] ?? ''),
        ));
    }

    #[Post(path: '/shopline/api/bookings', summary: '创建预约', tags: ['shopline:api'])]
    public function createBooking(): Result
    {
        $payload = $this->request->all();
        $payload['confirm'] = true;

        return $this->success($this->bookingFlowService->createBooking($this->shop(), $payload));
    }

    #[Get(path: '/shopline/api/bookings', summary: '预约列表', tags: ['shopline:api'])]
    public function bookings(): Result
    {
        $list = Booking::query()
            ->where('shop_id', $this->shop()->id)
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        return $this->success(['list' => $list]);
    }

    #[Post(path: '/shopline/api/bookings/{id}/cancel', summary: '取消预约', tags: ['shopline:api'])]
    public function cancelBooking(int $id): Result
    {
        return $this->success($this->bookingFlowService->cancelBooking($this->shop(), $id));
    }

    #[Post(path: '/shopline/api/holidays', summary: '创建假日', tags: ['shopline:api'])]
    public function createHoliday(): Result
    {
        $data = $this->request->all();
        $row = BookingHoliday::query()->updateOrCreate(
            [
                'shop_id' => $this->shop()->id,
                'holiday_date' => (string) ($data['holiday_date'] ?? ''),
            ],
            [
                'name' => (string) ($data['name'] ?? ''),
                'status' => (int) ($data['status'] ?? 1),
            ]
        );

        return $this->success($row);
    }

    #[Post(path: '/shopline/api/blackouts', summary: '创建黑名单时段', tags: ['shopline:api'])]
    public function createBlackout(): Result
    {
        $data = $this->request->all();
        $row = BookingBlackout::query()->create([
            'shop_id' => $this->shop()->id,
            'staff_id' => isset($data['staff_id']) && $data['staff_id'] !== '' ? (int) $data['staff_id'] : null,
            'location_id' => isset($data['location_id']) && $data['location_id'] !== '' ? (int) $data['location_id'] : null,
            'start_at' => (string) ($data['start_at'] ?? ''),
            'end_at' => (string) ($data['end_at'] ?? ''),
            'reason' => (string) ($data['reason'] ?? ''),
        ]);

        return $this->success($row);
    }

    #[Put(path: '/shopline/api/shop/timezone', summary: '更新店铺时区', tags: ['shopline:api'])]
    public function updateTimezone(): Result
    {
        $tz = trim((string) $this->request->input('timezone', ''));
        if ($tz === '') {
            return $this->error('timezone required');
        }
        $shop = $this->shop();
        $shop->timezone = $tz;
        $shop->save();

        return $this->success(['timezone' => $shop->timezone]);
    }

    #[Post(path: '/shopline/api/shop/sync-timezone', summary: '从 SHOPLINE 同步时区', tags: ['shopline:api'])]
    public function syncTimezone(): Result
    {
        return $this->success($this->shopSyncService->syncTimezone($this->shop()));
    }

    #[Post(path: '/shopline/api/services/{id}/sync-product', summary: '同步服务到 SHOPLINE 商品', tags: ['shopline:api'])]
    public function syncServiceProduct(int $id): Result
    {
        $price = $this->request->input('price');

        return $this->success($this->shopSyncService->syncServiceProduct(
            $this->shop(),
            $id,
            is_string($price) || is_numeric($price) ? (string) $price : null,
        ));
    }
}
