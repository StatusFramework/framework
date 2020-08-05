<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 02.05.2019
 * Time: 13:48
 */

namespace Status\Cookie;

/**
 * Class Founder
 * @package Status\Cookie
 */
class Founder
{
    /**
     * @param string|NULL $name
     * @return bool
     */
    final public function exists(string $name = NULL)
    {
        return isset($_COOKIE[
            empty($name) ? env("COOKIE_NAME") : $name
        ]);
    }

    /**
     * @param string $name
     * @return bool
     */
    final protected function isEmpty(string $name)
    {
        return empty($_COOKIE[$name]) OR !isset($_COOKIE[$name]);
    }
}