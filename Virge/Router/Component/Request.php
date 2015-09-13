<?php
namespace Virge\Router\Component;

/**
 * 
 * @author Michael Kramer
 */
class Request extends \Virge\Core\Model {
    
    /**
     * Get a url part based on the preceding label
     * @param string $param
     */
    public function getUrlParam($param){
        $url = explode('/', $this->getURI());
        $value = null;
        $bNext = false;
        foreach($url as $piece){
            if($bNext){
                $value = $piece;
                break;
            }
            if($piece == $param){
                $bNext = true;
            }
        }
        return $value;
    }
    
    /**
     * attempt to return the value from the current request method or json body
     * if available
     * @param string $key
     * @param mixed $defaultValue
     */
    public function get($key, $defaultValue = null) {
        switch($this->getServer()->get('REQUEST_METHOD')) {
            case 'GET':
            case 'DELETE':
                $value = $this->getGet()->get($key);
                break;
            case 'POST':
            case 'PUT':
                $value = $this->getPost()->get($key);
                break;
        }
        
        if($this->getJson()) {
            $jsonValue = $this->getJson()->get($key);
            $value = $jsonValue !== null ? $jsonValue : $value;
        }
        
        if($value === null) {
            return $defaultValue;
        }
        
        return $value;
    }
}