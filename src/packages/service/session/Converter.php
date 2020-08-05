<?php

namespace Status\Session;
/**
 * Class Converter
 * @package Status\Cookie
 */
final class Converter
{
    /**
     * @var
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->data;
    }

    /**
     * @param string|NULL $param
     * @param bool $stringify
     * @return false|mixed|string
     */
    public function toArray(string $param = NULL, bool $stringify = false)
    {
        if (!$stringify) {
            return empty($param)
                ? json_decode($this->data, true)
                : json_decode($this->data, true)[$param];
        } else {
            return empty($param)
                ? json_decode(json_decode($this->data, true))
                : json_decode(json_decode($this->data, true)[$param]);
        }
    }

    /**
     * @param string|NULL $param
     * @param bool $stringify
     * @return mixed
     */
    public function toJson(string $param = NULL, bool $stringify = false)
    {
        if (!$stringify) {
            return empty($param)
                ? json_decode($this->data, false)
                : json_decode($this->data, false)->{$param};
        } else {
            return empty($param)
                ? json_decode(json_decode($this->data, false))
                : json_decode(json_decode($this->data, false)->{$param});
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return array_key_exists($key, $this->toArray());
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isEmpty(string $key): bool
    {
        return empty($this->toArray($key));
    }
}