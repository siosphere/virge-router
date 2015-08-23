<?php
namespace Virge\Router\Service;

use Virge\Routes;

use Virge\Router\Component\Request;
use Virge\Router\Component\Response;
use Virge\Router\Component\Route;

use Virge\Router\Exception\NotFoundException;
use Virge\Router\Exception\UnauthorizedAccessException;

/**
 * 
 * @author Michael Kramer
 */
class RouterService {
    
    /**
     * Holds available routes
     * @var array
     */
    protected $_hooks = [];
    
    /**
     * Route a web request
     */
    public function route() {
        $request = $this->_buildRequest();
        
        $route = $this->_getRoute($request->getURI());
        if(!$route) {
            throw new NotFoundException();
        }
        
        if(!$route->access()){
            throw new UnauthorizedAccessException();
        }
        
        $route->setActive(true);
        
        if(is_callable($route->getContoller())){
            return call_user_func($route->getController(), $request);
        }
        
        $controller = new $route->getController();
        $method = $route->getMethod();
        
        $response = call_user_func_array([$controller, $method], [$request]);
        
        if($response instanceof Response) {
            $response->send();
        } else {
            $response = new Response($response);
            $response->send();
        }
    }
    
    /**
     * Build the request object
     * @return Request
     */
    protected function _buildRequest() {
        $request = new Request();
        
        $request->setURI($_SERVER['REQUEST_URI']);
        
        $server = new Request\Server($_SERVER);
        $post = new Request\Post($_POST);
        $get = new Request\Get($_GET);
        
        $request->setServer($server);
        $request->setPost($post);
        $request->setGet($get);
        
        return $request;
    }
    
    /**
     * Get the controller that matches this route
     * @param string $uri
     * @return Route|null
     */
    protected function _getRoute($uri) {
        $uriParts = explode('/', $uri);
        
        $i = 0;
        $d = 0;
        $route = NULL;
        
        foreach ($uriParts as $part) {
            if ($i == 0) {
                $ident = $part;
            } else {
                $ident .= '/' . $part;
            }
            if(NULL !== ($temp = $this->getRouteFromPart($ident))){
                $route = $temp;
                $d = 0;
            } else {
                $d++;
            }
            $i++;
        }
        
        if(!$route) {
            return NULL;
        }
        
        return $route;
    }
    
    /**
     * Get route from string
     * @param string $part
     * @return Route
     */
    public function getRouteFromPart($part) {
        foreach(Routes::getRoutes() as $route){
            if($route->getUrl() == $part){
                return $route;
            }
        }
        
        return NULL;
    }
}