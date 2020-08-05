<?php
namespace Status\System;

use Symfony\Component\Console\Application;

/**
 * Class Console
 * @package Status\System
 */
final class Console
{
    /**
     * @var bool
     */
    private static $run = false;
    /**
     * @var string
     */
    private static $namespace = 'Status\\Command\\';
    /**
     * @var bool
     */
    private static $path = '/../command/';
    
    /**
     * @throws \Exception
     */
    public static function run()
    {
        self::check();
        self::find();
        self::appalication();
    }

    /**
     * @throws \Exception
     */
    private static function check()
    {
        if(self::$run)
            throw new \Exception("console class has already been started");

        self::$run = true;
    }

    /**
     * @throws \Exception
     */
    private static function appalication()
    {
        $app = new Application();

        foreach (self::find() as $key => $value){
            $value = preg_replace('/\\.[^.\\s]{3,4}$/', '', $value);

            if(!class_exists($value))
                throw new \Exception("command class not found");

            $app->add(new $value);
        }

        $app->run();
    }

    /**
     * @return array
     * @throws \Exception
     */
    private static function find()
    {
        if(!file_exists(__DIR__.self::$path))
            throw new \Exception("command directory not found");

        $scan = array_diff(scandir(__DIR__.self::$path), ['.', '..']);

        $result = [];

        foreach ($scan as $key => $value){
            $result[] = self::$namespace.$value;
        }

        return $result;
    }
}
