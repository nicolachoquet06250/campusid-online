<?php

/**
 * Project Name
 * Copyright (c) Firstname Lastname
 *
 * @license http://yourLicenceUrl/ (Licence Name)
 */

namespace Tests;


use Loader;

ini_set('session.use_cookies', 0);

session_start();

// backward compatibility
if (!class_exists('\PHPUnit\Framework\TestCase') and class_exists('\PHPUnit_Framework_TestCase')) {
	class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

$autoloader = include dirname(__DIR__).'/../vendor/autoload.php';
require_once __DIR__.'/../../core/autoload.php';
require_once __DIR__.'/../../web/mvc/controller/Controller.php';
require_once __DIR__.'/../../web/mvc/model/Model.php';
require_once __DIR__.'/../../web/mvc/view/View.php';
require_once dirname(__FILE__).'/../Bootstrap.php';
require_once dirname(__FILE__).'/../../web/config.php';

/**
 * Testing project.
 *
 * @author Sławek Kaleta <slaszka@gmail.com>
 */
class RunTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @throws \Dframe\Loader\Exceptions\LoaderException
	 */
	public function testCreateController() {
		$bootstrap         = new \Bootstrap();
		$bootstrap->router = new \Dframe\Router();

		$run  = new Loader($bootstrap);
		$page = $run->loadController('Page')->returnController;

		$this->assertEquals('{"return":"1"}', $page->json()->getBody());
	}
}
