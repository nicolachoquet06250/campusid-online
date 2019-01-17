<?php
namespace commands;

use Dframe\custom\traits\cmd;

class make {
	use cmd;

	protected function before_run() {
		$this->command->get_logger()
					  ->add_logger('console')
					  ->add_loggers(
					  	[
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
		$logs = [];
		foreach ($this->command->get_params() as $param => $value) {
			$logs[] = $param.' = '.$value;
		}
		foreach ($logs as $log) {
			$this->command->get_logger()->log($log);
		}
		$this->command->get_logger()->send();
	}

	/**
	 * @throws \Exception
	 */
	public function send_mail() {
		$this->command->get_logger()->remove_logger('console')->remove_logger('file');
		$this->command->get_logger()->log($this->command->get_param('content'), [
			'object' => 'un nouvel objet pour ce mail !',
			'is_log' => false,
		]);
		$this->command->get_logger()->send();
	}
}