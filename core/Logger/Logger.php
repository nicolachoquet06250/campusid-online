<?php

namespace Dframe\custom\Logger;

use Dframe\custom\Logger\traits\log;
use loggers\console;
use loggers\file;
use loggers\mail;

class Logger {
	private $types     = [
		'console' => console::class,
		'mail'    => mail::class,
		'file'    => file::class
	];
	private $logs_type = [];
	private $instanciate_loggers = [];

	/**
	 * Logger constructor.
	 *
	 * @param array|string|null $types
	 */
	public function __construct($types = null) {
		if (!is_null($types)) {
			if (is_string($types))
				$this->logs_type[] = $types;
			else foreach ($types as $type)
				$this->add_logger($type);
		}
	}

	/**
	 * @param $type
	 * @return Logger
	 */
	public function remove_logger($type) {
		if (isset($this->logs_type[$type]))
			unset($this->logs_type[$type]);
		return $this;
	}

	/**
	 * @param array $types
	 * @return $this
	 */
	public function remove_loggers(array $types) {
		foreach ($types as $type)
			$this->remove_logger($type);
		return $this;
	}

	/**
	 * @param $type
	 * @param array $options
	 * @return $this
	 */
	public function add_logger($type, $options = []) {
		if (in_array($type, array_keys($this->types))) {
			$this->logs_type[$type] = $options;
			$log_class  = $this->types[$type];
			$logger_dir = __DIR__.'/../../'.ucfirst(str_replace('\\', '/', $log_class)).'.php';
			require_once $logger_dir;
			/** @var log $logger */
			$logger = empty($options) ? new $log_class() : new $log_class($options);
			$this->instanciate_loggers[$type] = $logger;
		}
		return $this;
	}

	/**
	 * @param array $types
	 * @return Logger
	 */
	public function add_loggers(array $types) {
		foreach ($types as $type => $options)
			$this->add_logger($type, $options);
		return $this;
	}

	public function log($msg = '') {
		foreach ($this->instanciate_loggers as $logger) {
			$logger->log($msg);
		}
	}

	public function send() {
		/** @var log $logger */
		foreach ($this->instanciate_loggers as $logger) {
			$logger->send();
		}
	}
}