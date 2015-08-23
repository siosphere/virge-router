<?php
namespace Virge\Router\Service;

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
    public function render($template) {
        ob_start();
        include $template;
        $content = ob_get_contents();
        ob_end_clean();
        
        return new Response($content);
    }
}