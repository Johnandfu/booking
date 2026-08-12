<?php

declare(strict_types=1);

namespace App\Shopline\Dto;

/**
 * OAuth token/create 与 token/refresh 成功响应 data 字段。
 *
 * 文档：https://developer.shopline.com/docs/apps/api-instructions-for-use/app-authorization
 */
final readonly class AccessTokenResult
{
    public function __construct(
        public string $accessToken,
        public string $expireTime,
        public string $scope,
    ) {}
}
