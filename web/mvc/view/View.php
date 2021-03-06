<?php
/**
 * Project Name
 * Copyright (c) Firstname Lastname
 *
 * @license http://yourLicenceUrl/ (Licence Name)
 */

namespace View;

/**
 * Here is a description of what this file is for.
 *
 * @author First Name <adres@email>
 */
abstract class View extends \Dframe\View {

	/**
	 * View constructor.
	 *
	 * @param $baseClass
	 * @throws \Dframe\Loader\Exceptions\LoaderException
	 */
	public function __construct($baseClass) {
		$this->setView(new \Dframe\custom\View\SmartyView());
		parent::__construct($baseClass);
	}

}