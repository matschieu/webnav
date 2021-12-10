<?php

/**
 *
 * @author Matschieu
 */
class FileSystem {

	const ROOT = "/";
	const SELF_FOLDER = ".";
	const PARENT_FOLDER = "..";

	const HTACCESS = ".htaccess";
	const HTPASSWD = ".htpasswd";

	const FILE_DOCUMENT_EXTENSIONS = array("txt", "pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx");
	const FILE_IMAGE_EXTENSIONS = array("tif", "tiff", "gif", "jpeg", "jpg", "jif", "jfif", "jp2", "jpx", "j2k", "j2c", "fpx", "pcd", "png", "bmp");
	const FILE_VIDEO_EXTENSIONS = array("webm", "mkv", "flv", "flv", "vob", "ogv", "ogg", "drc", "gif", "gifv", "mng", "avi", "mov", "qt", "wmv", "yuv", "rm", "rmvb", "asf", "amv", "mp4", "m4p", "m4v", "mpg", "mp2", "mpeg", "mpe", "mpv", "mpg", "mpeg", "m2v", "m4v", "svi", "3gp", "3g2", "mxf", "roq", "nsv", "flv", "f4v", "f4p", "f4a", "f4b");
	const FILE_MUSIC_EXTENSIONS = array("3gp", "aa", "aac", "aax", "act", "aiff", "amr", "ape", "au", "awb", "dct", "dss", "dvf", "flac", "gsm", "iklax", "ivs", "m4a", "m4b", "m4p", "mmf", "mp3", "mpc", "msv", "ogg", "oga", "opus", "ra", "rm", "raw", "sln", "tta", "vox", "wav", "wma", "wv", "webm");
	const FILE_EXECUTABLE_EXTENSIONS = array("action", "apk", "app", "bat", "bin", "cmd", "com", "command", "cpl", "csh", "exe", "gadget", "inf1", "ins", "inx", "ipa", "isu", "job", "jse", "ksh", "lnk", "msc", "msi", "msp", "mst", "osx", "out", "paf", "pif", "prg", "ps1", "reg", "rgs", "run", "scr", "sct", "shb", "shs", "u3p", "vb", "vbe", "vbs", "vbscript", "workflow", "ws", "wsf", "wsh");
	const FILE_COMPRESSED_EXTENSIONS = array("bz2", "f", "gz", "lz", "lzma", "lzo", "rz", "sfark", "sz", "xz", "z", "Z", "infl", "7z", "s7z", "ace", "afa", "alz", "apk", "arc", "arj", "b1", "ba", "bh", "cab", "car", "cfs", "cpt", "dar", "dd", "dgc", "dmg", "ear", "gca", "ha", "hki", "ice", "jar", "kgb", "lzh", "lha", "lzx", "pak", "partimg", "paq6", "paq7", "paq8", "pea", "pim", "pit", "qda", "rar", "rk", "sda", "sea", "sen", "sfx", "shk", "sit", "sitx", "sqx", "tar.gz", "tgz", "tar.Z", "tar.bz2", "tbz2", "tar.lzma", "tlz", "uc", "uc0", "uc2", "ucn", "ur2", "ue2", "uca", "uha", "war", "wim", "xar", "xp3", "yz1", "zip", "zipx", "zoo", "zpaq", "zz");
	const FILE_ARCHIVE_EXTENSIONS = array("a", "ar", "cpio", "shar", "lbr", "iso", "lbr", "mar", "tar");

	const GLYPHICON_FOLDER = "oi-folder";
	const GLYPHICON_DEFAULT_FILE = "oi-file";
	const GLYPHICON_DOCUMENT = "oi-document";
	const GLYPHICON_IMAGE = "oi-image";
	const GLYPHICON_VIDEO = "oi-video";
	const GLYPHICON_MUSIC = "oi-musical-note";
	const GLYPHICON_EXECUTABLE = "oi-cog";
	const GLYPHICON_COMPRESSED = "oi-briefcase";
	const GLYPHICON_ARCHIVE = "oi-box";

	/**
	 *
	 * @return string
	 */
	public static function getGlyphicon(File $file): string {
		if (is_dir($file->getPath())) {
			return self::GLYPHICON_FOLDER;
		}

		if (in_array($file->getExtension(), FileSystem::FILE_DOCUMENT_EXTENSIONS)) {
			return self::GLYPHICON_DOCUMENT;
		}
		if (in_array($file->getExtension(), FileSystem::FILE_IMAGE_EXTENSIONS)) {
			return self::GLYPHICON_IMAGE;
		}
		if (in_array($file->getExtension(), FileSystem::FILE_VIDEO_EXTENSIONS)) {
			return self::GLYPHICON_VIDEO;
		}
		if (in_array($file->getExtension(), FileSystem::FILE_MUSIC_EXTENSIONS)) {
			return self::GLYPHICON_MUSIC;
		}
		if (in_array($file->getExtension(), FileSystem::FILE_EXECUTABLE_EXTENSIONS)) {
			return self::GLYPHICON_EXECUTABLE;
		}
		if (in_array($file->getExtension(), FileSystem::FILE_COMPRESSED_EXTENSIONS)) {
			return self::GLYPHICON_COMPRESSED;
		}
		if (in_array($file->getExtension(), FileSystem::FILE_ARCHIVE_EXTENSIONS)) {
			return self::GLYPHICON_ARCHIVE;
		}

		return self::GLYPHICON_DEFAULT_FILE;
	}

	/**
	 *
	 * @return string
	 */
	public static function getLogicalRoot(): string {
		if (substr(Config::FILE_SYSTEM_ROOT, 0, 1) !== self::ROOT) {
			return dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR . Config::FILE_SYSTEM_ROOT;
		}

		return SELF::ROOT;
	}

	/**
	 *
	 * @return string
	 */
	public static function getRoot(): string {
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
	public static function getRootFolder(): Folder {
		$path = Config::FILE_SYSTEM_ROOT;

		// If the root is a relative path, then the path is transformed to an absolute path
		if (substr(Config::FILE_SYSTEM_ROOT, 0, 1) !== self::ROOT) {
			$path = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR . Config::FILE_SYSTEM_ROOT);
		}

		return new Folder($path, self::ROOT);
	}

	/**
	 *
	 * @param Folder $folder
	 * @return boolean
	 */
	public static function isRoot(Folder $folder): bool {
		return $folder !== null && $folder->getPath() === FileSystem::getRoot();
	}

	/**
	 *
	 * @param string $logicalPath
	 * @return Folder
	 */
	final public static function getCurrentFolder(?string $logicalPath): Folder {
		$rootPath = FileSystem::getRoot();

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
