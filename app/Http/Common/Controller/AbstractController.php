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
use App\Model\Enums\User\Status;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Context\ApplicationContext;
use Hyperf\Di\Aop\ProceedingJoinPoint;

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
    protected function getPageParams(): array
    {
        $request = $this->getRequest();

        $page  = max(1, (int) $request->input('page', 1));
        $limit = min(1000, max(0, (int) ($request->input('limit', 10))));

        return [
            'page'  => $page,
            'limit' => $limit,
            'offset' => ($page - 1) * $limit,
        ];
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

    /**
     * 获取客户端操作系统
     */
    public static function clientOS(): string
    {
        /** @var RequestInterface $request */
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        $ua = $request->header('user-agent', '');

        $osArray = [
            'Windows' => '/windows/i',
            'iOS'     => '/iphone|ipad|ipod/i',
            'Mac'     => '/macintosh|mac os x/i',
            'Android' => '/android/i',
            'Linux'   => '/linux/i',

        ];

        foreach ($osArray as $name => $pattern) {
            if (preg_match($pattern, $ua)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    /**
     * 获取客户端浏览器
     */
    public static function clientBrowser(): string
    {
        /** @var RequestInterface $request */
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        $ua = $request->header('user-agent', '');

        $browsers = [
            'Edge'      => '/edge/i',
            'Chrome'    => '/chrome/i',
            'Firefox'   => '/firefox/i',
            'Safari'    => '/safari/i',
            'Opera'     => '/opera|opr/i',
            'IE'        => '/msie|trident/i',
            'FBAN' => '/FBAN/i',
            'TikTok' => '/TikTok/i',
            'WeChat' => '/micromessenger|miniprogram/i',
            'QQ' => '/qq/i',
            'Whatsapp' => '/whatsapp/i',
            'Telegram' => '/telegram/i',
            'Crawler'  => '/bot|crawl|spider|preview|scanner|facebookexternalhit/i',
        ];


        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $ua)) {
                return $name;
            }
        }


        return 'Unknown';
    }


    public static function rateLimitKey(ProceedingJoinPoint $proceedingJoinPoint): string
    {
        $request = ApplicationContext::getContainer()->get(RequestInterface::class);
        $ip = $request->header('x-real-ip')
            ?? $request->header('x-forwarded-for')
            ?? $request->server('remote_addr')
            ?? 'unknown';
        return  $proceedingJoinPoint->className . '_' . $proceedingJoinPoint->methodName . '_' . (data_get(explode(',', $ip), 0) ?: 'unknown');
    }
}
