# SHOPLINE Integration

对照官方文档（禁止猜测接口）：

- App authorization: https://developer.shopline.com/docs/apps/api-instructions-for-use/app-authorization
- Signatures: https://developer.shopline.com/docs/apps/api-instructions-for-use/generate-and-verify-signatures
- Embedded App Bridge OAuth: https://developer.shopline.com/docs/apps/development-tool/app-bridge/actions/authorization
- Webhooks overview: https://developer.shopline.com/docs/apps/api-instructions-for-use/webhooks/overview
- Subscribe webhook: https://developer.shopline.com/docs/admin-rest-api/webhook/subscribe-to-a-webhook
- Order created: https://developer.shopline.com/docs/webhook/order/order-created

## 内嵌模式（默认）

Partner Portal 须配置：
- **打开方式**：内嵌（Embedded）
- **应用网址 / App URL**：`{APP_URL}/shopline/app`
- **回调网址 / Callback URL**：`{APP_URL}/shopline/oauth/callback`

流程：
1. 商家在 SHOPLINE Admin 安装应用 → iframe 打开 App URL（带 `handle/timestamp/appkey/sign`）
2. 服务端验签；若未授权，返回页面并通过 **App Bridge** `Oauth.create(app).invoke()` 在父窗口发起授权（禁止服务端 302）
3. 授权成功后回调 `/shopline/oauth/callback` → `token/create` → 写入 `shops`/`shop_tokens` → 订阅 `orders/create`
4. 再次打开 App URL 且 token 未过期 → 显示内嵌首页

Nginx `/shopline/` 使用 `Content-Security-Policy: frame-ancestors https://*.myshopline.com ...`，不设 `X-Frame-Options`。

## 配置（`.env`）
- `SHOPLINE_APP_KEY` / `SHOPLINE_APP_SECRET`
- `SHOPLINE_APP_URL` / `SHOPLINE_OAUTH_REDIRECT_URI` / `SHOPLINE_SCOPES`
- `SHOPLINE_EMBEDDED=true`
- `SHOPLINE_DEFAULT_TIMEZONE` / `SHOPLINE_API_VERSION`
- `SHOPLINE_WEBHOOK_API_VERSION`（默认 `v20240601`）
- `SHOPLINE_WEBHOOK_CALLBACK_URL`（默认 `{APP_URL}/shopline/webhook`）
- `SHOPLINE_TOKEN_REFRESH_BEFORE_MINUTES` / `CRONTAB_ENABLE`

## 入口
| Method | Path | 说明 |
|--------|------|------|
| GET | `/shopline/app` | 内嵌 App URL |
| GET | `/shopline/install` | 同上 |
| GET | `/shopline/oauth/callback` | OAuth 回调 |
| POST | `/shopline/webhook` | Webhook 接收 |

## Adapter / Service
- `App\Shopline\ShoplineSignature`（含 Webhook HMAC）
- `App\Shopline\Client\ShoplineOAuthClient`
- `App\Shopline\Client\ShoplineAdminClient`（webhook / shop / product）
- `App\Service\Shopline\ShoplineOAuthService`
- `App\Service\Shopline\ShoplineWebhookService`
- `App\Service\Shopline\ShoplineIntegrationService`
- `App\Service\Shopline\ShoplineShopSyncService`

## Token 刷新
- Crontab：每 10 分钟 `RefreshShoplineTokens`
- 命令：`php bin/hyperf.php shopline:refresh-tokens`

## Product / Timezone
- `POST /shopline/api/services/{id}/sync-product` — Create product（`write_products`）
- `POST /shopline/api/shop/sync-timezone` — Query shop（`read_store_information`）
- OAuth 成功后静默同步 timezone

## Checkout Extension
源码目录：`extensions/booking-checkout`（需 SHOPLINE CLI push）

## 安全
- Access Token 不写业务日志
- OAuth / Webhook / Booking / Product sync Redis NX 锁
- GET 验签 + Webhook 验签 + event_id 幂等

## 未完成
- Extension 发布与店铺启用
- Calendar 可视化
