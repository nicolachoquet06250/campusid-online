<?php

return [
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
];