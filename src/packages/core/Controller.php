<?php

namespace Status\Core;

use Status\Core\Performer\ReflectionParamsController;
use Status\Service\Type;
use Status\System\Error;
use Status\System\Headers;

/**
 * Class Controller
 * @package Status\Core
 */
final class Controller
{
    /**
     * @var string
     */
    private $controller = '';
    /**
     * @var string
     */
    private $method = '';
    /**
     * @var array
     */
    private $args = [];
    /**
     * @var array
     */
    private $values = [];
    /**
     * @var string
     */
    private $dir = 'App\\Controllers\\';

    /**
     * Controller constructor.
     * @param String $controller
     * @param String $method
     * @param array $args
     * @param array $values
     */
    public function __construct(String $controller, String $method, Array $args, Array $values)
    {
        $this->controller = $controller;
        $this->method = $method;
        $this->args = $args;
        $this->values = $values;
    }

    /**
     * @throws \ReflectionException
     */
    public function start()
    {
        $this->injectClass();

        $controller = $this->dir . $this->controller;

        $refClass = new \ReflectionClass($controller);

        if (empty($refClass)) {
            throw new \Exception('controller not found', 500);
        }

        $refMethod = new \ReflectionMethod($controller, $this->method);

        $getPrm = $refMethod->getParameters();

        $refArgs = [];

        $cArg = count($this->args);

        $cPrm = count($getPrm);

        for ($i = $cArg; $i < $cPrm; $i++) $refArgs = array_merge($getPrm);

        $refParams = new ReflectionParamsController($refArgs);

        $this->args = array_merge($this->args, $refParams->getParameters());
        $this->values = array_merge($this->values, $refParams->getParameters());

        if (count($refMethod->getParameters()) !== count($this->args)) {
            throw new \Exception('required arguments not found in method', 500);
        }

        $this->echoContent($refMethod, $controller, $this->values);
    }

    /**
     * @throws \Exception
     */
    private function injectClass()
    {
	$controller = $this->dir . $this->controller;

	preg_match_all("/[a-z_0-9]+/i", $controller, $match);

	$str = '';
	
	for($i = 0, $c = count($match[0]); $i < $c; $i++){
		if($i < $c - 1){
			$str .= strtolower($match[0][$i]) . '/';
			continue;
		}	
		$str .= $match[0][$i];
	}

	$file = str_replace("\\", "/", dirname(__DIR__, 6) . '/' . $str) . ".php";

        if (!file_exists($file)) {
            throw new \Exception('controller not found', 500);
        }

        require_once $file;
    }

    /**
     * @param \ReflectionMethod $refMethod
     * @param string $controller
     * @param $value
     * @throws \Exception
     */
    private function echoContent(\ReflectionMethod $refMethod, string $controller, $value)
    {
        $this->obStart();

        $refMethodContent = $refMethod->invokeArgs(new $controller, $value);

        if(!(new Type($refMethodContent,Type::STR,Type::INT,Type::DOUBLE,Type::FLOAT,Type::NULL))->check()){
            throw new \Exception("incorrectly formed content (must be displayed as a string, numbers or null)", 500);
        }

        echo $refMethodContent;

        $this->obEnd();
    }

    /**
     * ob_start
     */
    private function obStart()
    {
        ob_start(function ($buffer) {
            preg_match("/(?'head'\<head[^>]*>)(?'chead'[\s\D\d]*)<\/head>\s*" .
                "(?'body'\<body[^>]*>)(?'cbody'[\s\D\d]*)\<\/body>/i",
                $buffer,
                $matcher
            );

            if (Headers::getContentType()) {
                return $buffer;
            }

            $chead = (!isset($matcher["chead"]) OR empty($matcher["chead"]))
                ? '' : $matcher["chead"];
            $cbody = (!isset($matcher["cbody"]) OR empty($matcher["cbody"]))
                ? $buffer : $matcher["cbody"];

            return "<!DOCTYPE html>\n<html>\n\t<head>" .
                $chead . "\n\t</head>\n\t<body>" . $cbody .
                "\n\t</body>\n</html>";
        });
    }

    /**
     * ob_end_flush
     */
    private function obEnd()
    {
        ob_end_flush();
    }
}