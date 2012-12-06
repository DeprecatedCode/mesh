<?php

/**
 * Mesh :: Session Module
 */
namespace Mesh\session;
use Mesh;
use PDO;

class Module {

	private $_db;

	public function __construct($key) {
		$parts = explode('.', $key);
		$mini = "$parts[0].$parts[1].$parts[2].".substr($parts[3], 5, 10);
		$file = __DIR__ . "/../../data/$parts[0]/$parts[1]/$mini.mesh";
		$dir = dirname($file);
		if(!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}
		$this->_db = new PDO("sqlite:$file");
		$this->key = $key;
	}

	public function __get($var) {

	}

	public function __set($var, $value) {

	}

}