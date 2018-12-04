<?php

namespace tools;


class http {

	public function get($key = null, $value = null) {
		if(!is_null($value)) $_GET[$key] = $value;
		if(is_null($key)) return $_GET;
		return $_GET[$key];
	}

	public function post($key, $value = null) {
		if(!is_null($value)) $_POST[$key] = $value;
		if(is_null($key)) return $_POST;
		return $_POST[$key];
	}

}