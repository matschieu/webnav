<?php

namespace services;

use core\services\Service;
use core\services\HttpMethod;
use core\Config;

require_once("../core/autoload.php");

(new Service(false))->registerHandler(HttpMethod::GET, function() {
		return array("config" => Config::get());
	})->buildResponse();
