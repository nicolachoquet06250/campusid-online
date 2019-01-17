<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 06/12/2018
 * Time: 21:26
 */

namespace Dframe\custom\Logger\traits;


trait log {
	protected function get_date() {
		return date('Y-m-d');
	}
	protected function get_date_text_format() {
		$date = date('[D M  d H:i:s Y] ');
		return $date;
	}
	protected function get_user_ip() {
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	}
	protected function get_port() {
		return isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '';
	}
	protected function get_complete_domain() {
		return $this->get_user_ip().($this->get_port() === '80' || $this->get_port() === '' ? '' : ':'.$this->get_port()).' ';
	}
	abstract public function log($msg, $params = []);
	abstract public function send();
}