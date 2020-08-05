<?php
namespace Status\Core;

use Status\System\Env;

/**
 * Class Connection
 * @package Status\Core
 */
final class Connection
{
    /**
     * @var null|Connection
     */
    protected static $inst = NULL;
    /**
     * @var null|String
     */
    private static $database = NULL;
    /**
     * @var null|String
     */
    private static $host = NULL;
    /**
     * @var null|String
     */
    private static $port = NULL;
    /**
     * @var null|String
     */
    private static $user = NULL;
    /**
     * @var null|String
     */
    private static $password = NULL;
    /**
     * @var null|String
     */
    private static $name = NULL;
    /**
     * @var null|String
     */
    private static $charset = NULL;
    /**
     * @var null|\PDO
     */
    private static $pdo = NULL;

    /**
     * @throws \Exception
     */
    public static function start(): void
    {
        self::setConfig();
        if(!self::check()){
            self::$inst = NULL;
            return;
        }
        self::PDO();
    }

    /**
     * configure
     */
    private static function setConfig(): void
    {
        self::$database = env('DATABASE_CONN');
        self::$host     = env('DATABASE_HOST');
        self::$port     = env('DATABASE_PORT');
        self::$user     = env('DATABASE_USER');
        self::$password = env('DATABASE_PASS');
        self::$name     = env('DATABASE_NAME');
        self::$charset  = env('DATABASE_CHAR');
    }

    /**
     * @return bool
     */
    private static function check(): bool
    {
        return (empty(self::$name) || !is_null(self::$pdo)) ? false : true;
    }

    /**
     * @return string
     */
    private static function dns()
    {
        return self::$database.":host=".self::$host.";port=".self::$port.";dbname=".self::$name.";charset=".self::$charset;
    }

    /**
     *
     */
    private static function PDO()
    {
        self::$pdo = new \PDO(self::dns(), self::$user, self::$password);
    }

    /**
     * @return \PDO|null
     */
    public static function getPDO(): ?\PDO
    {
        return self::$pdo;
    }
}