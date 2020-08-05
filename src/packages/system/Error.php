<?php
namespace Status\System;

use Status\Service\Logger;

/**
 * Class Error
 * @package Status\System
 */
final class Error
{
    public static $level = 6;
    /**
     * @var bool
     */
    public static $check = false;
    /**
     * @var string
     */
    public static $error = '';
    /**
     *
     */
    private static $errors = [
        400 => "",
        401 => "",
        403 => "",
        419 => "Invalid Token",
        500 => "",
        501 => "",
    ];
    /**
     *
     */
    public static $errHandler = [];
    /**
     * @return bool
     */
    public static function init(): bool
    {
        self::handler();

        self::shutdown();

        return true;
    }

    /**
     * @param \Throwable $throwable
     */
    public static function debug(\Throwable $throwable)
    {
        header("Content-type: text/html");
        Headers::setContentType();
        $debug = env('APP_DEBUG');
        Logger::make($throwable);
        self::$check = empty($debug) ? false : $debug;

        if(self::$check == false){
            self::deleteDir();
            self::off($throwable->getCode());
            return;
        }

        self::copyDir();
        self::view($throwable);
    }

    /**
     * @param $error
     */
    public static function setError($error)
    {
        self::$error = json_encode($error);
    }

    /**
     * @return string
     */
    public static function getError()
    {
        return self::$error;
    }

    /**
     * @param $info
     * @param int $code
     * @return mixed|string
     */
    public static function setInfo($info, int $code = 500)
    {
        header("Content-type: text/html");
        $debug = env('APP_DEBUG');
        Logger::make($info);
        self::$check = empty($debug) ? false : $debug;

        if(self::$check == false){
            self::deleteDir();
            self::off($code);
            return "error";
        }

        self::copyDir();
        return self::viewInfo($info);
    }

    /**
     *
     */
    public static function copyDir()
    {
        $debug = self::DIR_DEBUG();
        $resrc = self::DIR_RESRC();
        foreach ($debug as $k=>$v) {
            if(file_exists($v)) continue;
            mkdir($v);
            self::copyFile($resrc[$k], $v);
        }
    }

    /**
     * @param string $dirOld
     * @param string $dirNew
     */
    public static function copyFile(string $dirOld, string $dirNew)
    {
        $files = array_diff(scandir($dirOld), array('.','..'));
        foreach ($files as $k=>$v) {
            copy($dirOld.'/'.$v, $dirNew.'/'.$v);
        }
    }

    /**
     * @param \Throwable $throwable
     */
    public static function view(\Throwable $throwable)
    {
        include_once dirname(__DIR__, self::$level)."/factory/resource/views/debug.resource.php";
    }

    /**
     * @param \Throwable $throwable
     */
    public static function viewInfo($info)
    {
        return require(dirname(__DIR__, self::$level)."/factory/resource/views/shutdown.resource.php");
    }

    /**
     * @param int $code
     */
    public static function off(int $code)
    {
        header('HTTP/1.1 '.$code.' '.self::$errors[$code]);
        die(require(dirname(__DIR__, self::$level)."/factory/resource/views/error.resource.php"));
    }

    /**
     *
     */
    public static function deleteDir()
    {
        $debug = self::DIR_DEBUG();

        foreach ($debug as $k=>$v) {
            if(!file_exists($v)) continue;
            self::deleteFile($v);
            rmdir($v);
        }
    }

    /**
     * @param string $dir
     */
    private static function deleteFile(string $dir)
    {
        if(!file_exists($dir)) return;

        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $k=>$v) {
            unlink($dir.'/'.$v);
        }
    }

    /**
     * @return array
     */
    private static function DIR_DEBUG()
    {
        return [
            dirname(__DIR__, self::$level) . '/public/css/debug',
            dirname(__DIR__, self::$level) . '/public/js/debug',
        ];
    }

    /**
     * @return array
     */
    private static function DIR_RESRC()
    {
        return [
            dirname(__DIR__, self::$level) . '/factory/resource/css/bootstrap',
            dirname(__DIR__, self::$level) . '/factory/resource/js/bootstrap',
        ];
    }

    /**
     * 
     */
    public static function shutdown()
    {
        register_shutdown_function(function(){
            $error = error_get_last();

            if($error['type'] != 'NULL'){
                return;
            }
            Error::$handler = false;
            Error::setError($error);

            ob_start(function(){
                return Error::setInfo(Error::getError());
            });
        });
    }

    /**
     * error handler
     */
    public static function handler()
    {
        set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext){
            $info = [$errno, "message"=>$errstr, "file"=>$errfile, "line"=>$errline, "context"=>$errcontext];
            if($errno != 0){
                Error::setError($info);
                die(Error::setInfo(Error::getError()));
            } else {
                Error::setError($info);
                $info = Error::getError();
                Error::copyDir();
                echo Error::viewInfo($info);
                die;
            }

            return true;
        });
    }
}