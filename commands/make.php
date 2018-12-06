<?php
namespace commands;

use Dframe\custom\traits\cmd;

class make {
	use cmd;

	protected function before_run() {
		$this->command->get_logger()->add_loggers(
			[
				'console' => [],
				'file' => [
					'file' => 'hello',
				],
				'mail' => [
					'to' => [
						'nicolachoquet06250@gmail.com',
						'nicolas.choquet@campusid.eu',
					]
				]
			]
		);
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
}