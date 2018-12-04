<?php

/**
 * DframeFramework
 * Copyright (c) Sławomir Kaleta.
 *
 * @license https://github.com/dframe/dframe/blob/master/LICENCE (MIT)
 */

namespace Dframe\custom;

use Dframe\custom\Router\Response;
use Dframe\custom\View\Exceptions\ViewException;
use Dframe\custom\View\SmartyView;

/**
 * View Class.
 *
 * @author Sławomir Kaleta <slaszka@gmail.com>
 */
abstract class View extends Loader implements \Dframe\View\ViewInterface {
	/** @var SmartyView $view */
	protected $view;
	/**
	 * Defines template variables.
	 *
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return void
	 * @throws ViewException
	 */
    public function assign($name, $value)
    {
        if (!isset($this->view)) {
            throw new ViewException('Please Define view engine in app/View.php', 500);
        }

        return $this->view->assign($name, $value);
    }

	/**
	 * Generates the output of the templates with parsing all the template variables.
	 *
	 * @param string|array $data
	 * @param string|int|null $type
	 *
	 * @return mixed
	 * @throws \SmartyException
	 */
    public function render($data, $type = null)
    {
        if (empty($type) or $type === 'html') {
            return $this->view->renderInclude($data);
        } elseif ($type == 'jsonp') {
            return $this->renderJSONP($data);
        } else {
            return $this->renderJSON($data);
        }
    }

	/**
	 * Fetch the output of the templates with parsing all the template variables.
	 *
	 * @param string $name
	 * @param string $path
	 *
	 * @return void|string
	 * @throws ViewException
	 */
    public function fetch($name, $path = null)
    {
        if (!isset($this->view)) {
            throw new ViewException('Please Define view engine in app/View.php', 500);
        }

        return $this->view->fetch($name, $path);
    }

	/**
	 * Include pliku.
	 *
	 * @param $name
	 * @param null $path
	 * @throws ViewException
	 * @throws \SmartyException
	 */
    public function renderInclude($name, $path = null)
    {
        if (!isset($this->view)) {
            throw new ViewException('Please Define view engine in app/View.php', 500);
        }

        return $this->view->renderInclude($name, $path);
    }

    /**
     * Display JSON.
     *
     * @param array $data
     * @param int   $status
     */
    public function renderJSON($data, $status = 200)
    {
        exit(Response::Create(json_encode($data))->status($status)->headers(['Content-Type' => 'application/json'])->display());
    }

    /**
     * Display JSONP.
     *
     * @param array $data
     */
    public function renderJSONP($data)
    {
        $callback = null;
        if (isset($_GET['callback'])) {
            $callback = $_GET['callback'];
        }

        exit(Response::Create($callback . '(' . json_encode($data) . ')')->headers(['Content-Type' => 'application/jsonp'])->display());
    }
}
