<?php
namespace Status\Core\Performer;

use Status\Service\Logger;

/**
 * Class SearchRoute
 * @package Status\Core\Performer
 */
final class SearchRoute
{
    /**
     * @var array
     */
    private $request = [];
    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var string
     */
    private $link = '';

    /**
     * SearchRoute constructor.
     * @param string $link
     * @param $request
     * @param array $routes
     * @throws \Exception
     */
    public function __construct(string $link, array $request, array $routes)
    {
        $this->link = $link;
        $this->routes = $routes;
        $this->request = $request;
        $this->start();
    }

    /**
     * @throws \Exception
     */
    private function start()
    {
        $this->methodSelection();
        $this->linkSelection();
        $this->majorSelection();
        $this->checkRoute();
        $this->checkArgs();
    }

    /**
     *
     */
    private function methodSelection()
    {
        foreach ($this->routes as $key => $value) {
            if (strtolower($value['method']) !== strtolower($_SERVER['REQUEST_METHOD'])) {
                unset($this->routes[$key]);
                continue;
            }
        }
    }

    /**
     *
     */
    private function linkSelection()
    {
        foreach ($this->routes as $key => $value) {
            if (strtolower($value["uri"]) === strtolower($this->link)) {
                $this->routes = []; //clean array routes
                $this->routes[] = $value; //new data for array routes
                break;
            }
        }
    }

    /**
     *
     */
    private function majorSelection()
    {
        $result = [];

        foreach ($this->routes as $key => $value) {
            /**
             * identical links. stop searching.
             */
            if (strtolower($value["uri"]) === strtolower($this->link)) {
                $result[] = array_merge($this->routes[$key], ['args' => ['name' => [], 'value' => []]]);
                break;
            }
            /**
             * parsing links into parts
             */
            $parse = (new ParseLinkRoute($value['uri']))->getResult();
            /**
             * if there is not enough request data, ignore the link
             */
            if (count($parse[0]) != count($this->request[0])) continue;

            /**
             * combine data for comparison
             */
            $redirect = $this->searchRedirect($parse, $key);
            /**
             * recheck collected link
             */
            if ($redirect === $this->link) {
                $result[] = $this->routes[$key];
                unset($redirect);
                break;
            }
        }

        $this->routes = $result;
    }

    /**
     * @param array $uri
     * @param int $key
     * @return string
     */
    private function searchRedirect(array $uri, int $key): string
    {

        $cPath = count($uri['path']);
        $redirect = '';

        for ($i = 0; $i < $cPath; $i++) {
            if (empty($uri["path"][$i]) AND $uri["path"][$i] != 0 AND !array_key_exists('args', $uri)) {
                $redirect = '';
                break;
            } else if (empty($uri["path"][$i]) AND $uri["path"][$i] != 0 AND !empty($uri["args"][$i])) {
                $redirect .= '/' . $this->request[1][$i];
                $this->routes[$key]['args']['value'][] = (string)$this->request[1][$i];
                $this->routes[$key]['args']['name'][] = (string)$uri["args"][$i];
                continue;
            } else if (!empty($uri["path"][$i]) AND empty($uri["args"][$i]) AND $uri["args"][$i] != 0) {
                $redirect .= '/' . $uri["path"][$i];
                continue;
            }
        }

        return $redirect;
    }

    /**
     * Only Linux Ubuntu
     * @throws \Exception
     */
    private function checkRoute()
    {
        if (empty($this->routes) OR !is_array($this->routes) OR count($this->routes) !== 1){
			@shell_exec("sudo iptables -I INPUT -s ".$_SERVER["REMOTE_ADDR"]." -j DROP");
			Logger::client();
			throw new \Exception("route or url not found", 403);
		}
            
    }

    /**
     * @throws \Exception
     */
    private function checkArgs()
    {
        if (!array_key_exists('args', $this->routes[0]))
            throw new \Exception("error in creating an array of arguments [args]", 500);
        if (!array_key_exists('name', $this->routes[0]['args']))
            throw new \Exception("error in creating an array of arguments [name]", 500);
        if (!array_key_exists('value', $this->routes[0]['args']))
            throw new \Exception("error in creating an array of arguments [value]", 500);
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->routes[0];
    }
}