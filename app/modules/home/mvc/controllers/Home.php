<?php

namespace modules\home\mvc\controllers;


use Controller\Controller;

class HomeController extends Controller {
	public function index() {
		$view = $this->loadView('Index');
		$view->assign('contents', 'Example assign');
		/**
		 * @var ExampleModel $example_model
		 */
		$example_model = $this->loader->get_model_example();
		$users = $example_model->example();

		return $view->render(
			[
				[
					'status' => 'success',
				],
				$users['data'],
			], 'json'
		);
	}
}