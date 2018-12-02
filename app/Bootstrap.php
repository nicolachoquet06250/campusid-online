<?php

/**
 * Project Name
 * Copyright (c) Firstname Lastname
 *
 * @license http://yourLicenceUrl/ (Licence Name)
 */

use Dframe\Session;
use Dframe\Messages;
use Dframe\Token;
use Dframe\Database\Database;

require_once dirname(__DIR__) . '/web/config.php';

/**
 * Here is a description of what this file is for.
 *
 * @author First Name <adres@email>
 */

class Bootstrap
{
	protected $db, $session, $msg, $token, $models = [
		'example' => [
			'class' => \Model\ExampleModel::class,
			'source' => __DIR__.'/Model/Example.php',
		],
	], $services = [
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

    public function __construct()
    {
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

                $this->db = new Database($dbConfig);
				$this->db->setErrorLog(setErrorLog); // Debugowanie
            }

        } catch (DBException $e) {
            echo 'The connect can not create: ' . $e->getMessage();
            exit();
        }

        $this->session = new Session('session_name'); // Best to set projectName
        $this->msg = new Messages($this->session);     // Default notify class
        $this->token = new Token($this->session);     // Default csrf token

		foreach ($this->services as $service_name => $service_detail) {
			if(is_null($service_detail['value'])) {
				$param = null;
				if($service_detail['param_is'] === 'var') $param = ${$service_detail['param']};
				elseif ($service_detail['param_is'] === 'prop') $param = $this->{$service_detail['param']};
				else $param = $service_detail['param'];
				$this->services[$service_name]['value'] = new $service_detail['class']($param);
			}
		}
		$this->services['database']['value']->setErrorLog(setErrorLog); // Debugowanie

        return $this;
    }

    public function get_token() {
    	return $this->token;
	}

	public function get_session() {
    	return $this->session;
	}

	public function get_msg() {
    	return $this->msg;
	}

	public function get_db() {
    	return $this->db;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($name, $arguments) {
		if(strstr($name, 'get_model_')) {
			$model = str_replace('get_model_', '', $name);
			if(isset($this->models[$model])) {
				$model_class = $this->models[$model]['class'];
				$model_source = $this->models[$model]['source'];
				require_once $model_source;
				return new $model_class;
			}
			else throw new Exception('Model '.$model.' not found !');
		}
		elseif (strstr($name, 'get_service_')) {
			$service = str_replace('get_service_', '', $name);
			if(isset($this->services[$service])) {
				return $this->services[$service]['value'];
			}
			else throw new Exception('Service '.$service.' not found !');
		}
	}

}
