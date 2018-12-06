<?php

namespace tools;


class route_parser {
	protected $routes = [];
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
		$complete_controller_path = $this->modules_base_path.'/'.$module.'/mvc/controllers/'.ucfirst($module).'.php';
		$class = '\modules\home\mvc\controllers\\'.ucfirst($module);
		$routes = [];
		if(file_exists($complete_controller_path)) {
			$complete_controller_path = realpath($complete_controller_path);
			require_once $complete_controller_path;

			$ref_class = new \ReflectionClass($class);
			$doc_block = $ref_class->getDocComment();
			preg_match('`\@(GET|POST|route)\(([a-zA-Z0-9\{\}\/\_\-\|]+)\)\n`', $doc_block, $matches);
			if ($matches) {
				unset($matches[0]);
				$route_path          = $matches[2];
				$routes[$route_path] = [
					$route_path,
					'task='.$module.'&action={action}&type={type}'
				];
			} else {
				$route_path          = self::DEFAULT_MODULE;
				$routes[$route_path] = [
					$route_path,
					'task='.self::DEFAULT_MODULE.'&action={action}&type={type}'
				];
			}

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
		}
		return $this->add_routes($routes);
	}

	public function add_route(string $route, array $route_detail) {
		$this->routes[$route] = $route_detail;
		return $this;
	}

	public function add_routes(array $routes) {
		$this->routes = array_merge($this->routes, $routes);
		return $this;
	}

	public function get_routes(): array {
		return $this->routes;
	}
}