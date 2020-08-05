<?php
namespace Status\Service;

/**
 * Class Page
 * @package Status\Service
 */
class Page
{
    /**
     * @var bool
     */
    private static $html = false;
    /**
     * @var bool
     */
    private static $head = false;
    /**
     * @var bool
     */
    private static $body = false;

    /**
     * @return bool
     */
    public static function getHTML()
    {
        return self::$html;
    }

    public static function getHEAD()
    {
        return self::$head;
    }

    public static function getBODY()
    {
        return self::$body;
    }

    public static function setHTML()
    {
        self::$html = true;
    }

    public static function setHEAD()
    {
        self::$head = true;
    }

    public static function setBODY()
    {
        self::$body = true;
    }
}