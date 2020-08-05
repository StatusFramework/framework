<?php
/**
 * Class CSRF
 * @package Status\Security
 */
class CSRF
{
    /**
     * @var string
     */
    private $method = '';
    /**
     * @var string
     */
    private $methods = ['GET','HEAD','OPTIONS','TRACE'];
    /**
     * CSRF constructor.
     */
    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return bool
     */
    public function isSafe(): bool
    {
        return in_array($this->method, $this->methods);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        if(isset(getallheaders()["X-CSRF-TOKEN"])){
            return getallheaders()["X-CSRF-TOKEN"];
        }
        else if(isset($GLOBALS['_'.$this->method]["_csrf"]))
        {
            return $GLOBALS['_'.$this->method]["_csrf"];
        }
        else {
            return '';
        }
    }

    /**
     * @return bool
     */
    public function isAjax(): bool
    {
        return 'XMLHttpRequest' == ( getallheaders()["X-Requested-With"] ?? NULL );
    }
}