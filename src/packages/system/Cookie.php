<?php
namespace Status\System;

use Status\Cookie\Converter;
use Status\Cookie\Creator;
use Status\Cookie\Reader;
use Status\Cookie\Remover;
use Status\Cookie\Storage;

/**
 * Class Cookie
 * @package Status\System
 */
final class Cookie
{
    private static $name = '';
    private static $data = '';
    private static $time = 0;

    /**
     * @param string $name
     * @param string $data
     * @param bool $base64encode
     * @return Cookie
     */
    public static function set(string $name, string $data, bool $base64encode = true)
    {
        self::$name = $name;
        self::$data = $data;

        $cookie = new Creator($base64encode);
        $cookie->make($name, $data);
        return new self();
    }

    /**
     * @param string $name
     * @param bool $base64decode
     * @return string|Converter
     */
    public static function get(string $name, bool $base64decode = true)
    {
        return (new Reader($base64decode))->make($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name)
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * @param string $name
     */
    public static function remove(string $name)
    {
        $cookie = new Remover();
        $cookie->make($name);
    }

    /**
     * @return array
     */
    public static function getStorage()
    {
        return Storage::getCache();
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public static function getStorageValue(string $key)
    {
        return Storage::getValue($key);
    }

    /**
     *
     */
    public function toSave()
    {
        Storage::setCache(self::$name, self::$data);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function removeStorage()
    {
        Storage::remove();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function removeStorageValue(string $key)
    {
        Storage::removeCacheValue($key);
    }

    /**
     * @return int
     */
    public static function getTime(): int
    {
        return self::$time;
    }
}