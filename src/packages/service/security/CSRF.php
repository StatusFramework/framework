<?php
namespace Status\Security;

use Status\Core\Request;

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
     * @return bool
     * @throws \Exception
     */
    public function isAjax(): bool
    {
        return (new Request())->isAjax();
    }

    public function getVerifyToken()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getServerXToken(): string
    {
        return isset($_SERVER["HTTP_X_CSRF_TOKEN"]) ? $_SERVER["HTTP_X_CSRF_TOKEN"] : '';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function request(): string
    {
        $request = (new Request())->getArray();
        return array_key_exists("_csrf",$request) ? $request["_csrf"] : "";
    }
}