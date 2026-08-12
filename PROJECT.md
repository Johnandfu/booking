# SHOPLINE Calendar Booking

## 目标
开发类似 Calendar Booking 的 SHOPLINE 预约应用：商家配置服务、门店、员工、排班和可预约时间；客户选择时间并完成 SHOPLINE 下单；订单与 Booking 关联。

## 技术栈
- Base: [MineAdmin](https://github.com/mineadmin/MineAdmin) **v3.0.9**（Hyperf 3.1 后台骨架，含 `web/` Admin）
- Backend: PHP 8.1+/8.3, Hyperf **3.1**, MySQL 8, Redis, REST API
- Frontend: Vue 3, TypeScript, Vite（MineAdmin Admin，目录 `web/`）
- SHOPLINE: App, OAuth, Admin API, Storefront API, Webhook, Checkout UI Extension
- Infrastructure: Docker, Nginx, Redis, MySQL

## 基座约定
- 上游对照目标是 MineAdmin **v3.0.9**，不是 `master` / **v3.2**（3.2 为 Hyperf 3.2，且根目录无 `web/`，含部门/数据权限，不可直接合并）。
- 保留 MineAdmin 基座：Admin / Permission / Passport / Attachment / Log / App-Store 插件能力。
- 已移除无关 CMS / 前台 Index；Booking 在基座上增量开发。
- 分层：Controller → Service → Repository → Model（与 MineAdmin 一致）。
- Controller 不直接调用 SHOPLINE API；经 Adapter/Service。
- MySQL 是最终数据源，Redis 只做临时锁。
- 不要无关重构；不要为对齐而升级到 MineAdmin 3.2，除非单独规划升级 Phase。

## 核心实体
Service / Location / Staff / Schedule / Slot / SlotLock / Booking / BookingItem

## Booking 状态
pending / confirmed / cancelled / completed / no_show / expired

## Slot 状态
available / locked / full / disabled

## 核心流程
客户选择商品 → 查询可预约时间 → 锁定 Slot → Checkout → Order Created → Webhook → 确认 Booking。

## 并发原则
Redis Lock → MySQL Transaction → SELECT FOR UPDATE → 检查 capacity → 创建 Booking → 更新 booked → Commit。
Redis 仅用于临时锁，MySQL 是最终数据源。

## 时间
数据库统一 UTC；每个 Shop 保存 timezone；所有预约计算使用 Shop timezone。

## SHOPLINE
所有 API 必须以官方当前文档为准，禁止猜测接口。
业务代码不得直接调用 SHOPLINE API，必须通过 Adapter/Service。
Webhook 必须验证、记录 event、幂等处理、防重复。

## 数据库
shops
shop_tokens
booking_services
booking_locations
booking_staff
booking_schedules
booking_slots
booking_slot_locks
bookings
booking_items
booking_holidays
booking_blackouts
webhook_events

## 开发阶段
1. 基础项目、Docker、MySQL、Redis、Shop、OAuth
2. Booking Domain
3. Availability、Slot Lock、Booking、Cancel、Reschedule、并发
4. SHOPLINE OAuth/Product/Order/Webhook
5. Storefront 日期和时间选择
6. Checkout Extension
7. Admin Calendar/Booking/Service/Staff/Location/Schedule
8. 自动化测试
9. 生产部署

## Agent 要求
每次只完成明确 Phase。
不要无关重构。
完成后运行测试，并报告：修改文件、数据库变化、测试结果、未完成事项。
