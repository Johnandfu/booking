# Database Design

时间字段统一 UTC；业务计算使用 `shops.timezone`。

## Phase 1
- shops / shop_tokens

## Phase 2+
- booking_services / booking_locations / booking_staff
- booking_staff_service / booking_staff_location
- booking_schedules / booking_slots / booking_slot_locks
- bookings / booking_items
- booking_holidays / booking_blackouts
- webhook_events

迁移：
- `2026_08_12_100001_create_shops_table.php`
- `2026_08_12_100002_create_shop_tokens_table.php`
- `2026_08_12_110000_create_booking_domain_tables.php`
