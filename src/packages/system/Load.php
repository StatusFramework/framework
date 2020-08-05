<?php
namespace Status\System;

use Status\Core\Route;
use Status\Core\Connection;
use Status\Security\CSRF;
use Status\Security\XCSRF;
use Status\System\Cors;
use Status\System\Session;

/**
 * Class Load
 * @package Status\Framework\System
 */
final class Load
{
    /**
     * @var string
     */
    private static $pathRouters = '../app/Routers.php';
    /**
     * @var string
     */
    private static $pathBridges = '../factory/Bridges.php';

    /**
     * @throws \Exception
     */
    public static function init()
    {
 	try
        {		
	    self::setHeaders();
            self::setSession();
            self::setXToken();
            self::setBridge();
            self::setConnection();
            self::setRoute();
        }
        catch (\Throwable $e)
        {
            Error::debug($e);
        }
    }

    /**
     * @throws \Exception
     */
    private static function setBridge()
    {
        if(!file_exists(self::$pathBridges))
        {
            throw new \Exception('bridges file not found [path: '.self::$pathBridges.']', 500);
        }
        include_once self::$pathBridges;
    }

    /**
     * @throws \Exception
     */
    private static function setRoute()
    {
        Route::init();
        if(!file_exists(self::$pathRouters))
        {
            throw new \Exception('routers file not found [path: '.self::$pathRouters.']', 500);
        }
        include_once self::$pathRouters;
        Route::start();
    }

    /**
     * @throws \Exception
     */
    private static function setConnection()
    {
        Connection::start();
    }

    /**
     * headers
     */
    private static function setHeaders()
    {
        Headers::start();
    }


    /**
     * @throws \Exception
     */
    private static function setSession()
    {
        Session::start()->clear();
    }

    /**
     * @throws \Exception
     */
    private static function setXToken()
    {
        XCSRF::setCookie();
        XCSRF::start();
    }
}