<?php
namespace Virge\Router\Service;

use Virge\Router\Component\Response;
use Virge\Router\Component\Response\Pipe;

/**
 * Used to pipeline a response to modify and then ultimately send the response
 */
class PipelineService
{
    protected $pipes = [];

    public function prepareResponse(Response $response)
    {
        foreach($this->pipes as $pipe) {
            $pipe->prepareResponse($response);
        }

        $response->send(false);
    }

    public function addPipe(Pipe $pipe)
    {
        $this->pipes[] = $pipe;

        return $pipe;
    }
}