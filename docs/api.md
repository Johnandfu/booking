# API

## 内嵌公开页
- `GET /shopline/app` / `/shopline/install` — 内嵌入口
- `GET /shopline/oauth/callback` — OAuth 回调
- `POST /shopline/webhook` — Webhook（验签，返回 200）

## 商家 API（Authorization: Bearer {shopSession}）
| Method | Path | 说明 |
|--------|------|------|
| GET | `/shopline/api/me` | 当前店铺 |
| CRUD | `/shopline/api/services` | 服务 |
| CRUD | `/shopline/api/locations` | 门店 |
| CRUD | `/shopline/api/staff` | 员工（含 service_ids/location_ids） |
| GET/POST/DELETE | `/shopline/api/schedules` | 排班 |
| GET | `/shopline/api/availability` | `service_id,location_id,date[,staff_id]` |
| POST | `/shopline/api/slot-locks` | `{slot_id,customer_key?}` |
| POST | `/shopline/api/bookings` | `{slot_id,lock_token,...}`（商家端直接 confirmed） |
| GET | `/shopline/api/bookings` | 列表 |
| POST | `/shopline/api/bookings/{id}/cancel` | 取消 |
| PUT | `/shopline/api/shop/timezone` | 更新时区 |

## Storefront（短时 token，picker 页签发）
| Method | Path | 说明 |
|--------|------|------|
| GET | `/storefront/{handle}/picker` | 选时页 |
| GET | `/storefront/api/{handle}/services` | 服务 |
| GET | `/storefront/api/{handle}/locations` | 门店 |
| GET | `/storefront/api/{handle}/availability` | 可预约时段 |
| POST | `/storefront/api/{handle}/slot-locks` | 锁 Slot |
| POST | `/storefront/api/{handle}/bookings` | 创建 **pending** 预约 |

## Admin
| Method | Path | 权限码 |
|--------|------|--------|
| GET | `/admin/booking/list` | `booking:booking:list` |
| POST | `/admin/booking/{id}/cancel` | `booking:booking:cancel` |
| GET | `/admin/booking/shop/list` | `booking:shop:list` |
| CRUD | `/admin/booking/service*` | `booking:service:*` |
| CRUD | `/admin/booking/location*` | `booking:location:*` |
| CRUD | `/admin/booking/staff*` | `booking:staff:*` |
| GET/POST/DELETE | `/admin/booking/schedule*` | `booking:schedule:*` |

## 商家同步
| Method | Path | 说明 |
|--------|------|------|
| POST | `/shopline/api/shop/sync-timezone` | GET merchants/shop.json → timezone |
| POST | `/shopline/api/services/{id}/sync-product` | POST products/products.json |

统一响应：`{ code, message, data }`（Webhook 除外，纯 200）。
