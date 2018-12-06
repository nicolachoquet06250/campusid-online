<?php

$dependencies = [
	'dependencies/queues' => 'https://github.com/nicolachoquet06250/mvc_framework_queues.git',
];

foreach ($dependencies as $dir => $repo) {
	exec('git clone '.$repo.' '.__DIR__.'/'.$dir, $out);
	echo implode("\n", $out);
}