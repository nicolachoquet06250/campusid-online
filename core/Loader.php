<?php

namespace Dframe\custom;
require_once __DIR__.'/Core.php';

use Dframe\Config;
use Dframe\Loader\Exceptions\LoaderException;
use Exception;

class Loader extends Core {
	protected $charged = [];

	public $baseClass;
	public $router;
	private $namespaceSeparator = '\\';

	/**
	 * Loader constructor.
	 *
	 * @param null $bootstrap
	 * @throws LoaderException
	 */
	public function __construct($bootstrap = null) {
		if (!defined('APP_DIR')) {
			throw new LoaderException('Please Define appDir in Main config.php', 500);
		}

		if (!defined('SALT')) {
			throw new LoaderException('Please Define SALT in Main config.php', 500);
		}

		$this->baseClass = empty($bootstrap) ? new \Bootstrap() : $bootstrap;

		if (isset($this->baseClass->router)) {
			$this->router = $this->baseClass->router;
		}

		return $this;
	}

	/**
	 * Metoda do includowania pliku modelu i wywołanie objektu przez namespace.
	 *
	 * @param string $name
	 *
	 * @return object
	 */
	public function loadModel($name)
	{
		return $this->loadObject($name, 'Model');
	}

	/**
	 * Metoda do includowania pliku widoku i wywołanie objektu przez namespace.
	 *
	 * @param string $name
	 *
	 * @return object
	 */
	public function loadView($name) {
		return $this->loadObject($name, 'View');
	}

	/**
	 * Metoda do includowania pliku widoku i wywołanie objektu przez namespace.
	 *
	 * @param string $name
	 * @param string $type
	 *
	 * @return bool|object
	 */
	private function loadObject($name, $type) {
		if (!in_array($type, (['Model', 'View']))) {
			return false;
		}

		$pathFile = pathFile($name);
		$folder = $pathFile[0];
		$name = $pathFile[1];

		$n = str_replace($type, '', $name);
		$path = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, APP_DIR . $type . '/' . $folder . $n . '.php');

		try {
			if (!$this->isCamelCaps($name, true)) {
				if (!defined('CODING_STYLE') or (defined('CODING_STYLE') and CODING_STYLE == true)) {
					throw new LoaderException('Camel Sensitive is on. Can not use ' . $type . ' ' . $name . ' try to use StudlyCaps or CamelCase');
				}
			}

			$name = !empty($folder) ? $this->namespaceSeparator . $type . $this->namespaceSeparator . str_replace([$this->namespaceSeparator, '/'], $this->namespaceSeparator, $folder) . $name . $type : $this->namespaceSeparator . $type . $this->namespaceSeparator . $name . $type;
			if (!is_file($path)) {
				throw new LoaderException('Can not open ' . $type . ' ' . $name . ' in: ' . $path);
			}

			include_once $path;
			$ob = new $name($this->baseClass);
			if (method_exists($ob, 'init')) {
				$ob->init();
			}
		} catch (LoaderException $e) {
			$msg = null;
			if (ini_get('display_errors') == 'on') {
				$msg .= '<pre>';
				$msg .= 'Message: <b>' . $e->getMessage() . '</b><br><br>';

				$msg .= 'Accept: ' . $_SERVER['HTTP_ACCEPT'] . '<br>';
				if (isset($_SERVER['HTTP_REFERER'])) {
					$msg .= 'Referer: ' . $_SERVER['HTTP_REFERER'] . '<br><br>';
				}

				$msg .= 'Request Method: ' . $_SERVER['REQUEST_METHOD'] . '<br><br>';
				$msg .= 'Current file Path: <b>' . $this->router->currentPath() . '</b><br>';
				$msg .= 'File Exception: ' . $e->getFile() . ':' . $e->getLine() . '<br><br>';
				$msg .= 'Trace: <br>' . $e->getTraceAsString() . '<br>';
				$msg .= '</pre>';

				exit($msg);
			}

			$routerConfig = Config::load('router');

			if (isset($routerConfig->get('error/400')[0])) {
				return $this->router->redirect($routerConfig->get('error/400')[0], 400);
			} elseif (isset($routerConfig->get('error/404')[0])) {
				return $this->router->redirect($routerConfig->get('error/404')[0], 404);
			}

			return 'loadObject Error';
		}

		return $ob;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($name, $arguments) {
		foreach ($this->charged as $n => $detail)
			if(strstr($name, $detail['getter']))
				return $this->{$detail['callback']}($name);
		return null;
	}

