<?php

/**
 * Mesh
 */
const VERSION = "0.0.1";

/**
 * Ensure proper PHP version is installed
 */
if(PHP_VERSION_ID < 50304) {
	die('PHP version 5.3.4 or greater is required to use Mesh, you are using PHP ' . PHP_VERSION);
}

function autoload($class) {
	$info = explode('\\', $class);
	$root = array_shift($info);
	$file = strtolower(implode('/', $info));
	if($root === 'Mesh') {
		require_once(__DIR__ . "/../modules/$file.php");
		if(!class_exists($class)) {
			throw new Exception("Class $class not found");
		}
	}
}

spl_autoload_register('autoload');

function err($errno, $errstr, $errfile, $errline) {
	throw new \ErrorException("$errstr on line $errline of $errfile");
}

set_error_handler('err');

try {
	/**
	 * Handle command line arguments
	 */
	if(isset($argv)) {
		$cmd = array_shift($argv);
		while($arg = array_shift($argv)) {
			switch($arg) {
				case '-h':
				case '-help':
				case '--help':
					return Mesh\help\Module::help($argv);
				default:
					Mesh\ml\Module::file($arg);
			}
		}
	} else {
		Mesh\web\Module::console();
	}
}
catch(Exception $e) {
	echo "<pre>" . $e->getMessage();
	if(!($e instanceof ErrorException)) {
		echo " on line " . $e->getLine() . " of " . $e->getFile();
	}
	echo "</pre>";
}
