<?php
namespace Status\Cookie;

/**
 * Class Remover
 * @package Status\Cookie
 */
class Remover
{
    /**
     * @var int
     */
    private $time = 60*60*2;

    /**
     * @param string $name
     */
    public function make(string $name)
    {
        setcookie($name, null, time()-$this->time, '/');
    }
}