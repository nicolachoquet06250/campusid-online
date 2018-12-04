<?php

use Controller\PageController;
use Dframe\Database\Database;
use Dframe\Messages;
use Dframe\Session;
use Dframe\Token;
use Model\ExampleModel;
use modules\home\mvc\controllers\HomeController;
use modules\home\mvc\models\HomeModel;
use tools\http;

require_once __DIR__.'/../web/Loader.php';

/**
 * Class ModuleLoader
 *
 * @method ExampleModel get_model_example()
 * @method HomeModel get_model_home()
 *
 * @method PageController get_controller_page()
 * @method HomeController get_controller_home()
 *
 * @method Database get_service_database()
 * @method Session get_service_session()
 * @method Messages get_service_message()
 * @method Token get_service_token()
 *
 * @method http get_util_http()
 */
class ModuleLoader extends Loader {
	protected $charged = [
		'models' => [
			'getter' => 'get_model_',
			'callback' => 'load_model'
		],
		'controllers' => [
			'getter' => 'get_controller_',
			'callback' => 'load_controller'
		],
		'services' => [
			'getter' => 'get_service_',
			'callback' => 'load_service'
		],
		'tools' => [
			'getter' => 'get_util_',
			'callback' => 'load_util'
		],
	];

	protected $models = [
		'example' => [
			'class' => ExampleModel::class,
			'source' => __DIR__.'/Model/Example.php',
		],
		'home' => [
			'class' => HomeModel::class,
			'source' => __DIR__.'/modules/home/mvc/models/Home.php',
		],
	];
	protected $controllers = [
		'page' => [
			'class' => PageController::class,
			'source' => __DIR__.'/Controller/Page.php',
		],
		'home' => [
			'class' => HomeController::class,
			'source' => __DIR__.'/modules/home/mvc/controllers/Home.php',
		],
	];
	protected $services = [
		'database' => [
			'class' => Database::class,
			'param' => 'dbConfig',
			'param_is' => 'var',
			'value' => null,
		],
		'session' => [
			'class' => Session::class,
			'param' => 'session_name',
			'param_is' => "string",
			'value' => null,
		],
		'message' => [
			'class' => Messages::class,
			'param' => 'session',
			'param_is' => 'prop',
			'value' => null,
		],
		'token' => [
			'class' => Token::class,
			'param' => 'session',
			'param_is' => 'prop',
			'value' => null,
		],
	];
	protected $tools = [
		'http' => [
			'class' => http::class,
			'source' => __DIR__.'/../tools/http.php',
		],
	];

	public function __construct() {
		$this->load_services();
	}

	protected function load_services() {
		try {
			if (!empty(DB_HOST)) {
				$dbConfig = array(
					'host' => DB_HOST,
					'dbname' => DB_DATABASE,
					'username' => DB_USER,
					'password' => DB_PASS,
				);
				// Debug Config
				$config = [
					'logDir' => APP_DIR . 'View/logs/',
					'attributes' => [
						PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
						//PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,  		// Set pdo error mode silent
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 		// If you want to Show Class exceptions on Screen, Uncomment below code
						PDO::ATTR_EMULATE_PREPARES => true, 				// Use this setting to force PDO to either always emulate prepared statements (if TRUE), or to try to use native prepared statements (if FALSE).
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC 	// Set default pdo fetch mode as fetch assoc
					]
				];
			}
			foreach ($this->services as $service_name => $service_detail) {
				if (is_null($service_detail['value'])) {
					$param = null;
					if ($service_detail['param_is'] === 'var')
						$param = ${$service_detail['param']};
					elseif ($service_detail['param_is'] === 'prop')
						$param = $this->services[$service_detail['param']]['value'];
					else $param = $service_detail['param'];
					$this->services[$service_name]['value'] = new $service_detail['class']($param);
				}
			}
			$this->services['database']['value']->setErrorLog(setErrorLog);
		}
		catch (DBException $e) {
			echo 'The connect can not create: ' . $e->getMessage();
			exit();
		}
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception
	 */
	protected function load_model($name) {
		$model = str_replace('get_model_', '', $name);
		if(isset($this->models[$model])) {
			$model_class = $this->models[$model]['class'];
			$model_source = $this->models[$model]['source'];
			require_once $model_source;
			return new $model_class;
		}
		else throw new Exception('Model '.$model.' not found !');
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception
	 */
	protected function load_controller($name) {
		$controller = str_replace('get_controller_', '', $name);
		if(isset($this->controllers[$controller])) {
			$ctrl_class = $this->controllers[$controller]['class'];
			$ctrl_source = $this->controllers[$controller]['source'];
			require_once $ctrl_source;
			return new $ctrl_class;
		}
		else throw new Exception('Controller '.$controller.' not found !');
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception
	 */
	protected function load_service($name) {
		$service = str_replace('get_service_', '', $name);
		if(isset($this->services[$service])) {
			return $this->services[$service]['value'];
		}
		else throw new Exception('Service '.$service.' not found !');
	}

	/**
	 * @param $name
	 * @return mixed
	 * @throws Exception
	 */
	protected function load_util($name) {
		$util = str_replace('get_util_', '', $name);
		if(isset($this->tools[$util])) {
			$util_class = $this->tools[$util]['class'];
			$util_source = $this->tools[$util]['source'];
			require_once $util_source;
			return new $util_class;
		}
		else throw new Exception('Tool '.$util.' not found !');
	}

	protected function merge_array($prop, $array) {
		$this->$prop = array_merge($this->$prop, $array);
	}
}