<?php

namespace services\folder;

use core\services\Service;
use core\services\HttpMethod;
use core\Config;
use core\FileSystem;
use core\FileSort;

require_once("../../core/autoload.php");

(new Service(false))->registerHandler(HttpMethod::GET, function() {
	$showHidden = isset($_GET['hidden']) ? htmlspecialchars($_GET['hidden']) : Config::defaultShowHidden();
	$location = isset($_GET['path']) ? htmlspecialchars($_GET['path']) : FileSystem::getRootFolder($showHidden);
	$fileSort = isset($_GET['sort']) ? FileSort::tryFrom($_GET['sort']) ?? FileSort::NameAscending : FileSort::NameAscending;

	return array("folder" => FileSystem::getFolderFromLogicalPath($location, $showHidden, $fileSort));
})->buildResponse();
