<?php
namespace core;

/**
 *
 * @author Matschieu
 */
final class FileSystem {

	const ROOT = "/";

	const SELF_FOLDER = ".";
	const PARENT_FOLDER = "..";

	private static Folder $rootFolder;

	/**
	 *
	 * @param FileSort $fileSort
	 * @return int
	 */
	final private static function getScandirSort(FileSort $fileSort) : int {
		switch ($fileSort) {
			case FileSort::NameAscending:
				return SCANDIR_SORT_ASCENDING;
			case FileSort::NameDescending:
				return SCANDIR_SORT_DESCENDING;
			default:
				return SCANDIR_SORT_NONE;
		}
	}

	/**
	 *
	 * @return string
	 */
	final public static function getInstallationFolder(): ?string {
		return dirname($_SERVER['SCRIPT_FILENAME']);
	}

	/**
	 *
	 * @param string $path
	 * @return string
	 */
	final public static function getLogicalPath(string $path): string {
		$rootPathes = array(self::getRoot() . DIRECTORY_SEPARATOR, self::getRoot());
		return str_replace($rootPathes, self::ROOT, $path);
	}

	/**
	 *
	 * @return string
	 */
	final public static function getLogicalRoot(): string {
		if (substr(Config::fileSystemRoot(), 0, 1) !== self::ROOT) {
			return dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR . Config::fileSystemRoot();
		}

		return SELF::ROOT;
	}

	/**
	 *
	 * @return string
	 */
	final public static function getRoot(): string {
		// If the root is a relative path, then the path is transformed to an absolute path
		if (substr(Config::fileSystemRoot(), 0, 1) !== self::ROOT) {
			return realpath(dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . Config::fileSystemRoot());
		}

		return Config::fileSystemRoot();
	}

	/**
	 *
	 * @param bool $includeHidden
	 * @return Folder
	 */
	final public static function getRootFolder(bool $includeHidden = false): Folder {
		if (!isset(self::$rootFolder)) {
			$path = Config::fileSystemRoot();

			// If the root is a relative path, then the path is transformed to an absolute path
			if (substr(Config::fileSystemRoot(), 0, 1) !== self::ROOT) {
				$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . Config::fileSystemRoot());
			}

			self::$rootFolder = new Folder($path, self::ROOT, true, self::getFolderChildren($path, $includeHidden), self::getFileChildren($path, $includeHidden));
		}

		return self::$rootFolder;
	}

	/**
	 *
	 * @param string $logicalPath
	 * @param bool $includeHidden
	 * @param FileSort $fileSort
	 * @return Folder
	 */
	final public static function getFolderFromLogicalPath(?string $logicalPath, bool $includeHidden = false, FileSort $fileSort = FileSort::NameAscending): Folder {
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

		return new Folder($path, "/", false, self::getFolderChildren($path, $includeHidden, $fileSort), self::getFileChildren($path, $includeHidden, $fileSort));
	}

	/**
	 *
	 * @param number $fileSize
	 * @return string
	 */
	final public static function convertSize(?int $fileSize): string {
		$sizeUnit = Translation::get("filesize.byte");

		if ($fileSize > 1) {
			$sizeUnit .= "s";
		}

		if ($fileSize / (1024 * 1024) >= 1024) {
			$fileSize = round($fileSize / (1021 * 1024 * 1024), 2);
			$sizeUnit = Translation::get("filesize.gb");
		} else if ($fileSize / 1024 >= 1024) {
			$fileSize = round($fileSize / (1024 * 1024), 2);
			$sizeUnit = Translation::get("filesize.mb");
		} else if ($fileSize >= 1024) {
			$fileSize = round($fileSize / 1024, 2);
			$sizeUnit = Translation::get("filesize.kb");
		}

		return !empty($fileSize) ? $fileSize . " " . $sizeUnit : "";
	}

	/**
	 *
	 * @param Folder $folder
	 * @param int $level
	 * @return array
	 */
	final public static function getFlatTree(Folder $folder = null, int $level = 0): array {
		$folderArray = array();

		if (isset($folder)) {
			if ($folder->getName() !== self::PARENT_FOLDER) {
				$folderArray = array(new TreeElement($level, $folder));
				if ($folder->getChildrenCount() > 0) {
					foreach ($folder->getFolderChildren() as $child) {
						$folderArray = array_merge($folderArray, self::getFlatTree($child, $level + 1));
					}
				}
			}
		}

		return $folderArray;
	}

	/**
	 *
	 * @param String $path
	 * @return bool
	 */
	final public static function isValidFolder(String $path): bool {
		return file_exists($path) && is_dir($path);
	}

	/**
	 *
	 * @param String $path
	 * @return bool
	 */
	final public static function isValidFile(String $path): bool {
		return file_exists($path) && !is_dir($path);
	}

	/**
	 *
	 * @return bool
	 */
	final public static function isRoot(String $path): bool {
		return $path === FileSystem::getRoot();
	}

	/**
	 *
	 * @param string $path
	 * @param bool $includeHidden
	 * @param FileSort $fileSort
	 * @return array
	 */
	final public static function getFolderChildren(string $path, bool $includeHidden, FileSort $fileSort = FileSort::NameAscending): array {
		if (!self::isValidFolder($path)) {
			return array();
		}

		$folderList = array();

		foreach(scandir($path, self::getScandirSort($fileSort)) as $file) {
			$filePath = $path . DIRECTORY_SEPARATOR . $file;
			$appFolder = self::getInstallationFolder();

			// If the folder is the one where the application is installed, it's not added to the list
			if ($appFolder === $filePath) {
				continue;
			}

			switch($file) {
				case "":
				case self::SELF_FOLDER:
					break;
				case self::PARENT_FOLDER:
					if (self::isRoot($path)) {
						break;
					}
					if (file_exists($filePath) && is_dir($filePath)) {
						array_push($folderList, new Folder($filePath, $file));
					}
					break;
				default:
					if (!$includeHidden && substr($file, 0, 1) === "." && $file !== self::PARENT_FOLDER) {
						continue;
					}
					if (file_exists($filePath) && is_dir($filePath)) {
						array_push($folderList, new Folder($filePath, $file, false, self::getFolderChildren($filePath, $includeHidden, $fileSort), self::getFileChildren($filePath, $includeHidden, $fileSort)));
					}
			}
		}

		return $folderList;
	}

	/**
	 *
	 * @param string $path
	 * @param bool $includeHidden
	 * @param FileSort $fileSort
	 * @return array
	 */
	final public static function getFileChildren(string $path, bool $includeHidden, FileSort $fileSort = FileSort::NameAscending): array {
		if (!self::isValidFolder($path)) {
			return array();
		}

		$fileList = array();

		foreach(scandir($path, self::getScandirSort($fileSort)) as $file) {
			switch($file) {
				case "":
				case self::SELF_FOLDER:
				case self::PARENT_FOLDER:
					continue;
				default:
					if (!$includeHidden && substr($file, 0, 1) === ".") {
						continue;
					}

					$filePath = $path . DIRECTORY_SEPARATOR . $file;

					if (file_exists($filePath) && !is_dir($filePath)) {
						array_push($fileList, new File($filePath, $file));
					}
			}
		}

		return $fileList;
	}
}
