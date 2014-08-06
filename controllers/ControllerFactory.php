<?php

require_once 'controllers/MainController.php';
require_once 'controllers/Controller.php';
require_once 'controllers/ApiController.php';

class ControllerFactory {

	private function __construct() {}

	public static function getController($api = false, $config) {
		if ($api) {
			return new ApiController($config);
		}
		return new Controller($config);
	}
}