<?php

class Loader {
	protected $charged = [];
	/**
	 * @param $name
	 * @param $arguments
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($name, $arguments) {
		foreach ($this->charged as $n => $detail)
			if(strstr($name, $detail['getter']))
				return $this->{$detail['callback']}($name);
		return null;
	}
}