<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 02.05.2019
 * Time: 17:38
 */

namespace Status\Session;

/**
 * Class Storage
 * @package Status\Session
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
        return isset(self::$cache[$key]) ? self::$cache[$key] : NULL;
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