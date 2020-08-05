<?php
namespace Status\System;

use Status\Service\Hash;

/**
 * Class Cors
 * @package Status\System
 */
class Cors
{
    /**
     * @param string|NULL $origin
     * @return Cors
     */
    public static function setAllowOrigin(string $origin = NULL)
    {
        $accessOrigin = env("HEADERS_ALLOW_ORIGIN");
        $accessOrigin = empty($accessOrigin) ? NULL : $accessOrigin;
        $accessOrigin = empty($origin) ? $accessOrigin : $origin;
//        $accessOrigin =
//            preg_match('/'.str_replace("/","\/",$_SERVER["HTTP_ORIGIN"]).'/i', $accessOrigin)
//                ? $_SERVER["HTTP_ORIGIN"]
//                : NULL;

        if(!empty($accessOrigin))
            header("Access-Control-Allow-Origin: $accessOrigin");
        return new self;
    }

    /**
     * @param string|NULL $methods
     * @return Cors
     */
    public static function setAllowMethods(string $methods = NULL)
    {
        $accessMethods = constant("HEADERS_ALLOW_METHODS");
        $accessMethods = empty($accessMethods)
            ? NULL
            : $accessMethods
        ;
        $accessMethods = empty($methods) ? $accessMethods : $methods;

        if(!is_null($accessMethods))
            header("Access-Control-Allow-Methods: ".$accessMethods);

        return new self;
    }

    /**
     * @param bool|NULL $credentials
     * @return Cors
     */
    public static function setAllowCredentials(bool $credentials = NULL)
    {
        if($credentials)
        {
            $accessCredentials = 'true';
        }
        else
        {
            $accessCredentials = constant("HEADERS_ALLOW_CREDENTIALS");
            $accessCredentials = $accessCredentials  ? 'true' : '';
        }

        if($accessCredentials)
        {
            header("Access-Control-Allow-Credentials: {$accessCredentials}");
        }

        return new self;
    }

    /**
     * @param bool|NULL $credentials
     * @return Cors
     */
    public static function setAllowHeaders(bool $headers = NULL)
    {
        $accessHeaders = constant("HEADERS_ALLOW_HEADERS");
        $accessHeaders = empty($accessHeaders) ? '' : $accessHeaders;
        $accessHeaders = empty($headers) ? $accessHeaders : $headers;

        if(!empty($accessHeaders))
            header("Access-Control-Allow-Headers: ".$accessHeaders);

        return new self;
    }

    /**
     * Token and header: Verification
     */
    public static function setTokenVerification()
    {
        $hash = Hash::set('sha256', false);
        header("Verification: " . $hash);
    }
}