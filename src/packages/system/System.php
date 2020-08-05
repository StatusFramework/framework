<?php
namespace Status\System;
/**
 * Class System
 * @package Status\System
 */
class System
{
    /**
     * System constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $pack = '/vendor/n0zzy/status-framework/src/packages/service/functions.php';
        $path = dirname(__DIR__,2).$pack;

        if (!file_exists($path)){
            throw new \Exception("functions file not found", 500);
        }

        include_once $path;
    }
}