<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 03.05.2019
 * Time: 15:17
 */

namespace Status\Session;

use Status\System\Session;

/**
 * Class Rewriter
 * @package Status\Session
 */
class Rewriter extends Founder
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
     * @var array
     */
    private $cache = [];

    /**
     * Rewriter constructor.
     * @param string $path
     * @throws \Exception
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->sessionID = Storage::getValue('session_id');
        $this->path = $this->sessionPath($this->sessionID);
        $this->cache = Session::getValue()->toArray();
    }

    /**
     * @param array $data
     */
    public function make(array $data)
    {
        $this->setCache($data);
        $this->write($this->path, $this->toArray());
    }

    /**
     * @param array $cache
     */
    private function setCache(array $cache)
    {
        $this->cache = array_merge($this->cache, $cache);
    }


    /**
     * @param string $key
     * @return mixed
     */
    public function getValue(string $key)
    {
        return $this->cache[$key];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->cache;
    }

    /**
     * @return array
     */
    public function toJson(): array
    {
        return json_encode($this->cache);
    }
}