	/**
	 * @param $controller
	 * @return $this|string
	 */
	public function loadController($controller) {
		$subControler = null;
		if (strstr($controller, ',') !== false) {
			$url = explode(',', $controller);
			$urlCount = count($url) - 1;
			$subControler = '';

			for ($i = 0; $i < $urlCount; $i++) {
				if (!defined('CODING_STYLE') or (defined('CODING_STYLE') and CODING_STYLE == true)) {
					$subControler .= ucfirst($url[$i]) . DIRECTORY_SEPARATOR;
				} else {
					$subControler .= $url[$i] . DIRECTORY_SEPARATOR;
				}
			}

			$controller = $url[$urlCount];
		}

		if (!defined('CODING_STYLE') or (defined('CODING_STYLE') and CODING_STYLE == true)) {
			$controller = ucfirst($controller);
		}

		$controller = strtolower($controller);
		$controller_class = '\modules\\'.$controller.'\mvc\controllers\\'.ucfirst($controller);
		$controller_path = APP_DIR.'modules/'.$controller.'/mvc/controllers/'.ucfirst($controller).'.php';
		try {
			if (!is_file($controller_path)) {
				throw new LoaderException('Can not open Controller ' . $controller . ' in: ' . $controller_path);
			}

			if (isset($this->baseClass->router->debug)) {
				$this->baseClass->router->debug->addHeader(['X-DF-Debug-File' => $controller_path]);
				$this->baseClass->router->debug->addHeader(['X-DF-Debug-Controller' => $controller_class]);
			}

			include_once $controller_path;

			$xsubControler = str_replace(DIRECTORY_SEPARATOR, "\\", $subControler);
			if (!class_exists($controller_class)) {
				throw new LoaderException('Bad controller error');
			}

			$this->returnController = new $controller_class($this->baseClass);
		} catch (LoaderException $e) {
			$msg = null;
			if (ini_get('display_errors') == 'on') {
				$msg .= '<pre>';
				$msg .= 'Message: <b>' . $e->getMessage() . '</b><br><br>';

				$msg .= 'Accept: ' . $_SERVER['HTTP_ACCEPT'] . '<br>';
				if (isset($_SERVER['HTTP_REFERER'])) {
					$msg .= 'Referer: ' . $_SERVER['HTTP_REFERER'] . '<br><br>';
				}

				$msg .= 'Request Method: ' . $_SERVER['REQUEST_METHOD'] . '<br><br>';
				$msg .= 'Current file Path: <b>' . $this->router->currentPath() . '</b><br>';
				$msg .= 'File Exception: ' . $e->getFile() . ':' . $e->getLine() . '<br><br>';
				$msg .= 'Trace: <br>' . $e->getTraceAsString() . '<br>';
				$msg .= '</pre>';

				exit($msg);
			}

			$routerConfig = Config::load('router');

			if (isset($routerConfig->get('error/400')[0])) {
				return $this->router->redirect($routerConfig->get('error/400')[0], 400);
			} elseif (isset($routerConfig->get('error/404')[0])) {
				return $this->router->redirect($routerConfig->get('error/404')[0], 404);
			}

			return 'loadController Error';
		}

		return $this;
	}

	/**
	 * @param string $string
	 * @param bool $classFormat
	 * @param bool $public
	 * @param bool $strict
	 * @return bool
	 */
	public static function isCamelCaps($string, $classFormat = false, $public = true, $strict = true) {

		// Check the first character first.
		if ($classFormat === false) {
			$legalFirstChar = '';
			if ($public === false) {
				$legalFirstChar = '[_]';
			}

			if ($strict === false) {
				// Can either start with a lowercase letter,
				// or multiple uppercase
				// in a row, representing an acronym.
				$legalFirstChar .= '([A-Z]{2,}|[a-z])';
			} else {
				$legalFirstChar .= '[a-z]';
			}
		} else {
			$legalFirstChar = '[A-Z]';
		}

		if (preg_match("/^$legalFirstChar/", $string) === 0) {
			return false;
		}

		// Check that the name only contains legal characters.
		$legalChars = 'a-zA-Z0-9';
		if (preg_match("|[^$legalChars]|", substr($string, 1)) > 0) {
			return false;
		}

		if ($strict === true) {
			// Check that there are not two capital letters
			// next to each other.
			$length = strlen($string);
			$lastCharWasCaps = $classFormat;

			for ($i = 1; $i < $length; $i++) {
				$ascii = ord($string[$i]);
				if ($ascii >= 48 and $ascii <= 57) {
					// The character is a number, so it cant be a capital.
					$isCaps = false;
				} else {
					if (strtoupper(
							$string[$i]
						) === $string[$i]) {
						$isCaps = true;
					} else {
						$isCaps = false;
					}
				}

				if ($isCaps === true and $lastCharWasCaps === true) {
					return false;
				}

				$lastCharWasCaps = $isCaps;
			}
		}//end if

		return true;
	}

	//end isCamelCaps()

	/**
	 * Metoda
	 * init dzialajaca jak __construct wywoływana na poczatku kodu.
	 */
	public function init() {
	}

	/**
	 * Metoda
	 * dzialajaca jak __destruct wywoływana na koncu kodu.
	 */
	public function end() {
	}
}