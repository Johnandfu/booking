<?php

declare(strict_types=1);

namespace App\Shopline\Exception;

use RuntimeException;

final class ShoplineApiException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?string $i18nCode = null,
        public readonly mixed $payload = null,
        int $code = 0,
    ) {
        parent::__construct($message, $code);
    }
}
