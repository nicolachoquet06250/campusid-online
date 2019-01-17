<?php

namespace tools;


class http {

	public function get($key = null, $value = null) {
		if(!is_null($value)) $_GET[$key] = $value;
		if(is_null($key)) return $_GET;
		return isset($_GET[$key]) ? $_GET[$key] : null;
	}

	public function post($key, $value = null) {
		if(!is_null($value)) $_POST[$key] = $value;
		if(is_null($key)) return $_POST;
		return isset($_POST[$key]) ? $_POST[$key] : null;
	}

	public function files($key, $value = null) {
		if(!is_null($value)) $_FILES[$key] = $value;
		if(is_null($key)) return $_FILES;
		return isset($_FILES[$key]) ? $_FILES[$key] : null;
	}

}