# Requirements

## MVP 可用标准（当前）
1. 内嵌安装授权、Webhook、Token 刷新
2. 商家配置服务/门店/员工/排班并预约
3. Storefront 选时创建 pending 预约
4. Checkout Extension 源码写入 `booking_no`（需 CLI 发布）
5. `orders/create` 确认预约
6. 服务同步为 SHOPLINE Product；安装后同步店铺时区
7. MineAdmin 后台 CRUD 列表

## 客户主流程
选时 → pending booking → Checkout 附加 booking_no → Order Created Webhook → confirmed

## 仍缺
- Extension 实际上线（Enterprise + CLI push）
- Calendar 可视化 Admin
- 端到端真实店铺联调
