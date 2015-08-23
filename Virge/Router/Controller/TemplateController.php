<?php
namespace Virge\Router\Controller;

use Virge\Virge;
use Virge\Core\Config;
use Virge\Router\Component\Response;
use Virge\Router\Service\TemplateService;

/**
 * 
 * @author Michael Kramer
 */

class TemplateController {
    
    /**
     * 
     * @param string $filepath
     * @return Response
     */
    public function render($filepath) {
        return $this->getTemplatingService()->render(Config::path($filepath));
    }
    
    /**
     * @return TemplateService
     */
    public function getTemplatingService() {
        return Virge::service('templating');
    }
}