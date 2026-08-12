# Checkout UI Customizer — Booking

官方文档：
- https://developer.shopline.com/docs/apps/checkout/checkout-ui-customizer/build-an-extension
- Attributes API：`applyAttributeChange({ type: "updateAttribute", key, value })`
- Note API：`applyNoteChange({ type: "updateNote", note })`

## 作用
在结账页写入：
- checkout attribute `booking_no=<预约单号>`
- note `booking_no:<预约单号>`

服务端 `orders/create` Webhook 读取后确认 pending 预约。

## 发布
需 SHOPLINE CLI（本仓库不内嵌 CLI）：

```bash
cd extensions/booking-checkout
sl login --store={handle}.myshopline.com
sl extension connect   # 或 create
sl extension serve
sl extension push
```

商家后台：Checkout Settings → Customize checkout → Add app。

> Checkout UI Customizer 仅 Enterprise 套餐可用（官方说明）。
