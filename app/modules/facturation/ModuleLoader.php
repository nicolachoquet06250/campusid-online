<?php

namespace modules\facturation;


class ModuleLoader extends \ModuleLoader {
	public function __construct() {
		$this->merge_array('services', []);
		$this->merge_array('models', []);
		$this->merge_array('controllers', []);
		parent::__construct();
	}
}