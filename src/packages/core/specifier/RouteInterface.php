<?php
namespace Status\Core\Specifier;

/**
 * Interface RouteInterface
 * @package Status\Core\Specifier
 */
interface RouteInterface
{
    public static function get(String $redirect_url, String $controller_method);
    public static function post(String $redirect_url, String $controller_method);
    public static function delete(String $redirect_url, String $controller_method);
    public static function put(String $redirect_url, String $controller_method);
    public static function init();
    public static function start();
}