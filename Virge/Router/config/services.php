<?php

use Virge\Virge;

/**
 * Registers all given handlers with Virge that this Capsule contains
 * @author Michael Kramer
 */
Virge::registerService("router", "\\Virge\\Router\\Service\\RouterService");
Virge::registerService("templating", "\\Virge\\Router\\Service\\TemplateService");