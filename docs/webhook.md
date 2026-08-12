# Webhook

## 文档依据
- Overview: https://developer.shopline.com/docs/apps/api-instructions-for-use/webhooks/overview
- Signature: https://developer.shopline.com/docs/apps/api-instructions-for-use/generate-and-verify-signatures
- Subscribe: https://developer.shopline.com/docs/admin-rest-api/webhook/subscribe-to-a-webhook
- Order created: https://developer.shopline.com/docs/webhook/order/order-created（topic = `orders/create`）

## 接收
- `POST /shopline/webhook`
- 校验 `X-Shopline-Hmac-Sha256` = HMAC-SHA256(raw body, app secret)（hex 或 base64）
- 以 `X-Shopline-Webhook-Id` 唯一落库 `webhook_events`，已 `processed` 则直接 200
- 成功响应：`HTTP 200 OK`

## orders/create → 确认预约
1. 从 payload `note_attributes` 读取 `name=booking_no`
2. 或从 `note` 解析 `booking_no:XXX`
3. 将对应 **pending** Booking 置为 **confirmed**，写入 `shopline_order_id`
4. 已确认则幂等返回

## 订阅
OAuth 成功后调用 Admin API：

`POST /admin/openapi/{SHOPLINE_API_VERSION}/webhooks.json`

Body：
```json
{
  "webhook": {
    "address": "{APP_URL}/shopline/webhook",
    "api_version": "v20240601",
    "topic": "orders/create"
  }
}
```

可用 `SHOPLINE_WEBHOOK_CALLBACK_URL` 覆盖回调地址。
