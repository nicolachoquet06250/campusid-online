<?php
/**
 * Project Name
 * Copyright (c) Firstname Lastname
 *
 * @license http://yourLicenceUrl/ (Licence Name)
 */

namespace Controller;

abstract class Controller extends \Dframe\Controller
{
	/**
	 * @var \Bootstrap $loader
	 */
	protected $loader;

	public function init() {
		parent::init();
		$this->loader = new \Bootstrap();
	}

	/**
     * initial function call working like __construct
     */
    public function start()
    {
      // For example you can check Auth 
    }

}
