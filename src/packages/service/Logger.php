<?php

namespace Status\Service;

/**
 * Class Logger
 * @package Status\Service
 */
final class Logger
{
    /**
     * @var string
     */
    private static $path = "../factory/storage/logs/errors/";
    private static $_title = "";

    /**
     * @param $data
     */
    public static function make($data)
    {
        $_data = '';

        if ($data instanceof \Throwable OR $data instanceof \Exception) {
            $_data = self::heredocThrowable($data);
        } else if (is_string($data)) {
            $_data = self::heredocString($data);
        } else if (is_array($data)) {
            $_data = self::heredocArray($data);
        }

        $_title = str_replace(" ", "_", substr(self::$_title, 0, 30));
        $data = null;

        file_put_contents(
            self::$path . self::rotation($_title),
            "time: " . date("H:i:s d.m.Y") . "\n" .
            self::information() . "\n" .
            $_data . "\n|--|--|--|--|--|--|--|--|--|--|--|--|--|--|--|\n",
            FILE_APPEND
        );
    }

    /**
     * @param \Throwable $data
     * @return string
     */
    private static function heredocThrowable(\Throwable $data)
    {
        //$trace = self::tracing($data);
        self::$_title = $data->getMessage();
        return <<<HEREDOC
message: {$data->getMessage()}
file: {$data->getFile()}
line: {$data->getLine()}
HEREDOC;
    }

    /**
     * @param string $data
     * @return string
     */
    private static function heredocString(string $data)
    {
        $data = json_decode($data, true);
        $message = self::$_title = $data['message'];
        $file = $data['file'];
        $line = $data['line'];

        return <<<HEREDOC
message: {$message}
file: {$file}
line: {$line}
HEREDOC;
    }

    /**
     * @param string $data
     * @return string
     */
    private static function heredocArray(string $data)
    {
        $data = implode("\n", $data);
        return <<<HEREDOC
{$data}
HEREDOC;
    }

    /**
     * @param \Throwable $data
     * @return string
     */
    private static function tracing(\Throwable $data)
    {
        $ex = explode("\n", $data->getTraceAsString());
        $_ex = '';
        foreach ($ex as $k => $v) {
            $_ex .= "Trace: $v\n";
        }

        return $_ex;
    }

    /**
     * @param string $filename
     * @return string
     */
    private static function rotation(string $filename)
    {
        $scan = array_diff(scandir(self::$path), ['.', '..']);
        $num = 0;
        $filename = quotemeta($filename);
        foreach ($scan as $k => $v) {
            if (preg_match("/^" . $filename . "/i", $v)) {
                $num++;
            }

            if (filesize(self::$path . $v) >= 1024 * 10) {
                $num++;
                break;
            }
        }

        if ($num == 0) {
            $num++;
        }

        $log = $filename . '_' . $num;
        return preg_replace("/[\'\"\\\]/", "_", $log) . '.log';
    }

    private static function information()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        $agent = $_SERVER["HTTP_USER_AGENT"];
        $status = $_SERVER["REDIRECT_STATUS"];
        $uri = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];
        $redirect = $_SERVER["REDIRECT_URL"];
        return <<<HEREDOC
ip: {$ip}
agent: {$agent}
status: {$status}
uri: {$uri}
method: {$method}
redirect: {$redirect}
HEREDOC;
    }

    /**
     * @param array $data
     */
    public static function client($data = ["Error Route",
        "IP: " . $_SERVER["REMOTE_ADDR"],
        "CLIENT: " . $_SERVER["HTTP_USER_AGENT"],
        "URL: " . $_SERVER['QUERY_STRING']
    ])
    {
        self::make($data);
    }
}