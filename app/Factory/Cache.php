<?php

namespace App\Factory;

use Hyperf\Context\ApplicationContext;
use Psr\SimpleCache\CacheInterface;

class Cache
{
    /**
     * 获取 Cache 实例
     */
    public static function Instance(): CacheInterface
    {
        // 需要注意在http/cli 环境问题
        $container = ApplicationContext::getContainer();

        // 获取默认 cache 实例
        return $container->get(CacheInterface::class);
    }


    /**
     * 读取缓存
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::Instance()->get($key, $default);
    }

    /**
     * 写入缓存
     */
    public static function set(string $key, mixed $value, ?int $ttl = null): bool
    {
        return self::Instance()->set($key, $value, $ttl);
    }

    /**
     * 删除缓存
     */
    public static function delete(string $key): bool
    {
        return self::Instance()->delete($key);
    }

    /**
     * 判断 key 是否存在
     */
    public static function has(string $key): bool
    {
        return self::Instance()->has($key);
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {
        return self::Instance()->clear();
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable<string> $keys    A list of keys that can be obtained in a single operation.
     * @param mixed            $default Default value to return for keys that do not exist.
     *
     * @return iterable<string, mixed> A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return self::Instance()->getMultiple($keys, $default);
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable               $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $values is neither an array nor a Traversable,
     *   or if any of the $values are not a legal value.
     */
    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        return self::Instance()->setMultiple($values, $ttl);
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable<string> $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function deleteMultiple(iterable $keys): bool
    {
        return self::Instance()->deleteMultiple($keys);
    }
}
