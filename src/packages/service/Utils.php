<?php
namespace Status\Service;

/**
 * Class Utils
 * @package Status\Service
 */
final class Utils
{
    /**
     * @param array $output
     * @return array
     */
    public static function _walk(Array $output)
    {
        $result = [];
        array_walk_recursive($output, function ($item, $key) use (&$result) {
            $result[$key] = $item;
        });
        return $result;
    }

    /**
     * @param int $length
     * @return string
     */
    public static function _code(int $length = 32)
    {
        $chars = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';

        $code = '';
        for($i=0; $i<$length; $i++){
            $code .= $chars[rand(0,strlen($chars)-1)];
        }

        return $code;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public static function env(string $name)
    {
        if(!defined($name)){
            throw new \Exception("environment name not found", 500);
        }

        return constant($name);
    }

    /**
     * @param string $path
     * @param int $minutes
     * @return bool
     */
    public static function lifeTime(string $path, int $minutes): bool
    {
        return (filemtime($path) + $minutes*60 < microtime(true));
    }
}