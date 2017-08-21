<?php
namespace Virge\Router\Service;

use Virge\Routes;

use Virge\Router\Component\Request;
use Virge\Router\Component\Response;
use Virge\Router\Component\Response\Pipe;
use Virge\Router\Component\Route;

use Virge\Router\Exception\NotFoundException;
use Virge\Router\Exception\UnauthorizedAccessException;
use Virge\Virge;

/**
 * 
 * @author Michael Kramer
 */
class RouterService {
    
    /**
     * Route a web request
     */
    public function route($uri = null) {
        
        $request = $this->_buildRequest($uri);
        Routes::setRequest($request);
        
        foreach(Routes::getResolvers() as $resolverConfig) {
            $resolver = $resolverConfig['resolver'];
            
            if(is_string($resolver) && Virge::service($resolver)) {
                $resolver = Virge::service($resolver);
            }
            
            $method = $resolverConfig['method'];
            if(false !== ($response = call_user_func_array( array( $resolver, $method), array($request) ))) {
                return $this->sendResponse($response);
            }
        }
        
        $route = $this->_getRoute($request->getURI());
        if(!$route) {
            throw new NotFoundException();
        }
        
        if(!$route->access()){
            throw new UnauthorizedAccessException();
        }
        
        $route->setActive(true);
        
        if(is_callable($route->getController())){
            return $this->sendResponse(call_user_func($route->getController(), $request));
        }

        $controllerClassname = $route->getController();
        $controller = new $controllerClassname;
        $method = $route->getMethod();
        
        $this->sendResponse(call_user_func_array(array($controller, $method), array($request)));
    }

    protected function sendResponse($response)
    {
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
    protected function _buildRequest($uri = null) {
        $request = new Request();
        
        $request->setURI(!$uri ? $this->_getServerURI() : $uri);
        
        $jsonBody = json_decode(file_get_contents("php://input"), true);
        if($jsonBody !== false) {
            //set request body
            $request->setJson(new Request\Json($jsonBody));
        }
        
        $server = new Request\Server($_SERVER);
        $post = new Request\Post($_POST);
        $get = new Request\Get($_GET);
        $files = new Request\Files($_FILES);
        
        $request->setServer($server);
        $request->setPost($post);
        $request->setGet($get);
        $request->setFiles($files);
        
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
    
    /**
     * Return from the server URL
     * @return string|null
     */
    protected function _getServerURI() {
        if(!isset($_SERVER) || !isset($_SERVER['REQUEST_URI'])){
            return NULL;
        }
        
        $url = parse_url($_SERVER['REQUEST_URI']);
        if(!$url) {
            return NULL;
        }
        
        return substr($url['path'], 1);
    }
}