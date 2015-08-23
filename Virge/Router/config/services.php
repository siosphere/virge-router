<?php

use Virge\Router\Capsule;

/**
 * Registers all given handlers with Virge that this Capsule contains
 * @author Michael Kramer
 */
Capsule::registerService("router", "\\Virge\\Router\\Service\\RouterService");