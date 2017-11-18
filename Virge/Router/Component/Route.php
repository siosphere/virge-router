<?php
namespace Virge\Router\Component;

use Virge\Routes;

/**
 * 
 * @author Michael Kramer
 */

class Route extends \Virge\Core\Model {
    /**
     * Holds callables we need to check before this route becomes active
     * @var array
     */
    protected $before = array();

    /**
     * Set a before verify callable
     * @param callable $beforeVerify
     * @return \Virge\Router\Component\Route
     */
    public function before($beforeVerify) {
        $this->before[] = $beforeVerify;
        return $this;
    }

    /**
     * See if we can access this route
     * @return boolean
     */
    public function access(Request $request) {
        if (empty($this->before)) {
            return true;
        }
        
        foreach ($this->before as $before) {
            if (is_callable($before)) {
                if(!call_user_func($before, $request)) {
                    return false;
                }
                continue;
            }
            
            if(!Routes::before($before, $request)) {
                return false;
            }
        }
        
        return true;
    }
}