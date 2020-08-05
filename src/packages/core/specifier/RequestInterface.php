<?php
namespace Status\Core\Specifier;

/**
 * Interface RequestInterface
 * @package Status\Core\Specifier
 */
interface RequestInterface
{
    public function getMethod();
    public function getArray();
    public function getJson();
    public function getObject();
    public function getValue($key = NULL);
}