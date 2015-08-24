<?php
namespace Virge\Router\Service;

use Virge\Core\Config;

use Virge\Router\Component\Response;

/**
 * 
 * @author Michael Kramer
 */

class TemplateService {
    
    /**
     * Render the given file
     * @param string $template
     * @return Response
     */
    public function render($template, $parameters = array()) {
        
        foreach($parameters as $name => $value){
            $$name = $value;
        }
        
        ob_start();
        include $template;
        $content = ob_get_contents();
        ob_end_clean();
        
        return new Response($content);
    }
    
    /**
     * Include a component
     * @param string $template
     * @param array $parameters
     */
    public function component($template, $parameters = array()) {
        foreach($parameters as $name => $value){
            $$name = $value;
        }
        
        include Config::path($template);
    }
}