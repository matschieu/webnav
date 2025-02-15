<?php

const PHP_EXT = ".php";
const DEBUG = false;

// This can be used anywhere to identify the root folder of the app
define("APP_ROOT", realpath(dirname(__FILE__)."/../"));

spl_autoload_register('autoloader');

/**
 *
 * @param string $filepath
 * @param string $classname
 * @return bool
 */
function scan(string $filepath, ?string $classname): bool {
	if (is_dir($filepath)) {
		// replacing \ by / is used to manage the namespaces
		$filename = $filepath . DIRECTORY_SEPARATOR . str_replace('\\', '/', $classname) . PHP_EXT;

		if (DEBUG) {
			echo "Trying to load ".$filename."<br>";
		}

		if (is_file($filename)) {
			@include_once($filename);
			return true;
		}

		foreach (scandir($filepath) as $file) {
			if ($file === "." || $file === "..") {
				continue;
			}

			if (scan($filepath . DIRECTORY_SEPARATOR . $file, $classname)) {
				return true;
			}
		}
	}

	return false;
}

/**
 *
 * @param string $classname
 * @return bool
 */
function autoloader(?string $classname): bool {
	if (!isset($classname) || $classname == null) {
		return false;
	}

	if (DEBUG) {
		echo "Autoload ".$classname."<br>";
	}

	// Found using namespace
	foreach (array("", "/src/core", "/tests/core", "core", "tests") as $path) {
		$r = scan(APP_ROOT.$path, $classname);

		if ($r) {
			if (DEBUG) {
				echo "Found<br>";
			}

			return true;
		}
	}

	if (DEBUG) {
		echo "Not found<br>";
	}

	return false;
}
