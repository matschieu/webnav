<?php

/**
 *
 * @author Matschieu
 */
final class FileSystem {

	const ROOT = "/";

	private static Folder $rootFolder;

	/**
	 *
	 * @param string $path
	 * @return string
	 */
	final public function getLogicalPath(string $path): string {
		$rootPathes = array(self::getRoot() . DIRECTORY_SEPARATOR, self::getRoot());
		return str_replace($rootPathes, self::ROOT, $path);
	}

	/**
	 *
	 * @return string
	 */
	final public static function getLogicalRoot(): string {
		if (substr(Config::FILE_SYSTEM_ROOT, 0, 1) !== self::ROOT) {
			return dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR . Config::FILE_SYSTEM_ROOT;
		}

		return SELF::ROOT;
	}

	/**
	 *
	 * @return string
	 */
	final public static function getRoot(): string {
		// If the root is a relative path, then the path is transformed to an absolute path
		if (substr(Config::FILE_SYSTEM_ROOT, 0, 1) !== self::ROOT) {
			return realpath(dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . Config::FILE_SYSTEM_ROOT);
		}

		return Config::FILE_SYSTEM_ROOT;
	}

	/**
	 *
	 * @return Folder
	 */
	final public static function getRootFolder(): Folder {
		if (!isset(self::$rootFolder)) {
			$path = Config::FILE_SYSTEM_ROOT;

			// If the root is a relative path, then the path is transformed to an absolute path
			if (substr(Config::FILE_SYSTEM_ROOT, 0, 1) !== self::ROOT) {
				$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . Config::FILE_SYSTEM_ROOT);
			}

			self::$rootFolder = new Folder($path, self::ROOT);
		}

		return self::$rootFolder;
	}

	/**
	 *
	 * @param string $logicalPath
	 * @return Folder
	 */
	final public static function getFolderFromLogicalPath(?string $logicalPath): Folder {
		$rootPath = self::getRoot();

		// If a logical path is specified in the URL and is valid then it's added to the path.
		if (realpath($rootPath . $logicalPath)) {
			$path = realpath($rootPath . $logicalPath);
		} else {
			$path = $rootPath;
		}

		// Security: check that the root + logical path is not above the root, else using the root
		if (strpos($path, $rootPath) === FALSE) {
			$path = $rootPath;
		}

		return new Folder($path, "/");
	}

	/**
	 *
	 * @param number $fileSize
	 * @return string
	 */
	final public static function convertSize(?int $fileSize): string {
		$sizeUnit = "octet";

		if ($fileSize > 1) {
			$sizeUnit .= "s";
		}

		if ($fileSize / (1024 * 1024) >= 1024) {
			$fileSize = round($fileSize / (1021 * 1024 * 1024), 2);
			$sizeUnit = "Go";
		} else if ($fileSize / 1024 >= 1024) {
			$fileSize = round($fileSize / (1024 * 1024), 2);
			$sizeUnit = "Mo";
		} else if ($fileSize >= 1024) {
			$fileSize = round($fileSize / 1024, 2);
			$sizeUnit = "Ko";
		}

		return !empty($fileSize) ? $fileSize . " " . $sizeUnit : "";
	}

}
