<?php

$dir = opendir(__DIR__);

while (($file = readdir($dir)) !== false) {
	if($file !== '.' && $file !== '..' && $file !== 'autoload.php') {
		if(is_file(__DIR__.'/'.$file)) {
			require_once __DIR__.'/'.$file;
		}
		elseif (is_dir(__DIR__.'/'.$file)) {
			$_dir = opendir(__DIR__.'/'.$file);

			while (($_file = readdir($_dir)) !== false) {
				if($_file !== '.' && $_file !== '..') {
					if(is_file(__DIR__.'/'.$file.'/'.$_file)) {
						require_once __DIR__.'/'.$file.'/'.$_file;
					}
					elseif (is_dir(__DIR__.'/'.$file.'/'.$_file)) {
						$__dir = opendir(__DIR__.'/'.$file.'/'.$_file);

						while (($__file = readdir($__dir)) !== false) {
							if($__file !== '.' && $__file !== '..') {
								if(is_file(__DIR__.'/'.$file.'/'.$_file.'/'.$__file)) {
									require_once __DIR__.'/'.$file.'/'.$_file.'/'.$__file;
								}
							}
						}
					}
				}
			}
		}
	}
}