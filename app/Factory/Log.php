<?php

declare(strict_types=1);

namespace App\Factory;

use Hyperf\Context\ApplicationContext;
use Hyperf\Logger\LoggerFactory as HyperfLoggerFactory;
use Psr\Log\LoggerInterface;

class Log
{
    /**
     * 获取 Logger 实例，默认 channel 为 'app'
     */
    public static function channel(string $name = 'default'): LoggerInterface
    {
        $container = ApplicationContext::getContainer();

        if (!$container->has(HyperfLoggerFactory::class)) {
            throw new \RuntimeException('LoggerFactory not found in container.');
        }

        /** @var HyperfLoggerFactory $factory */
        $factory = $container->get(HyperfLoggerFactory::class);

        return $factory->get($name);
    }

    // -------- 静态便捷方法 --------

    public static function emergency(string|\Stringable $message, array $context = []): void
    {
        self::channel()->emergency($message, $context);
    }

    public static function alert(string|\Stringable $message, array $context = []): void
    {
        self::channel()->alert($message, $context);
    }

    public static function critical(string|\Stringable $message, array $context = []): void
    {
        self::channel()->critical($message, $context);
    }

    public static function error(string|\Stringable $message, array $context = []): void
    {
        self::channel()->error($message, $context);
    }

    public static function warning(string|\Stringable $message, array $context = []): void
    {
        self::channel()->warning($message, $context);
    }

    public static function notice(string|\Stringable $message, array $context = []): void
    {
        self::channel()->notice($message, $context);
    }

    public static function info(string|\Stringable $message, array $context = []): void
    {
        self::channel()->info($message, $context);
    }

    public static function debug(string|\Stringable $message, array $context = []): void
    {
        self::channel()->debug($message, $context);
    }

    public static function log($level, string|\Stringable $message, array $context = []): void
    {
        self::channel()->log($level, $message, $context);
    }
}
