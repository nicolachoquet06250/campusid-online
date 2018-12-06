<?php

/** @var ModuleLoader $loader */
$home_routes = $loader->get_util_route_parser()->parse_module_routes('home');

$home_routes->add_route('home', [
	'home',
	'task=home&action=responseExample&type=html',
]);

return $home_routes->get_routes();