<?php
namespace Status\Session;

/**
 * Class Writer
 * @package Status\Session
 */
class Writer extends Founder
{
    private $cache = [];

    /**
     * Creator constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct($path);
    }

    /**
     * @param string $key
     * @param $value
     * @return Writer
     */
    final public function set(string $key, $value): Writer
    {
        $this->cache[$key] = $value;
        return $this;
    }

    /**
     * @param array $data
     * @return Writer
     */
    final public function setArray(array $data): Writer
    {
        $this->cache = array_merge($this->cache, data);
        return $this;
    }

    /**
     * @return Writer
     * @throws \Exception
     */
    final public function make(): Writer
    {
        $read = new Reader($this->path);
        $session = $read->getArray();

        foreach ($this->cache as $key => $value) {
            if(strtolower($key) == 'code'){
                continue;
            }
            $session[$key] = $value;
        }

        $this->createSession($this->path, $read->getName(), $session);
        return $this;
    }
}