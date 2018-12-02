<?php

return [
	'https'           => false,
	'NAME_CONTROLLER' => 'page',    // Default Controller for router
	'NAME_METHOD'     => 'index',       // Default Action for router
	'publicWeb'       => '',              // Path for public web (web or public_html)

	'assets' => [
		'minifyCssEnabled' => true,
		'minifyJsEnabled'  => true,
		'assetsDir'        => 'assets',
		'assetsPath'       => APP_DIR.'View/',
		'cacheDir'         => 'cache',
		'cachePath'        => APP_DIR.'../web/',
		'cacheUrl'         => HTTP_HOST.'/',
	],

	'routes' => [
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

		'page/toto/json' => [
			'page/toto/json',
			'task=page&action=json&type=json',
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
	]
];
