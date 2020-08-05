<?php

namespace Status\Core;

use Status\Core\Specifier\RequestInterface;
use Status\Session\Reader;
use Status\System\Session;

/**
 * Class Request
 * @package Status\Core
 */
final class Request implements RequestInterface
{
    /**
     * @var string
     */
    private $method;
    /**
     * @var array|null
     */
    private $request;

    /**
     * Request constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->method = $this->setMethod();
        $this->request = $this->setRequest();
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return (string)$this->method;
    }

    /**
     * @return array|null
     */
    public function getArray(): ?array
    {
        return (array)$this->request;
    }

    /**
     * @return string|null
     */
    public function getJson(): ?string
    {
        return (is_array($this->request)) ? json_encode($this->request) : NULL;
    }

    /**
     * @return object|null
     */
    public function getObject(): ?object
    {
        return (is_array($this->request)) ? (object)$this->request : NULL;
    }

    /**
     * @return \Status\Session\Converter|null
     * @throws \Exception
     */
    public function session()
    {
        return (new Reader(Session::getPath()))->make();
    }

    /**
     * @return bool
     */
    public function isAjax(): bool
    {
        return 'XMLHttpRequest' == ($_SERVER["HTTP_X_REQUESTED_WITH"] ?? NULL);
    }

    /**
     * @param null $key
     * @return mixed|null
     */
    public function getValue($key = NULL)
    {
        if (is_null($key)) return NULL;

        if (is_string($key) AND array_key_exists($key, $this->getArray())) {
            return  $this->getArray()[$key];
        }

        return NULL;
    }

    /**
     * @return string
     */
    private function setMethod(): string
    {
        return (string)$_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    private function setRequest(): ?array
    {
        return $this->isMethod() ? $GLOBALS['_' . $this->method] : NULL;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function isMethod(): bool
    {
        if (is_null($this->method)) {
            throw new \Exception('request method is null', 501);
        }

        return true;
    }
}