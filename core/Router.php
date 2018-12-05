<?php

namespace Dframe\custom;

use Dframe\Router\Response;

class Router extends \Dframe\Router {
	protected $subdomain;

	/**
	 * @param null $controller
	 * @param null $action
	 * @param array $arg
	 * @return bool
	 * @throws \Dframe\Loader\Exceptions\LoaderException
	 */
	public function run($controller = null, $action = null, $arg = []) {
		if (is_null($controller) and is_null($action)) {
			$this->parseGets();
			$controller = $_GET['task'];
			$action     = $_GET['action'];
		}

		$arg               = $this->parseArgs;
		$bootstrap         = new \Bootstrap();
		$bootstrap->router = $this;
		$loader            = new Loader($bootstrap);
		$loadController    = $loader->loadController($controller); // Loading Controller class

		if (isset($loadController->returnController)) {
			$controller = $loadController->returnController;
			$response   = [];

			if (method_exists($controller, 'start')) {
				$response[] = 'start';
			}

			if (method_exists($controller, 'init')) {
				$response[] = 'init';
			}

			if (method_exists($controller, $action) or is_callable([$controller, $action])) {
				$response[] = $action;
			}

			if (method_exists($controller, 'end')) {
				$response[] = 'end';
			}

			foreach ($response as $key => $data) {
				if (is_callable([$controller, $data])) {
					$run = $controller->$data();
					if ($run instanceof Response) {
						if (isset($this->debug)) {
							$this->debug->addHeader(['X-DF-Debug-Method' => $action]);
							$run->headers($this->debug->getHeader());
						}

						return $run->display();
					}
				}
			}
		}

		return true;
	}

	/**
	 * Gerenate full url for files.
	 *
	 * @param string $sUrl
	 * @param string $path
	 *
	 * @return string
	 */
	public function publicWeb($sUrl = null, $path = null) {
		if (is_null($path)) {
			$path = $this->aRouting['publicWeb'];
		}

		$sExpressionUrl = $sUrl;
		$sUrl           = $this->requestPrefix.$this->domain.'/'.$path;
		$sUrl           .= $sExpressionUrl;

		unset($this->subdomain);
		$this->domain = HTTP_HOST;
		$this->setHttps($this->routerConfig->get('https', false));

		return $sUrl;
	}
}