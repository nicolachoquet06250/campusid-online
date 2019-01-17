<?php

namespace Dframe\custom;

use Dframe\custom\traits\cmd;
use Exception;

class Command {
	protected $command, $params, $command_method, $logger;

	/**
	 * Command constructor.
	 *
	 * @param array $args
	 */
	protected function __construct(array $args) {
		if(count($args) === 0 || (count($args) === 1 && $args[0] === 'help')) {
			$this->command 		  = 'help';
			$this->command_method = 'index';
			$this->params		  = [];
		}
		else {
			$this->command        = $args[0];
			$this->command_method = explode(':', $this->command)[1];
			$this->params         = self::clean_args(
				self::clean_args($args)
			);
			$this->logger         = new Logger\Logger();
			$this->build_params();
		}
	}

	/**
	 * @return Logger\Logger
	 */
	public function get_logger() {
		return $this->logger;
	}

	/**
	 * @return array
	 */
	public function get_params(): array {
		return $this->params;
	}

	/**
	 * @param $key
	 * @return mixed
	 * @throws \Exception
	 */
	public function get_param($key) {
		if(isset($this->params[$key])) return $this->params[$key];
		else throw new Exception('Parameter `'.$key.'` not found in `'.$this->get_command_method().'` method !');
	}

	/**
	 * @return string
	 */
	public function get_command_method(): string {
		return $this->command_method;
	}

	protected function build_params() {
		$params = [];
		foreach ($this->params as $param) {
			$param = explode('=', $param);
			if(count($param) === 1) $params[] = $param[0];
			elseif (count($param) === 2) $params[$param[0]] = $param[1];
		}
		$this->params = $params;
	}

	/**
	 * @param array $args
	 * @return Command
	 */
	public static function build(array $args) {
		return new Command($args);
	}

	/**
	 * @param array $args
	 * @return array
	 */
	public static function clean_args(array $args): array {
		unset($args[0]);
		$_args = [];
		foreach ($args as $arg) {
			$_args[] = $arg;
		}
		return $_args;
	}

	/**
	 * @param null|callable|string $callback
	 * @throws Exception
	 */
	public function run($callback = null): void {
		$command_class = explode(':', $this->command)[0];
		if(file_exists(__DIR__.'/../commands/'.$command_class.'.php')) {
			require_once __DIR__.'/../commands/'.$command_class.'.php';
			$complete_command_class = '\commands\\'.$command_class;
			/** @var cmd $cmd */
			$cmd = new $complete_command_class($this);
			$cmd->run($callback);
			if(!is_null($callback)) $callback($this);
		}
	}
}