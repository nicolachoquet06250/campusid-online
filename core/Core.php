<?php

/**
 * DframeFramework
 * Copyright (c) Sławomir Kaleta.
 *
 * @license https://github.com/dframe/dframe/blob/master/LICENCE (MIT)
 */

namespace Dframe\custom;


/**
 * Core Class.
 *
 * @author Sławomir Kaleta <slaszka@gmail.com>
 */
class Core extends \Dframe\Core {
	protected $view;
	/**
	 * @return bool|void
	 * @throws \Dframe\Loader\Exceptions\LoaderException
	 */
	public function run() {
        $router = new Router();

        return $router->run();
    }

    public function setView($view) {
        $this->view = $view;
    }
}
