<?php
namespace Status\Service;

/**
 * Class Hash
 * @package Status\Service
 */
final class Hash
{
    /**
     * @param string $algo
     * @param bool $crypt
     * @return bool|string
     */
    public static function set(string $algo = 'sha512', bool $crypt = true)
    {
        if($crypt)
        {
            return substr(crypt(
                $_SERVER['REMOTE_ADDR'].env("APP_KEY"),
                '$1$1'
            ), 0 , 40);
        }
        else {
            return hash(
                $algo,
                $_SERVER['REMOTE_ADDR'].
                env("APP_KEY").
                $_SERVER['HTTP_USER_AGENT']);
        }
    }

    /**
     * @param string $data
     * @param string $algo
     * @return string
     */
    public static function value(string $data, string $algo = 'sha512')
    {
        return hash($algo, $data);
    }
}