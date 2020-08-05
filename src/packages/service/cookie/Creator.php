<?php
namespace Status\Cookie;

use Status\System\Env;
use Status\Cookie\Founder;

/**
 * Class Creator
 * @package Status\Cookie
 */
class Creator extends Founder
{
    /**
     * @var string|null
     */
    private $name = NULL;
    /**
     * @var int
     */
    private $time = 0;

    /**
     * @var bool
     */
    private $base64encode;

    /**
     * Creator constructor.
     * @param bool $base64encode
     */
    public function __construct(bool $base64encode = true)
    {
        $this->base64encode = $base64encode;
    }

    /**
     * @param string $name
     * @param string $data
     */
    public function make(string $name, string $data)
    {
        $this->name = $name;

        setcookie(
            $name,
            $this->base64encode ? base64_encode($data) : $data,
            time()+env("COOKIE_TIME"),
            '/',
            '',
            true,
            true
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }
}