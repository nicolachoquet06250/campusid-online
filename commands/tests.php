<?php

namespace commands;
require_once __DIR__.'/../tools/var_dump.php';

use Dframe\custom\traits\cmd;
use Dframe\custom\Typage;
use tools\var_dump;

class tests {
	use cmd;
	private $var_dump;

	protected function before_run() {
		$this->var_dump = new var_dump();
	}

	protected function after_run() {}

	/**
	 * @throws \Dframe\Loader\Exceptions\LoaderException
	 */
	public function typage() {
		$check_ctrl = Typage::check(Typage::FILES_IN_DIR_TYPE, __DIR__.'/../web/mvc/controller');
		var_dump('Folder web/mvc/controller types check '.($check_ctrl ? 'OK' : 'FAIL'));

		$check_model = Typage::check(Typage::FILES_IN_DIR_TYPE, __DIR__.'/../web/mvc/model');
		var_dump('Folder web/mvc/model types check '.($check_model ? 'OK' : 'FAIL'));

		$check_ctrl = Typage::check(Typage::FILE_TYPE, __DIR__.'/../web/mvc/controller/Controller.php');
		var_dump('File web/mvc/controller/Controller.php types check '.($check_ctrl ? 'OK' : 'FAIL'));
	}
}