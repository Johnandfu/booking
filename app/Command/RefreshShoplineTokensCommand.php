<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Shopline\ShoplineIntegrationService;
use Hyperf\Command\Annotation\AsCommand;
use Hyperf\Command\Concerns\InteractsWithIO;

final class RefreshShoplineTokensCommand
{
    use InteractsWithIO;

    public function __construct(
        private readonly ShoplineIntegrationService $integrationService,
    ) {}

    #[AsCommand(
        signature: 'shopline:refresh-tokens {--minutes= : Refresh tokens expiring within N minutes}',
        description: 'Refresh SHOPLINE access tokens that are about to expire'
    )]
    public function handle(): void
    {
        $minutes = $this->input->getOption('minutes');
        $result = $this->integrationService->refreshExpiringTokens(
            $minutes !== null && $minutes !== '' ? (int) $minutes : null
        );
        $this->output->writeln(sprintf(
            'refreshed=%d failed=%d skipped=%d',
            $result['refreshed'],
            $result['failed'],
            $result['skipped'],
        ));
    }
}
