<?php
namespace commands;

use Dframe\custom\Config;
use Dframe\custom\traits\cmd;
use Exception;

class make {
	use cmd;

	protected function before_run() {
		if($this->command->get_command_method() === 'build') {
			$this->command->get_logger()->add_loggers(
				[
					'console' => [],
					'file'    => [
						'file' => 'hello',
					],
					'mail'    => [
						'to' => [
							'nicolachoquet06250@gmail.com',
							'nicolas.choquet@campusid.eu',
						]
					]
				]
			);
		}
	}

	protected function after_run() {
		// TODO: Implement after_run() method.
	}

	/**
	 * @throws \Exception
	 */
	public function build() {
		$logs = [
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
			'coucou',
			'c\'est moi !!',
			'tu es là ?',
			'Je t\'attend',
		];
		foreach ($logs as $log) {
			$this->command->get_logger()->log($log);
		}
		$this->command->get_logger()->send();
		var_dump($this->command->get_param('host'));
	}

	/**
	 * @throws \Exception
	 */
	public function install() {
		switch ($this->command->get_param('install')) {
			case 'dependencies':
				$dependencies = Config::load('dependencies')->get();

				foreach ($dependencies as $dir => $repo) {
					exec('git clone '.$repo.' '.APP_DIR.'/../core/'.$dir, $out);
					echo implode("\n", $out);
				}
				break;
			case 'vhosts':
				$vhosts_conf = Config::load('vhosts');
				$enable_systems = $vhosts_conf->get('enable_systems');
				$default_messages = $vhosts_conf->get('default_messages');

				if(!isset($argv[1])) $argv[1] = '';
				$os = !is_null($this->command->get_param('os')) ? $this->command->get_param('os') : $this->command->get_param('system');

				try {
					if(in_array($os, array_keys($enable_systems))) echo $enable_systems[$os]($default_messages)."\n";
					else {
						if(is_null($os)) exit('Error: Vous devez entrer un Système d\'exploitation ( win, lnx, osx ).'."\n");
						exit('Error: Le system `'.$os.'` n\'est pas connue.'."\n");
					}
				}
				catch (Exception $e) {
					exit('Fatal Error: '.$e->getMessage()."\n");
				}
				break;
			default: break;
		}
	}
}