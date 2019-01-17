<?php

namespace commands;


use Dframe\custom\traits\cmd;

class genere {
	use cmd;

	protected function before_run() {
		// TODO: Implement before_run() method.
	}

	protected function after_run() {
		// TODO: Implement after_run() method.
	}

	public function documentation() {
		/** @description génération des répertoirs du module documentation. */
		if(!is_dir(__DIR__.'/../app/modules/documentation/mvc/controllers'))
			mkdir(__DIR__.'/../app/modules/documentation/mvc/controllers', 0777, true);
		if(!is_dir(__DIR__.'/../app/modules/documentation/mvc/models'))
			mkdir(__DIR__.'/../app/modules/documentation/mvc/models', 0777, true);

		// Generate Model
		if(!is_file(__DIR__.'/../app/modules/documentation/mvc/models/Documentation.php'))
			file_put_contents(__DIR__.'/../app/modules/documentation/mvc/models/Documentation.php', '<?php
		
namespace modules\documentation\mvc\models;

use Model\Model;

/**
 * Class Documentation
 *
 * @package modules\home\mvc\models
 */
class Documentation extends Model {
	/** @var Router $router */
	public $router;
	
	public function get_user_doc(): string {
		return \'\';
	}
	
	public function get_technique_doc(): string {
		return \'\';
	}
}
');

		// Generate Controller
		if(!is_file(__DIR__.'/../app/modules/documentation/mvc/controllers/Documentation.php'))
			file_put_contents(__DIR__.'/../app/modules/documentation/mvc/controllers/Documentation.php', '<?php
		
namespace modules\documentation\mvc\controllers;


use Controller\Controller;
use Dframe\custom\Router;
use Dframe\custom\View;

/**
 * Class Documentation
 *
 * @package modules\home\mvc\controllers
 */
class Documentation extends Controller {
	/** @var Router $router */
	public $router;
	
	/**
	 * @return mixed
	 * @throws View\Exceptions\ViewException
	 * @throws \SmartyException
	 */
	public function index() {
		/** @var View $view */
		$view = $this->loadView(\'Index\');
		/** @var \modules\documentation\mvc\models\Documentation $model */
		$model = $this->loader->get_model_documentation();
		$view->assign(\'router\', $this->router);
		$view->assign(\'onglets\', [\'user\' => \'?onglet=user\', \'technique\' => \'?onglet=technique\']);
		if($this->loader->get_util_http()->get(\'onglet\') === \'technique\') {
			$view->assign(\'content\', $model->get_technique_doc());
		}
		elseif($this->loader->get_util_http()->get(\'onglet\') === \'user\') {
			$view->assign(\'content\', $model->get_user_doc());
		}
		else {
			$view->assign(\'content\', \'<center>Veuillez choisir la documentation <a href="?onglet=user">utilisateur</a> ou <a href="?onglet=technique">technique</a> !</center> \');
		}

		return $view->render(\'documentation\');
	}
}
');

		file_put_contents(__DIR__.'/../app/modules/documentation/router.php', '<?php

/** @var ModuleLoader $loader */
$documentation_routes = $loader->get_util_route_parser()->parse_module_routes(\'documentation\');

$documentation_routes->add_route(\'documentation\', [
	\'documentation\',
	\'task=documentation&action=index&type=html\',
]);
return $documentation_routes->get_routes();
');

		file_put_contents(__DIR__.'/../app/modules/documentation/ModuleLoader.php', '<?php

namespace modules\documentation;


class ModuleLoader extends \ModuleLoader {
	public function __construct() {
		$this->merge_array(\'services\', []);
		$this->merge_array(\'models\', []);
		$this->merge_array(\'controllers\', []);
		parent::__construct();
	}
}
');

		$this->callback = self::class.'::callback';
	}

	public static function callback() {
		echo "La documentation à bien été généré !\nVous pouvez y accéder à l'adresse http://api.campusid.local/documentation\n";
	}
}