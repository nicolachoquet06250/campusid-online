<?php

namespace loggers;

use Dframe\custom\Logger\traits\log;

class file {
	use log;
	private $path = __DIR__.'/../logs';
	private $file;
	private $messages = [];

	public function __construct(array $options) {
		if(isset($options['path'])) $this->path = $options['path'];
		$this->file = $options['file'];
	}

	public function log($msg, $params = []) {
		$this->messages[] = $msg;
	}

	public function send() {
		if(!is_dir($this->path)) mkdir($this->path, 0777, true);
		$old_content = file_exists($this->path.'/'.$this->file.'.log')
			? file_get_contents($this->path.'/'.$this->file.'.log') : '';
		$new_lines = '';
		foreach ($this->messages as $msg) {
			$new_line = $this->get_date_text_format().$this->get_complete_domain().'=> '.$msg."\n";
			$new_lines .= $new_line;
		}
		file_put_contents($this->path.'/'.$this->file.'.log', $old_content.$new_lines);
	}
}