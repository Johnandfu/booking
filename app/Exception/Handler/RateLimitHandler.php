<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Factory\Log;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\RateLimit\Exception\RateLimitException;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponseInterface;
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
