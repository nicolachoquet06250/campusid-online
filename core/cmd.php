<?php

namespace Dframe\custom\traits;


use Dframe\custom\Command;

trait cmd {
	private $command, $callback = null;
	public function __construct(Command $command) {
		$this->command = $command;
	}

	abstract protected function before_run();

	abstract protected function after_run();

	/**
	 * @param null|callable|string $callback
	 * @throws \Exception
	 */
	public function run($callback = null) {
		$this->before_run();
		$method = $this->command->get_command_method();
		if(in_array($method, get_class_methods(get_class($this)))) {
			$this->$method();
			if(is_null($callback) && !is_null($this->callback)) {
				$callback = $this->callback;
			}
			if(!is_null($callback)) {
				$callback();
			}
		}
		else throw new \Exception('Method `'.$this->command->get_command_method().'` not found !');
		$this->after_run();
	}
}