#!/usr/bin/php
<?php

use Dframe\custom\Command;

require_once __DIR__.'/core/autoload.php';

try {
	Command::build(
		Command::clean_args($argv)
	)->run();
}
catch (Exception $e) {
    exit($e->getMessage());
}