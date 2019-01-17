<?php

namespace Dframe\custom;


class Typage extends \ModuleLoader {
	protected $regex = '/(\$([a-zA-Z0-9\_]+)\ ?=\ ?)?(public|private|protected)?\ ?(static)?\ ?function[\ ]?([a-zA-Z0-9\_]+)?[\ ]?\(([^)]{0,})\)([\ ]?:[\ ]?[a-zA-Z0-9\\\\\_]+)\ ?\{/m';
	protected $regex_count_funcs = '/(\$([a-zA-Z0-9\_]+)\ ?=\ ?)?\ ?(public|private|protected)?\ ?(static)?\ ?function\ ?([a-zA-Z0-9\_]+)?\ ?\(([^)]{0,})\)([\ ]?:\ ?[a-zA-Z0-9\\\\\_]+)?\ ?\{/m';

	protected $functions = [];
	protected $nb_functions = 0;

	protected $file_content;

	private static $types = [
		'file' => 'check_file',
		'files_in_dir' => 'check_all_files_in_dir'
	];

	const FILE_TYPE = 'file';
	const FILES_IN_DIR_TYPE = 'files_in_dir';

	public function __construct($bootstrap = null) {}

	/**
	 * @param string $type
	 * @param string $path
	 * @return bool
	 * @throws \Dframe\Loader\Exceptions\LoaderException
	 */
	public static function check(string $type, string $path): bool {
		return (in_array($type, array_keys(self::$types))) ? (new Typage())->{self::$types[$type]}($path) : false;
	}

	public function set_file_content(string $file): void {
		$this->file_content = is_null(realpath($file)) ? '' : file_get_contents(realpath($file));
	}

	private function get_nb_functions(): int {
		preg_match_all($this->regex_count_funcs, $this->file_content, $matches);
		$nb_functions = !empty($matches[0]) ? count($matches[0]) : 0;
		if(strstr($this->file_content, '__construct')) {
			if($nb_functions > 0) $nb_functions--;
		}
		return $nb_functions;
	}

	public function check_file(string $file): bool {
		$this->set_file_content($file);
		$nb_functions = $this->get_nb_functions();

		preg_match_all($this->regex, $this->file_content, $matches);
		if (!empty($matches)) {
			if (count($matches[0]) === $nb_functions) {
				unset($matches[0]);
				unset($matches[1]);
				$count       = count($matches[2]);
				$func_name   = [];
				$func_return = [];
				$func_args   = [];

				for ($i = 0; $i < $count; $i++) {
					if ($matches[2][$i] !== '' && $matches[2][$i] !== '__construct') {
						$func_name[] = $matches[2][$i];
					} elseif ($matches[5][$i] !== '' && $matches[3][$i] !== '__construct') {
						$func_name[] = $matches[5][$i];
					} else {
						$func_name[] = '';
					}

					$func_args[] = explode(', ', $matches[6][$i]);

					$func_return[] = str_replace([':', ' '], '', $matches[7][$i]);
				}

				foreach ($func_args as $id => $arg) {
					$args = [];
					foreach ($arg as $_id => $_arg) {
						if($_arg !== '') {
							$type = explode(' $', $_arg)[0];
							$name = isset(explode(' = ', explode(' $', $_arg)[1])[1]) ? explode(' = ', explode(' $', $_arg)[1])[0] : explode(' $', $_arg)[1];
							$default = isset(explode(' = ', explode(' $', $_arg)[1])[1]) ? explode(' = ', explode(' $', $_arg)[1])[1] : '';
							$args[] = [
								'type'    => $type,
								'name'    => $name,
								'default' => $default,
							];
						}
					}
					foreach ($args as $_id => $_arg) {
						if (substr($_arg['default'], 0, 1) === "'" || substr($_arg['default'], 0, 1) === '"') {
							$args[$_id]['default'] = substr($_arg['default'], 1, strlen($_arg['default']) - 2);
						}
						$parsed = (int)$_arg['default'];
						if (($_arg['type'] === 'int') && gettype($parsed) === 'integer') {
							$args[$_id]['default'] = $parsed;
						}
					}
					$func_args[$id] = $args;
				}
				return true;
			}
		}
		return false;
	}

	public function check_all_files_in_dir(string $path): bool {
		if(is_dir($path)) {
			$dir = opendir($path);
			while (($element = readdir($dir)) !== false) {
				if($element !== '.' && $element !== '..') {
					if(is_dir($path.'/'.$element)) {
						$dir_check = $this->check_all_files_in_dir($path.'/'.$element);
						if(!$dir_check) return false;
					}
					else {
						$file_check = $this->check_file($path.'/'.$element);
						if(!$file_check) return false;
					}
				}
			}
			return true;
		}
		return false;
	}
}