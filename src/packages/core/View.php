<?php

namespace Status\Core;

use Status\System\Headers;

/**
 * Class View
 * @package Status\Core
 */
final class View
{
    /**
     * @var null|View
     */
    private static $inst = NULL;
    /**
     * @var string
     */
    private static $viewName = '';
    /**
     * @var array
     */
    private static $params = [];

    /**
     * @param String $viewName
     * @return View|null
     * @throws \Exception
     */
    public static function make(String $name)
    {
        if (empty($name)) {
            throw new \Exception('views name missing', 500);
        }

        self::$viewName = preg_replace("/\.|\\|\_|\-/", '/', $name);

        self::$inst = new self;
        return self::$inst;
    }

    /**
     * @param mixed ...$args
     * @return View
     * @throws \Exception
     */
    public function params(Array $args): View
    {
        self::check();
        if (count(self::$params) !== 0) {
            throw new \Exception('parameters already set', 500);
        }
        self::$params = $args;
        return self::$inst;
    }

    /**
     * @param int $code
     * @return View|null
     * @throws \Exception
     */
    public function setStatusCode(int $code): View
    {
        self::check();
        http_response_code($code);
        return self::$inst;
    }

    /**
     * @param String $title
     * @param String $value
     * @return View
     * @throws \Exception
     */
    public function setHeaderText(String $title, String $value): View
    {
        if (strlen($title) < 1)
            throw new \Exception('text header is not set', 500);

        header("$title: $value");

        return self::$inst;
    }

    /**
     * @param int|NULL $code
     * @param String $text
     * @param bool $replace
     * @return View|null
     * @throws \Exception
     */
    public function setHeader(int $code = NULL, String $text = '', Bool $replace = true)
    {
        self::check();
        header('HTTP/1.1 ' . $code . ' ' . $text, $replace, $code);
        return self::$inst;
    }

    /**
     * @return String|NULL
     * @throws \Exception
     */
    public function return(): String
    {
        return self::getContents();
    }

    /**
     * @throws \Exception
     */
    public function echo(): void
    {
        echo self::getContents();
    }

    /**
     * @return String
     * @throws \Exception
     */
    private static function getContents(): String
    {
        ob_start();
        require_once('../app/views/' . self::$viewName . '.view.php');
        $content = ob_get_contents();
        ob_end_clean();
        return self::replacement($content);
    }

    /**
     * @throws \Exception
     */
    private static function check(): void
    {
        if (is_null(self::$inst)) {
            throw new \Exception('required object not found', 500);
        }
    }

    /**
     * @param String $text
     * @return string|string[]|void|null
     * @throws \Exception
     */
    private static function replacement(String $text)
    {
        $text = self::replacementViews($text);
        $text = self::replacementVars($text);
        $text = self::replacementFuncs($text);

        return $text;
    }

    /**
     * @param String $text
     * @return String
     */
    private static function replacementViews(String $text)
    {
        $viewPatterns = [
            'include' => '(?=\@include\(\'([a-zA-Z0-9\-\_\.]+)\'\))'
        ];

        foreach ($viewPatterns as $key => $value) {
            preg_match_all('/' . $value . '/i', $text, $matches);
            switch ($key) {
                case 'include':
                    $viewCache = [];
                    foreach ($matches[1] as $k => $v) {
                        $strr = str_replace('.', '/', $v);
                        $path = dirname(__DIR__, 6) . '/app/views/' . $strr . '.view.php';
                        if (!file_exists($path)) {
                            throw new \Exception("view not found [views/$strr.view.php]", 500);
                        }
                        if (!array_key_exists($v, $viewCache)) {
                            $viewCache[$v] = file_get_contents($path);
                            $text = preg_replace('/\@include\(\'' . $v . '\'\)/', $viewCache[$v], $text);
                        }
                    }
                    break;
            }
        }

        return $text;
    }

    private static function replacementVars(String $text)
    {
        foreach (self::$params as $k => $v) {
            if (!is_string($v) AND !is_numeric($v)) {
                throw new \Exception("only string or numeric must be in a pattern [return type: " . gettype($v) . "]", 500);
                return;
            }

            $text = preg_replace('/(\{\{\s*\\$?' . $k . '\s*\}\})/i', $v, $text);
        }

        /*variables*/
        /*
        preg_match('/\{\{\s*\\$?(?<arg>[^\}]+)+\s*\}\}/', $text, $matchesVars);

        if(isset($matchesVars[0]) AND count($matchesVars[0]) > 0)
            throw new \Exception('unknown variable <b>'.$matchesVars['arg'].'</b>', 500);
        */

        return $text;
    }

    /**
     * @param String $text
     * @return String|string[]|null
     */
    private static function replacementFuncs(String $text)
    {
        /*functions*/
        preg_match_all('/\{\{\s*(?<func>[a-z0-9\_]+)\((?<args>[^\}]*)\)\s*\}\}/i', $text, $matchesFuncs);

        foreach ($matchesFuncs[0] as $k => $v) {
            $func = $matchesFuncs["func"][$k];
            $args = isset($matchesFuncs["args"][$k])
                ? preg_replace("/^[\'\"](.*)[\'\"]$/", "$1", $matchesFuncs["args"][$k])
                : "";
            if (!function_exists($func)) {
                throw new \Exception('error calling function', 500);
            }
            $text = preg_replace(
                '/\{\{\s*' . $func . '\(' . $matchesFuncs["args"][$k] . '\)\s*\}\}/i',
                $func($args),
                $text
            );
        }
        return $text;
    }
}