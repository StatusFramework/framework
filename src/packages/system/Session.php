<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 02.05.2019
 * Time: 13:18
 */

namespace Status\System;

use Status\Service\Utils;
use Status\Session\Creator;
use Status\Session\Reader;
use Status\Session\Rewriter;

/**
 * Class Session
 * @package Status\System
 */
final class Session
{
    /**
     * @var string
     */
    private static $path = "../factory/storage/session/";

    /**
     * @throws \Exception
     */
    public static function start()
    {
        (new Creator(self::$path))->make();
        return new self;
    }

    /**
     * @return \Status\Session\Converter|null
     * @throws \Exception
     */
    public static function getValue()
    {
        return (new Reader(self::$path))->make();
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public static function setValue(array $data)
    {
        (new Rewriter(self::$path))->make($data);
    }

    /**
     * @return string
     */
    public static function getPath(): string
    {
        return self::$path;
    }

    /**
     * удаление файлов сессии
     */
    public function clear()
    {
        $scan = array_diff(scandir(self::$path), ['.', '..']);
        foreach ($scan as $k => $filename) {
            if (!file_exists(self::$path.$filename)) continue;
            /*удаление сессии через 2 часа если нет изменений*/
            if(Utils::lifeTime(self::$path.$filename, env("SESSION_TIME"))){
                unlink(self::$path.$filename);
                break;
            }
        }
    }
}