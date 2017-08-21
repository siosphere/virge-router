<?php

use Virge\Router\Service\{
    PipelineService,
    RouterService,
    TemplateService
};
use Virge\Virge;

/**
 * Registers all given handlers with Virge that this Capsule contains
 * @author Michael Kramer
 */

Virge::registerService(PipelineService::class, PipelineService::class);
Virge::registerService("router", RouterService::class);
Virge::registerService("templating", TemplateService::class);