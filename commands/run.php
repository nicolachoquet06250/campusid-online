<?php

namespace commands;


use Dframe\custom\traits\cmd;

class run {
	use cmd;

	protected function before_run() {
		// TODO: Implement before_run() method.
	}

	protected function after_run() {
		// TODO: Implement after_run() method.
	}

	/**
	 * DframeFramework
	 * Copyright (c) Sławomir Kaleta
	 *
	 * @author  Sławomir Kaleta <slaszka@gmail.com>
	 * @license https://github.com/dframe/dframe/blob/master/LICENCE (MIT)
	 *
	 * @usage   :
	 * ./builder run:server -p port=<port[8083]> host=<host[localhost]>
	 *
	 * @throws \Exception
	 */
	public function server() {
		$port = !is_null($this->command->get_param('port')) ? $this->command->get_param('port') : 8083;
		$host = !is_null($this->command->get_param('host')) ? $this->command->get_param('host') : 'localhost';
		echo "Le serveur est accessible sur l'adresse http://".$host.':'.$port."\n";
		exec('php -S '.$host.':'.$port.' -t '.APP_DIR.'/../web/ '.APP_DIR.'/../web/server.php', $out);
		echo implode("\n", $out);
	}
}