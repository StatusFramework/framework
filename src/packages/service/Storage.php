<?php
namespace Status\Service;

/**
 * Class Storage
 * @package Status\Service
 */
class Storage
{
    /**
     * @var string
     */
    private static $path = "../factory/storage/";

    /**
     * @param string $file
     * @param bool $dir
     * @return mixed|null
     */
    public static function read(string $file, bool $dir = true)
    {
        if($dir){
            $dir = self::$path;
        }

        if(!file_exists($dir.$file)){
            return NULL;
        }

        $fgc = file_get_contents($dir.$file);

        return json_decode($fgc);
    }

    /**
     * @param string $file
     * @param string $value
     * @param bool $path
     */
    public static function write(string $file, $value = '', bool $path = true)
    {
        if(is_array($value) OR is_object($value)){
            $value = json_encode((array)$value);
        }

        if($path){
            $path = self::$path;
        }

        file_put_contents($path.$file, $value);
    }

    /**
     * @param string $file
     * @return bool
     */
    public static function remove(string $file)
    {
        if(!file_exists(self::$path.$file)){
            return false;
        }

        unlink(self::$path.$file);
        return true;
    }
}