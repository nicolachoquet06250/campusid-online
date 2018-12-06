<?php

/** @var ModuleLoader $loader */
$emailing_routes = $loader->get_util_route_parser()->parse_module_routes('emailing');

return $emailing_routes->get_routes();
