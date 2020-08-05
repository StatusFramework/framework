<?php
namespace Status\System;

/**
 * Class Env
 * @package Status\System
 */
class Env
{
    /**
     * @param string $name
     * @return string
     */
    public static function get(string $name): string
    {
        return constant($name);
    }
}