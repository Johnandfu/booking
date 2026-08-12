<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Http\Common\Controller;

use App\Http\Common\Result;
use App\Http\Common\ResultCode;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Context\ApplicationContext;

abstract class AbstractController
{
    protected function success(mixed $data = [], ?string $message = null): Result
    {
        return new Result(ResultCode::SUCCESS, $message, $data);
    }

    protected function error(?string $message = null, mixed $data = []): Result
    {
        return new Result(ResultCode::FAIL, $message, $data);
    }

    protected function json(ResultCode $code, mixed $data = [], ?string $message = null): Result
    {
        return new Result($code, $message, $data);
    }

    protected function getCurrentPage(): int
    {
        return (int) $this->getRequest()->input('page', 1);
    }

    protected function getPageSize(): int
    {
        return (int) $this->getRequest()->input('page_size', 10);
    }

    protected function getRequestData(): array
    {
        return $this->getRequest()->all();
    }

    protected function getRequest(): RequestInterface
    {
        return ApplicationContext::getContainer()->get(RequestInterface::class);
    }

    public static function realIP(): string
    {
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        $ip = $request->header('x-real-ip')
            ?? $request->header('x-forwarded-for')
            ?? $request->server('remote_addr')
            ?? 'unknown';
        return  data_get(explode(',', $ip), 0) ?: 'unknown';
    }
}
