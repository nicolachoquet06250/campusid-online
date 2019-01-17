<?php

/** @var ModuleLoader $loader */
$comptability_routes = $loader->get_util_route_parser()->parse_module_routes('comptabilite');
//var_dump($comptability_routes->get_routes());

//$comptability_routes->add_route('comptabilite', [
//	'comptabilite',
//	'task=comptabilite&action=index&type=json',
//]);

return $comptability_routes->get_routes();