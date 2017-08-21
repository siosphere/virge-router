<?php
namespace Virge\Router\Component;

use Virge\Router\Service\PipelineService;
use Virge\Virge;

/**
 * 
 * @author Michael Kramer
 */

class Response extends \Virge\Core\Model 
{
    protected $body;
    
    protected $headers = array();
    
    protected $status_code;
    
    /**
     * @param string $body
     */
    public function __construct($body = null, $status_code = 200){
        $this->body = $body;
        $this->status_code = $status_code;
    }
    
    /**
     * Send the response
     */
    public function send($usePipline = true) 
    {
        if($usePipline) {
            return $this->getPipelineService()->prepareResponse($this);
        }
        $this->_sendHeaders();
        $this->_sendBody();
    }
    
    /**
     * Send out headers through
     */
    protected function _sendHeaders() {
        http_response_code($this->status_code);
        if(empty($this->headers)) {
            $this->headers[] = 'Content-Type: text/html';
        }

        foreach($this->headers as $header) {
            header($header);
        }
    }
    
    /**
     * Return the body of our request
     */
    protected function _sendBody() {
        echo $this->getBody();
    }

    protected function getPipelineService() : PipelineService
    {
        return Virge::service(PipelineService::class);
    }
}