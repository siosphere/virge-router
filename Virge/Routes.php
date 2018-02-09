<?php
namespace Virge;

use Virge\Router\Component\{
    Request,
    Response\Pipe,
    Route
};
use Virge\Router\Service\PipelineService;
use Virge\Virge;

/**
 * 
 * @author Michael Kramer
 */
class Routes 
{
    
    protected static $_routes = array();
    
    protected static $resolvers = array();
    
    protected static $before = array();
    
    public static $useSecure = false;

    protected static $request = null;

    public static function getRequest()
    {
        return static::$request;
    }

    public static function setRequest(Request $request)
    {
        static::$request = $request;
    }

    public static function resolver($resolver, $method, $priority = 999) {
        while (isset(self::$resolvers[$priority])) {
            $priority++;
        }

        self::$resolvers[$priority] = array(
            'resolver' => $resolver,
            'method' => $method
        );
    }

    public static function getResolvers() {
        return self::$resolvers;
    }

    /**
     * Add a url to the router
     * @param string $url
     * @param string|callable $controller
     * @param string $method
     * @param array $params
     * @return Route
     */
    public static function add($url, $controller, $method = 'run', $params = array()) {
        return self::$_routes[] = new Route(array(
            'url'           => $url,
            'controller'    => $controller,
            'method'        => $method,
            'params'        => $params
        ));
    }

    public static function pipe(Pipe $pipe) : Pipe
    {
        return self::getPipelineService()->addPipe($pipe);
    }

    /**
     * Run a before callable function
     * @param string $name
     * @return boolean
     */
    public static function before($name, Request $request) {
        if (isset(self::$before[$name])) {
            $func = self::$before[$name];
            return $func($request);
        }
        return false;
    }

    /**
     * Add a before callable function
     * @param string $name
     * @param callable $callable
     */
    public static function addBefore($name, $callable) {
        self::$before[$name] = $callable;
    }
    
    /**
     * Get our available routes
     * @return array
     */
    public static function getRoutes() {
        return self::$_routes;
    }

    protected static function getPipelineService() : PipelineService
    {
        return Virge::service(PipelineService::class);
    }
    
}