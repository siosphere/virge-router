<?php
namespace Virge\Router\Component\Response;

use Virge\Router\Component\Response;
use Virge\Router\Service\PipelineService;
use Virge\Virge;

abstract class Pipe
{
    abstract public function prepareResponse(Response $response);

    public function pipe(Pipe $pipe) {

    }

    protected function getPipelineService() : PipelineService
    {
        return Virge::service(PipelineService::class);
    }
}