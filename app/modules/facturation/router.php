<?php

/** @var ModuleLoader $loader */
$facturation_routes = $loader->get_util_route_parser()->parse_module_routes('facturation');

return $facturation_routes->get_routes();