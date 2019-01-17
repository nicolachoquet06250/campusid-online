<?php

namespace tools;


use Dframe\custom\Command;

class commands {
	/** @var null|Command $cmd  */
	private $cmd = null;

	/**
	 * @param array|string $cmd
	 * @return Command
	 */
	public function get($cmd = []) {
		if(is_string($cmd)) $cmd = explode(' ', $cmd);
		$this->cmd = Command::build($cmd);
		return $this->cmd;
	}

	/**
	 * @param array|string $cmd
	 * @param null|callable|string $callback
	 * @throws \Exception
	 */
	public function run($cmd = null, $callback = null) {
		if(!is_null($this->cmd) && is_null($cmd)) $this->cmd->run($callback);
		elseif ((is_null($this->cmd) && !is_null($cmd)) || (!is_null($this->cmd) && !is_null($cmd))) {
			if(is_string($cmd)) $cmd = explode(' ', $cmd);
			Command::build($cmd)->run($callback);
		}
		elseif (is_null($this->cmd) && is_null($cmd)) Command::build()->run($callback);
	}
}