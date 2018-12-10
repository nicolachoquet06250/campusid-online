<?php

namespace commands;


use Dframe\custom\traits\cmd;

class help {
	use cmd;

	protected function before_run() {
		// TODO: Implement before_run() method.
	}

	protected function after_run() {
		// TODO: Implement after_run() method.
	}

	public function run() {
		echo "HELP FOR CUSTOM DFRAME\n";
	}
}