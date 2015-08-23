<?php

/**
 * 
 * @author Michael Kramer
 */

class Response extends \Virge\Core\Model {
    
    protected $body;
    
    /**
     * @param string $body
     */
    public function __construct($body = null){
        $this->body = $body;
    }
    
    /**
     * Send the response
     */
    public function send() {
        $this->_sendHeaders();
        $this->_sendBody();
    }
    
    /**
     * Send out headers through
     */
    protected function _sendHeaders() {
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
}