<?php

declare(strict_types=1);

namespace App\Crontab;

use App\Service\Shopline\ShoplineIntegrationService;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

/**
 * 定时刷新即将过期的 SHOPLINE access token。
 */
#[Crontab(
    rule: '*/10 * * * *',
    name: 'RefreshShoplineTokens',
    callback: 'execute',
    memo: 'Refresh SHOPLINE access tokens before expiry',
    singleton: true,
    onOneServer: true,
)]
final class RefreshShoplineTokensCrontab
{
    #[Inject]
    protected ShoplineIntegrationService $integrationService;

    public function execute(): void
    {
        $this->integrationService->refreshExpiringTokens();
    }
}
