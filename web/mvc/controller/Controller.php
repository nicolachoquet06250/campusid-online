<?php
/**
 * Project Name
 * Copyright (c) Firstname Lastname
 *
 * @license http://yourLicenceUrl/ (Licence Name)
 */

namespace Controller;

use Bootstrap;
use Dframe\custom\Loader;
use Dframe\custom\View\SmartyView;

abstract class Controller extends Loader {
	/**
	 * @var Bootstrap $loader
	 */
	protected $loader;
	protected $fileExtension = '.html.php';

	/**
	 * @throws \Dframe\Loader\Exceptions\LoaderException
	 */
	public function init(): void {
		parent::init();
		$this->loader = new Bootstrap();
		$classe       = basename(str_replace("\\", '/', get_class($this)));
		$this->setView(new SmartyView($classe));
	}

	/**
	 * initial function call working like construct
	 */
	public function start(): void {
		// For example you can check Auth
	}

}
