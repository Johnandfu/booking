<?php

declare(strict_types=1);

/**
 * SHOPLINE App 配置。
 *
 * OAuth 文档：https://developer.shopline.com/docs/apps/api-instructions-for-use/app-authorization
 * 签名文档：https://developer.shopline.com/docs/apps/api-instructions-for-use/generate-and-verify-signatures
 */
return [
    'app_key' => env('SHOPLINE_APP_KEY', ''),
    'app_secret' => env('SHOPLINE_APP_SECRET', ''),
    // Partner Portal「应用网址 / App URL」：内嵌入口
    'app_url' => env('SHOPLINE_APP_URL', ''),
    // 与开发者中心配置的 App Callback URL 一致
    'oauth_redirect_uri' => env('SHOPLINE_OAUTH_REDIRECT_URI', ''),
    // 逗号分隔的 permission points，例如 read_products,write_products,read_orders
    'scopes' => env('SHOPLINE_SCOPES', ''),
    'default_timezone' => env('SHOPLINE_DEFAULT_TIMEZONE', 'UTC'),
    'http_timeout' => (float) env('SHOPLINE_HTTP_TIMEOUT', 15),
    'api_version' => env('SHOPLINE_API_VERSION', 'v20260601'),
    // Webhook 订阅 api_version（须 >= v20240601）
    // https://developer.shopline.com/docs/admin-rest-api/webhook/subscribe-to-a-webhook
    'webhook_api_version' => env('SHOPLINE_WEBHOOK_API_VERSION', 'v20240601'),
    'webhook_callback_url' => env('SHOPLINE_WEBHOOK_CALLBACK_URL', ''),
    'token_refresh_before_minutes' => (int) env('SHOPLINE_TOKEN_REFRESH_BEFORE_MINUTES', 60),
    // 内嵌模式：授权必须走 App Bridge Oauth.invoke，禁止服务端 302
    'embedded' => filter_var(env('SHOPLINE_EMBEDDED', true), FILTER_VALIDATE_BOOLEAN),
];
