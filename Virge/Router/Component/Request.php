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
    
}