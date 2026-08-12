<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\RateLimit\Exception\RateLimitException;
use App\Http\Common\Result;
use App\Http\Common\ResultCode;

class RateLimitHandler extends AbstractHandler
{
    public function handleResponse(\Throwable $throwable): Result
    {
        $this->stopPropagation();

        return new Result(
            code: ResultCode::TOO_MANY_REQUEST,
            message: 'Too Many Requests',
        );
    }

    public function isValid(\Throwable $throwable): bool
    {
        return $throwable instanceof RateLimitException;
    }
}
