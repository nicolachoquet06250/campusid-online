<?php

/**
 * Project Name
 * Copyright (c) Firstname Lastname
 *
 * @license http://yourLicenceUrl/ (Licence Name)
 */

namespace Model;

/**
 * This class includes methods for models.
 *
 * @abstract
 */
abstract class Model extends \Dframe\Model {
	/**
	 * @var \Bootstrap $loader
	 */
	protected $loader;

	public function init() {
		parent::init();
		$this->loader = new \Bootstrap();
	}

	public function __construct($bootstrap = null) {
		parent::__construct($bootstrap);
		$this->init();
	}

}
