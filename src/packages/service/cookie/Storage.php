<?php
namespace Status\Cookie;

/**
 * Class Storage
 * @package Status\Cookie
 */
final class Storage
{
    /**
     * @var array
     */
    private static $cache = [];

    /**
     * @return array
     */
    public static function getCache(): array
    {
        return self::$cache;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public static function getValue(string $key)
    {
        return isset(self::$cache[$key]) ? json_decode(self::$cache[$key]) : NULL;
    }

    /**
     * @param string $key
     * @param $data
     */
    public static function setCache(string $key, $data)
    {
        self::$cache = array_merge(self::$cache, [$key=>$data]);
    }

    /**
     * clear cache
     */
    public static function removeCache()
    {
        self::$cache = [];
    }

    /**
     * @param string $key
     */
    public static function removeCacheValue(string $key)
    {
        unset(self::$cache[$key]);
    }
}