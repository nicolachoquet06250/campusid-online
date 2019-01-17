<?php

namespace tools;


use Controller\Controller;

class route_parser {
	private static $routes = [];
	protected $modules_base_path = __DIR__.'/../app/modules';
	const DEFAULT_MODULE = 'home';
	const DEFAULT_ACTION = 'index';
	const DEFAULT_TYPE = 'json';

	/**
	 * @param string $module
	 * @return route_parser
	 * @throws \ReflectionException
	 */
	public function parse_module_routes($module = self::DEFAULT_MODULE) {
		$complete_controller_path = realpath($this->modules_base_path.'/'.$module.'/mvc/controllers/'.ucfirst($module).'.php');
		$class = '\modules\\'.$module.'\mvc\controllers\\'.ucfirst($module);
		$routes = [];
		if($complete_controller_path) {
			$complete_controller_path = realpath($complete_controller_path);
			require_once $complete_controller_path;

			$ref_class = new \ReflectionClass($class);
			$doc_block = $ref_class->getDocComment();
			if(empty($doc_block)) $doc_block = "/**\n *\n */";
			preg_match('`\@(GET|POST|route)\(([a-zA-Z0-9\{\}\/\_\-\|]+)\)\n`', $doc_block, $matches);
			if ($matches) {
				unset($matches[0]);
				$route_path          = $matches[2];
			} else $route_path       = $module;
			$routes[$route_path] = [
				$route_path,
				'task='.$module.'&action={action}&type={type}'
			];

			preg_match('`\@(action)\(([a-zA-Z0-9\_]+)\)\n`', $doc_block, $matches);
			if ($matches) {
				unset($matches[0]);
				$routes[$route_path][1] = str_replace('{action}', $matches[2], $routes[$route_path][1]);
			} else $routes[$route_path][1] = str_replace('{action}', self::DEFAULT_ACTION, $routes[$route_path][1]);

			preg_match('`\@(type)\(([a-zA-Z]+)\)\n`', $doc_block, $matches);
			if ($matches) {
				unset($matches[0]);
				$routes[$route_path][1] = str_replace('{type}', $matches[2], $routes[$route_path][1]);
			} else $routes[$route_path][1] = str_replace('{type}', self::DEFAULT_TYPE, $routes[$route_path][1]);

			$methods = get_class_methods($class);
			$controller_methods = get_class_methods(Controller::class);
			foreach ($methods as $method) {
				if(!in_array($method, $controller_methods)) {
					$ref_method = new \ReflectionMethod($class, $method);
					$doc_block = $ref_method->getDocComment();

					if(empty($doc_block)) $doc_block = "/**\n *\n */";
					preg_match('`\@(GET|POST|route)\(([a-zA-Z0-9\{\}\/\_\-\|]+)\)\n`', $doc_block, $matches);
					if ($matches) {
						unset($matches[0]);
						$route_path          = $matches[2];
					} else $route_path       = $module.'/'.$method;
					$routes[$route_path] = [
						$route_path,
						'task='.$module.'&action={action}&type={type}'
					];

					preg_match('`\@(action)\(([a-zA-Z0-9\_]+)\)\n`', $doc_block, $matches);
					if ($matches) {
						unset($matches[0]);
						$routes[$route_path][1] = str_replace('{action}', $matches[2], $routes[$route_path][1]);
					} else $routes[$route_path][1] = str_replace('{action}', $method, $routes[$route_path][1]);

					preg_match('`\@(type)\(([a-zA-Z]+)\)\n`', $doc_block, $matches);
					if ($matches) {
						unset($matches[0]);
						$routes[$route_path][1] = str_replace('{type}', $matches[2], $routes[$route_path][1]);
					} else $routes[$route_path][1] = str_replace('{type}', self::DEFAULT_TYPE, $routes[$route_path][1]);
				}
			}
		}
		return $this->add_routes($routes);
	}

	public function add_route(string $route, array $route_detail) {
		self::$routes[$route] = $route_detail;
		return $this;
	}

	public function add_routes(array $routes) {
		self::$routes = array_merge(self::$routes, $routes);
		return $this;
	}

	public function get_routes(): array {
		return self::$routes;
	}
}