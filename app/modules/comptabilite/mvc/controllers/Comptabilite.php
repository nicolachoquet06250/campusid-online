<?php

namespace modules\comptabilite\mvc\controllers;


use Controller\Controller;
use View\View;

/**
 * Class Test
 *
 * @package modules\comptabilite\mvc\controllers
 */
class Comptabilite extends Controller {

	public function index() {
		/** @var View $view */
		$view = $this->loadView('Index');
		return $view->renderJSON(
			[
				'status' => 'success',
				'data' => 'toto',
			]
		);
	}

	/**
	 * @route(comptabilite/test)
	 */
	public function test_de_nouvelle_route() {
		/** @var View $view */
		$view = $this->loadView('Index');
		return $view->renderJSON(
			[
				'status' => 'success',
				'data' => 'tata',
			]
		);
	}
}