<?php
namespace Status\Session;

use Status\Session\Converter;

/**
 * Class Reader
 * @package Status\Session
 */
final class Reader extends Founder
{
    /**
     * @var string
     */
    protected $path = '';
    /**
     * @var string
     */
    protected $sessionID = '';

    /**
     * Reader constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->sessionID = Storage::getValue('session_id');
    }

    /**
     * @return \Status\Session\Converter|null
     * @throws \Exception
     */
    public function make(): ?Converter
    {
        if(empty($this->sessionID))
        {
            throw new \Exception('session id not found', 403);
        }

        if($this->existsPath($this->path, $this->sessionID))
        {
            return new Converter(
                $this->open()
            );
        }

        return NULL;
    }
}