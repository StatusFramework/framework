<?php
namespace Status\Cookie;

use Status\Cookie\Founder;
use Status\Cookie\Converter;

/**
 * Class Reader
 * @package Status\Cookie
 */
class Reader extends Founder
{
    /**
     * @var bool
     */
    private $base64decode = true;
    /**
     * @var
     */
    private $data;

    public function __construct(bool $base64decode = true)
    {
        $this->base64decode = $base64decode;
    }

    /**
     * @param string $name
     * @return \Status\Cookie\Converter
     */
    public function make(string $name): Converter
    {
        $data = $this->isEmpty($name) ? '' : (
            $this->base64decode ? base64_decode($_COOKIE[$name]) : $_COOKIE[$name]
        );

        return new Converter($data);
    }
}