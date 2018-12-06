<?php

/** @var ModuleLoader $loader */
$comptability_routes = $loader->get_util_route_parser()->parse_module_routes('comptabilite');

return $comptability_routes->get_routes();