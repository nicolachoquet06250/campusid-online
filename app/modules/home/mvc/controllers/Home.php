<?php

namespace modules\home\mvc\controllers;

use Controller\Controller;
use Dframe\custom\Config;
use Dframe\custom\Router;
use Dframe\custom\Router\Response;
use Dframe\custom\View;

/**
 * Class Home
 *
 * @package modules\home\mvc\controllers
 */
class Home extends Controller {
	/** @var Router $router */
	public $router;

	public function error() {
		$view = $this->loadView('Index');
		$http = $this->loader->get_util_http();

		$errorsTypes = ['404'];
		if (!is_null($http->get('type')) OR !in_array($http->get('type'), $errorsTypes)) {
			return $this->router->redirect(':task/:action?task=page&action=index');
		}

		return Response::create($view->fetch('errors/'.$http->get('type')))->status($http->get('type'));
	}

	/**
	 * @throws View\Exceptions\ViewException
	 * @throws \Exception
	 */
	public function index() {
		/**
		 * @var View $view
		 */
		$view = $this->loadView('Index');
		$view->assign('contents', 'Example assign');
		/** @var \modules\home\mvc\models\Home $example_model */
		$example_model = $this->loader->get_model_home();
		$users         = $example_model->example();
		$this->loader->get_util_command()->get()->run();
		return $view->renderJSON(
			[
				'status' => 'success',
				'data' => $users['data'],
			]
		);
	}


	/**
	 * @return Response|string
	 * @throws View\Exceptions\ViewException
	 * @throws \SmartyException
	 */
	public function responseExample() {
		/** @var View $view */
		$view = $this->loadView('Index');
		$view->assign('router', $this->router);
		$view->assign('contents', 'Example assign');

		return $view->render('index');
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string $method
	 * @param $test
	 * @return Response|object
	 */
	public function __call($method, $test) {

		$smartyConfig = Config::load('view/smarty');
		$view         = $this->loadView('Index');
		$http         = $this->loader->get_util_http();

		$patchController = APP_DIR.'modules/home/mvc/views/tpl'.'/'.htmlspecialchars($http->get('action')).$smartyConfig->get('fileExtension', '.html.php');

		if (!file_exists($patchController)) {
			return $this->router->redirect(':task/:action?task=page&action=index');
		}

		return Response::create($view->fetch('home/'.htmlspecialchars($http->get('action'))));
	}
}