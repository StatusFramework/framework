<?php
namespace Status\Core;

use Status\Core\Performer\SearchRoute;
use Status\Core\Specifier\RouteInterface;
use Status\System\Error;

/**
 * Class Route
 * @package Status\Framework\Core
 */
class Route implements RouteInterface
{
    /**
     * @var bool
     */
    protected static $init = false;
    /**
     * @var array
     */
    private static $request = [];
    /**
     * @var array
     */
    private static $routes = [];
    /**
     * @var string
     */
    private static $rURL = '';
    /**
     * @var string
     */
    private static $controller = '';
    /**
     * @var string
     */
    private static $method = '';
    /**
     * @var array
     */
    private static $args = [];
    /**
     * @var array
     */
    private static $values = [];

    /**
     * @param String $redirect_url
     * @param String $controller_method
     * @throws \Exception
     */
    final public static function get(String $redirect_url, String $controller_method): void
    {
        self::setDataRoutes('get', $redirect_url, $controller_method);
    }

    /**
     * @param String $redirect_url
     * @param String $controller_method
     * @throws \Exception
     */
    final public static function post(String $redirect_url, String $controller_method): void
    {
        self::setDataRoutes('post', $redirect_url, $controller_method);
    }

    /**
     * @param String $redirect_url
     * @param String $controller_method
     * @throws \Exception
     * @return void
     */
    final public static function delete(String $redirect_url, String $controller_method, array $validate = []): void
    {
        self::setDataRoutes('delete', $redirect_url, $controller_method);
    }

    /**
     * @param String $redirect_url
     * @param String $controller_method
     * @throws \Exception
     * @return void
     */
    final public static function put(String $redirect_url, String $controller_method): void
    {
        self::setDataRoutes('put', $redirect_url, $controller_method);
    }

    /**
     * @param String $method
     * @param String $uri
     * @param String $controller_method
     * @throws \Exception
     */
    final protected static function setDataRoutes(String $method, String $uri, String $controller_method)
    {
        self::checkArgsRoutes([
            'method'            => $method,
            'uri'               => $uri,
            'controller_method' => $controller_method,
        ]);
        self::$routes[] = [
            'method'         => $method,
            'uri'            => $uri,
            'dataController' => $controller_method
        ];
    }

    /**
     * @param array $args
     * @throws \Exception
     * @return void
     */
    final protected static function checkArgsRoutes(Array $args)
    {
        if(empty($args['method']))
            throw new \Exception('no data transfer http method specified', 500);
        if(empty($args['uri']))
            throw new \Exception('no data transfer url specified', 500);
        if(empty($args['controller_method']))
            throw new \Exception('no data transfer controller or method specified', 500);
    }

    /**
     * First method
     * @throws \Exception
     * @return void
     */
    final public static function init()
    {
        self::checkInit();
        self::setInit();
    }

    /**
     * @throws \Exception
     * @return void
     */
    private static function checkInit()
    {
        if(self::$init)
            throw new \Exception('re-initialization of the route object is prohibited', 500);
    }

    /**
     * @return void
     */
    private static function setInit()
    {
        self::$init = true;
    }

    /**
     * First method
     * @throws \Exception
     * @return void
     */
    final public static function start()
    {
        self::checkInitRoute();
        self::setURL();
        self::setRequest();
        self::searchExistRoute();
        self::checkRoutes();
        self::compareController();
        self::initController();
    }

    /**
     * @throws \Exception
     * @return void
     */
    private static function checkInitRoute()
    {
        if(!self::$init){
            throw new \Exception('route object not initialized', 500);
        }
    }

    /**
     * set rURL
     * check this code every time
     */
    final protected static function setURL()
    {
        if(isset($_SERVER['REDIRECT_URL']))
        {
            self::$rURL = htmlentities(rawurldecode($_SERVER['REDIRECT_URL']));
        }
        else
        {
            preg_match('/^[^\\?\\#]+/i', $_SERVER['REQUEST_URI'], $match);
            self::$rURL = htmlentities(rawurldecode($match[0]));
        }
    }

    /**
     * Request
     */
    final protected static function setRequest()
    {
        preg_match_all(
            "/\/([^\/]+)|\//i",
            self::$rURL,
            self::$request
        );
    }

    /**
     * @throws \Exception
     */
    private static function searchExistRoute()
    {
        self::$routes = (new SearchRoute(
            self::$rURL,
            self::$request,
            self::$routes
        ))->getResult();
    }

    /**
     * @throws \Exception
     */
    private static function checkRoutes()
    {
        if(empty(self::$routes['method']))
            throw new \Exception('no data transfer method specified', 500);
        if(empty(self::$routes['uri']))
            throw new \Exception('no data transfer url specified', 500);
        if(empty(self::$routes['dataController']))
            throw new \Exception('no data transfer controller specified', 500);
    }

    /**
     * @throws \Exception
     */
    private static function compareController(): void
    {
        $matches = [];

        preg_match_all(
            '/^([a-z\\\]*[a-z]+[a-z0-9]+)::([a-z]+[a-z0-9]+)$/i',
            self::$routes['dataController'],
            $matches
        );

        self::checkParams($matches, self::$routes);
        self::setParams($matches, self::$routes);
    }

    /**
     * @param array $matches
     * @param array $routes
     * @throws \Exception
     */
    private static function checkParams(Array $matches, Array $routes)
    {
        if(empty($matches[1][0]) OR empty($matches[2][0])){
            throw new \Exception('no data about the controller and its method', 500);
        }

        if($matches[1][0].'::'.$matches[2][0] !== self::$routes['dataController']){
            throw new \Exception('controller data is incorrect [incorrect: '.$matches[1][0].'::'.$matches[2][0].']', 500);
        }

        if(!array_key_exists('args', $routes)){
            self::$routes['args'] = [];
        }

        if(!array_key_exists('value', $routes)){
            self::$routes['value'] = [];
        }
    }

    /**
     * @param array $matches
     * @param array $routes
     */
    private static function setParams(Array $matches, Array $routes)
    {
        self::$controller = (string)$matches[1][0];
        self::$method = (string)$matches[2][0];
        self::$args = (array)$routes['args']['name'];
        self::$values = (array)$routes['args']['value'];

    }

    /**
     * @throws \ReflectionException
     */
    private static function initController()
    {
        (new Controller(self::$controller, self::$method, self::$args, self::$values))->start();
    }

    /*******************************************/

    /**
     * @param array $routes
     * @return array
     */
    private static function setUniqueRoute(array $routes): array
    {
        return array_unique($routes,SORT_REGULAR);
    }
}