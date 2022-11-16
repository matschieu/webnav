<?php

const PHP_EXT = ".php";
const CURRENT_FOLDER = ".";
const DEBUG = false;

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
			echo "&nbsp;".$filename."<br>";
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
		echo "*".$classname."<br>";
	}

	// Found using namespace
	$r = scan(CURRENT_FOLDER, $classname);
	if (!$r) {
		// found by searching explicitly in the core/ folder
		$r = scan(CURRENT_FOLDER . DIRECTORY_SEPARATOR . "core", $classname);
	}

	return $r;
}
