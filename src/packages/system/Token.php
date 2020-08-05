<?php
namespace Status\System;

use Status\Service\Hash;

/**
 * Class Token
 * @package Status\System
 */
class Token
{
    /**
     * @var string 
     */
    private static $code = '';

    /**
     *
     */
    public static function setCSRF()
    {
        self::$code = Hash::set();
        $csrf = env("APP_TOKEN_CSRF");
        if(!$csrf)
            header("Authorization: Token ".self::$code);
    }

    /**
     *
     */
    public static function getCSRF()
    {

    }

    /**
     *
     */
    public static function validCSRF()
    {

    }
}