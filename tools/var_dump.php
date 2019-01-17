<?php

namespace tools;


class var_dump {
	public function in_file($data, $path) {
		ob_start();
		var_dump($data);
		$var_dump = ob_get_contents();
		ob_clean();
		file_put_contents(__DIR__.'/../'.$path, $var_dump);
	}

	/**
	 * @param array ...$data
	 */
	public function in_text(...$data) {
		foreach ($data as $_data) {
			var_dump($_data);
		}
	}
}