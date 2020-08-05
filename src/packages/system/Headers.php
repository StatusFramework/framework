<?php
namespace Status\System;

use Status\Service\Hash;
use Status\System\Cors;

/**
 * Class Headers
 * @package Status\System
 */
final class Headers extends Cors
{
    /**
     * @var bool
     */
    private static $statusContent = false;

    /**
     *
     */
    public static function start()
    {
        self::setDefaultContentType();
        self::setPowerBy();
        self::setAllowMethods();
        self::setAllowCredentials();
        self::setAllowHeaders();
        self::setAllowOrigin();
    }

    private static function setDefaultContentType()
    {
        header('Content-Type: text/html');
    }

    /**
     *
     */
    private static function setPowerBy()
    {
        $by = env("HEADERS_POWERED_BY");
        $by = (empty($by)) ? "unknown" : $by;
        header("X-Powered-By: " . $by);
    }

    /**
     * @param string $key
     */
    public static function get(string $key)
    {

    }

    /**
     * @return bool
     */
    public static function getContentType()
    {
        return self::$statusContent;
    }

    /**
     *
     */
    public static function setContentType()
    {
        self::$statusContent = true;
    }
}