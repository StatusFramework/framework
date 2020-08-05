<?php
namespace Status\Session;

/**
 * Class SReader
 * @package Status\Session
 */
class SReader
{
    private $path = '';

    /**
     * SReader constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function make()
    {
        return new self;
    }

    public function get()
    {
        return new self;
    }
}