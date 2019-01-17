<?php

namespace loggers;

use Dframe\custom\Logger\traits\log;

class console {
	use log;

	public function log($msg, $params = []) {
		echo $this->get_date_text_format().$this->get_complete_domain().'=> '.$msg."\n";
	}

	public function send() {}
}