<?php

const PHP_EXT = ".php";

/**
 * @param String $dirpath
 * @param String $className
 */
function autoloadScan($dirpath, $className) {
	$dir = dir($dirpath);

	while (false !== ($entry = $dir->read())) {
		if ($entry !== "." && $entry !== ".." && is_dir($dir->path . DIRECTORY_SEPARATOR . $entry)) {
			autoloadScan($dir->path . DIRECTORY_SEPARATOR . $entry, $className);
		} else if ($entry === $className . PHP_EXT) {
			if (Config::DEBUG) {
				include_once($dir->path . DIRECTORY_SEPARATOR . $className . PHP_EXT);
			} else {
				@include_once($dir->path . DIRECTORY_SEPARATOR . $className . PHP_EXT);
			}

			break;
		}
	}

	$dir->close();
}

/**
 * Magic function of PHP to load a class without use "include" or "require"
 * functions
 * @param String $className
 */
function __autoload($className) {
	global $path;
	$tmp = explode(PATH_SEPARATOR, $path);
	for ($i = 0; $i < count($tmp); $i++) {
		autoloadScan($tmp[$i], $className);
	}
}

?>
