<?php
namespace Status\System;

/**
 * Class Ini
 * @package Status\System
 */
final class Ini
{
    /**
     * @var null
     */
    protected static $inst = NULL;
    /**
     * @var string
     */
    private static $ini = '.env';
    /**
     * @var array
     */
    private static $params = [];

    /**
     * @return bool
     */
    public static function init()
    {
        return true;
    }

    /**
     * @param bool $level
     */
    public static function start(bool $level = true)
    {
        self::open($level ? 6 : 1);
        self::setDefine();
    }

    /**
     * @param int $level
     */
    private static function open(int $level)
    {
        $parse = parse_ini_file(
            str_replace("\\", "/", dirname(__DIR__,$level).'/'.self::$ini),
            true,
            2
        );

        self::$params = is_array($parse) ? $parse : [];
    }

    /**
     *  setter define
     */
    private static  function setDefine()
    {
        foreach (self::$params as $keyGroup=>$arrayGroup)
        {
            foreach ($arrayGroup as $k=>$v)
            {
                $k = strtoupper($keyGroup.'_'.preg_replace_callback('/[-\s]+/', function(){
                        return '_';
                    }, $k));

                define($k, $v);
            }
        }
    }

    public static function get(string $ini, int $level = 6)
    {
        $parse = parse_ini_file(
            str_replace("\\", "/", dirname(__DIR__, $level).'/'.$ini.".ini"),
            true,
            2
        );

        return $parse;
    }
}