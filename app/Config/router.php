<?php

$routes = [
	'documents/:pageId' => [
		'documents/[pageId]/',
		'task=page&action=show&pageId=[pageId]'
	],

	'error/:code' => [
		'error/[code]/',
		'task=page&action=error&type=[code]',
		'code' => '([0-9]+)',
		'args' => [
			'code' => '[code]'
		]
	],

	':task/:action' => [
		'[task]/[action]/[params]',
		'task=[task]&action=[action]',
		'params'  => '(.*)',
		'_params' => [
			'[name]/[value]/',
			'[name]=[value]'
		]
	],

	'default' => [
		'[task]/[action]/[params]',
		'task=[task]&action=[action]',
		'params'  => '(.*)',
		'_params' => [
			'[name]/[value]/',
			'[name]=[value]'
		]
	]
];

$dir = opendir(__DIR__.'/../modules');
while (($module = readdir($dir)) !== false) {
	$routes_path = realpath(__DIR__.'/../modules/'.$module.'/router.php');
	if($routes_path && file_exists($routes_path)) {
		$_routes = include $routes_path;
		$routes = array_merge($routes, $_routes);
	}
}

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

	'routes' => $routes,
];
