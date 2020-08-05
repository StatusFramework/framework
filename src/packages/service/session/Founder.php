<?php
namespace Status\Session;

use Status\Service\Utils;
use Status\System\Env;

/**
 * Class Founder
 * @package Status\Session
 */
class Founder
{
    /**
     * @var string
     */
    protected $prefix = 'sess_';

    /**
     * @var int
     */
    protected static $lengthCode = 64;

    /**
     * @var string|null
     */
    protected static $sessionCode = NULL;

    /**
     * @var string|null
     */
    protected static $checkCode = NULL;
    /**
     * @var float|int
     */
    protected $time = 6 * 60 * 60;
    /**
     * @var string
     */
    protected $path = '';

    /**
     * @param string $id
     * @return string
     */
    protected function sessionPath(string $id)
    {
        return $this->path.$this->prefix.$id;
    }

    /**
     * @param string $id
     * @return bool
     */
    protected function exists(string $id)
    {
        return file_exists($this->sessionPath($id));
    }

    /**
     * @param string $path
     * @param string $id
     * @return bool
     */
    protected function existsPath(string $path, string $id)
    {
        return file_exists($path.$this->prefix.$id);
    }

    /**
     * @param string $code
     * @return string
     */
    protected function getSessionID(string $code)
    {
        return md5(
            $code.$_SERVER["REMOTE_ADDR"].$_SERVER["HTTP_USER_AGENT"].Env::get("APP_KEY")
        );
    }

    /**
     * @return string
     */
    protected function getCode()
    {
        static::$sessionCode = is_null(self::$sessionCode)
            ? Utils::_code(self::$lengthCode)
            : self::$sessionCode;
        return self::$sessionCode;
    }

    /**
     * @return string
     */
    protected function getCheck()
    {
        static::$checkCode = is_null(self::$checkCode)
            ? Utils::_code(self::$lengthCode)
            : self::$checkCode;
        return self::$checkCode;
    }

    /**
     * @param array $data
     * @return false|string
     */
    protected function convert(array $data)
    {
        return json_encode($data);
    }

    /**
     * @return false|string
     */
    protected function open()
    {
        return file_get_contents($this->path.$this->prefix.$this->sessionID);
    }

    /**
     * @param string $path
     * @param string $data
     */
    protected function write(string $path, array $data)
    {
        file_put_contents($path, json_encode($data));
    }
}