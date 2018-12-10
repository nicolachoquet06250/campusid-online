<?php

$loader = new ModuleLoader();

$modules = [];
$dir = opendir(__DIR__.'/../modules');
while (($module = readdir($dir)) !== false) {
	$routes_path = realpath(__DIR__.'/../modules/'.$module.'/router.php');
	if($routes_path && file_exists($routes_path)) {
		$modules[$module] = $routes_path;
	}
}

$global_routes = $loader->get_util_route_parser();
$global_routes->add_route(
	'documents/:pageId',
	[
		'documents/[pageId]/',
		'task=home&action=show&pageId=[pageId]'
	]
)->add_route(
	'error/:code',
	[
		'error/[code]/',
		'task=home&action=error&type=[code]',
		'code' => '([0-9]+)',
		'args' => [
			'code' => '[code]'
		]
	]
)->add_route(
	':task/:action',
	[
		'[task]/[action]/[params]',
		'task=[task]&action=[action]',
		'params'  => '(.*)',
		'_params' => [
			'[name]/[value]/',
			'[name]=[value]'
		]
	]
)->add_route(
	'default',
	[
		'[task]/[action]/[params]',
		'task=[task]&action=[action]',
		'params'  => '(.*)',
		'_params' => [
			'[name]/[value]/',
			'[name]=[value]'
		]
	]
);

foreach ($modules as $module => $module_path) require_once $module_path;

return [
	'https'           => false,
	'NAME_CONTROLLER' => 'home',    // Default Controller for router
	'NAME_METHOD'     => 'index',   // Default Action for router
	'publicWeb'       => '',        // Path for public web (web or public_html)

	'assets' => [
		'minifyCssEnabled' => true,
		'minifyJsEnabled'  => true,
		'assetsDir'        => 'assets',
		'assetsPath'       => APP_DIR.'web/',
		'cacheDir'         => 'cache',
		'cachePath'        => APP_DIR.'../web/',
		'cacheUrl'         => HTTP_HOST.'/',
	],

	'routes' => $global_routes->get_routes(),
];
