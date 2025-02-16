<?php

namespace services\folder;

use core\services\Service;
use core\services\HttpMethod;
use core\Config;
use core\FileSystem;

require_once("../../core/autoload.php");

(new Service(false))->registerHandler(HttpMethod::GET, function() {
	$showHidden = isset($_GET['hidden']) ? htmlspecialchars($_GET['hidden']) : Config::defaultShowHidden();
	return array("root" => FileSystem::getRootFolder($showHidden));
})->buildResponse();
