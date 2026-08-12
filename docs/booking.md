# Booking Domain

## 状态
- Booking: pending / confirmed / cancelled / completed / no_show / expired
- Slot: available / locked / full / disabled
- SlotLock: active / released / consumed / expired

## 时间
- DB 存 UTC
- 可用性按 `shops.timezone` 解释日期与排班

## 并发
1. Redis NX 临时锁（slot）
2. MySQL Transaction + `SELECT FOR UPDATE` on `booking_slots`
3. 校验 capacity（booked + active locks）
4. 创建 booking / 更新 booked
5. Redis 锁释放

## 可用流程
### 商家内嵌 MVP
1. 创建 Service / Location / Staff
2. 创建 Schedule
3. Availability → Lock → Create Booking（confirmed）
4. Cancel

### 客户 Storefront → 订单确认
1. 打开 `/storefront/{handle}/picker`
2. Availability → Lock → Create Booking（**pending**）
3. 结账时订单 `note_attributes` 写入 `booking_no`
4. `orders/create` Webhook → confirm + 写 `shopline_order_id`

## 核心类
- `App\Service\Booking\AvailabilityService`
- `App\Service\Booking\BookingFlowService`
- `App\Service\Shopline\ShoplineWebhookService`
- `App\Http\Shopline\Controller\ShopApiController`
- `App\Http\Storefront\Controller\StorefrontController`
