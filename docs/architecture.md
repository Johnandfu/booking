# Architecture

## 基座
MineAdmin v3.0.9 + Hyperf 3.1。Booking 为独立业务域。

## 分层
Controller → Service → Repository → Model  
SHOPLINE 调用仅经 `App\Shopline\*` Adapter / Client。

## 当前可运行模块
- OAuth 内嵌安装 + Webhook 订阅 + 时区同步
- 商家 API / Storefront 选时 / Booking 并发锁单
- Product 同步：`POST /shopline/api/services/{id}/sync-product`
- Checkout UI Customizer 源码：`extensions/booking-checkout`
- Admin：预约 / 店铺 / 服务 / 门店 / 员工 / 排班

## 下一步（未完成）
- 用 SHOPLINE CLI 发布 Checkout Extension 并在店铺启用（需 Enterprise）
- Docker 可用时跑 migrate + Feature 测试
- 填真实 `SHOPLINE_*` 做端到端联调
