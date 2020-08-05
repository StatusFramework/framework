<?php

namespace Status\Cookie;

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

    /**
     * Converter constructor.
     * @param $data
     */
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
     * @param string|NULL $key
     * @param bool $exception
     * @return mixed|string|null
     * @throws \Exception
     */
    public function toArray(string $key = NULL, bool $exception = true)
    {
        $array = json_decode($this->data, true);

        if (empty($key)) return $array;

        if (!array_key_exists($key, $array)) {
            if ($exception) {
                throw new \Exception('missing key in array', 500);
            } else {
                return NULL;
            }
        } else {
            return json_decode($this->data, true)[$param];
        }
    }

    /**
     * @param string|NULL $param
     * @param bool $exception
     * @return mixed|null
     * @throws \Exception
     */
    public function toJson(string $param = NULL, bool $exception = true)
    {
        $object = json_decode($this->data);

        if (!is_object($object)) {
            if ($exception) {
                throw new \Exception('incorrect cookie data', 403);
            } else {
                return NULL;
            }
        }

        if (empty($param)) return $object;

        if (!property_exists($object, $param)) {
            if ($exception) {
                throw new \Exception('missing property in the object', 500);
            } else {
                return NULL;
            }
        } else {
            return json_decode($this->data)->{$param};
        }
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->data);
    }
}