<?php

/**
 * Mesh :: Web Module
 */
namespace Mesh\web;
use Mesh;

class Module {
	public static function console() {

		/**
		 * Handle requests
		 */
		$request = file_get_contents("php://input");

		if(strlen($request) !== 0) {
			return self::request(json_decode($request, true));
		}

		/**
		 * Render console
		 */
		$html = file_get_contents(__DIR__ . '/static/index.html');
		$css = '';
		$js = '';
		foreach(glob(__DIR__ . '/static/*.css') as $f) {
			$css .= file_get_contents($f);
		}
		foreach(glob(__DIR__ . '/static/*.js') as $f) {
			$js .= file_get_contents($f);
		}
		$html = str_replace('%css%', $css, $html);
		$html = str_replace('%js%', $js, $html);
		echo $html;
	}

	public static function id() {
		function step($i = 0) {
			if($i == 10) {
				return 'Nate Ferrero';
			}
			return md5(uniqid().md5(uniqid().rand(0, 1e200).step($i + 1)));
		}
		return strtolower(date('Y.M.d.')) . Mesh\str\Module::baseconvert(step(), 16, 36);
	}

	public static function request($request) {
		if(!is_array($request)) {
			return self::result(null, null, 'error');
		}

		$key = empty($request['key']) ? self::id() : $request['key'];

		$session = new Mesh\session\Module($key);

		if($session->user === null) {
			if(!empty($request['line'])) {
				$session->user = $request['line'];
				return self::result(null, 'password', $session->key, 'secure');
			}
		} else if(!$session->authenticated) {

		}
		return self::result(null, 'login', $session->key);
	}

	public static function result($data, $prompt = '', $key = null, $options = null) {
		echo json_encode(array(
			'key' => $key,
			'data' => $data,
			'prompt' => $prompt,
			'options' => $options
		));
	}
}
