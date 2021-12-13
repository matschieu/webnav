<?php

/**
 * @author Matschieu
 */
class File {

	const FILE_DOCUMENT_EXTENSIONS = array("txt", "pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx");
	const FILE_IMAGE_EXTENSIONS = array("tif", "tiff", "gif", "jpeg", "jpg", "jif", "jfif", "jp2", "jpx", "j2k", "j2c", "fpx", "pcd", "png", "bmp");
	const FILE_VIDEO_EXTENSIONS = array("webm", "mkv", "flv", "flv", "vob", "ogv", "ogg", "drc", "gif", "gifv", "mng", "avi", "mov", "qt", "wmv", "yuv", "rm", "rmvb", "asf", "amv", "mp4", "m4p", "m4v", "mpg", "mp2", "mpeg", "mpe", "mpv", "mpg", "mpeg", "m2v", "m4v", "svi", "3gp", "3g2", "mxf", "roq", "nsv", "flv", "f4v", "f4p", "f4a", "f4b");
	const FILE_MUSIC_EXTENSIONS = array("3gp", "aa", "aac", "aax", "act", "aiff", "amr", "ape", "au", "awb", "dct", "dss", "dvf", "flac", "gsm", "iklax", "ivs", "m4a", "m4b", "m4p", "mmf", "mp3", "mpc", "msv", "ogg", "oga", "opus", "ra", "rm", "raw", "sln", "tta", "vox", "wav", "wma", "wv", "webm");
	const FILE_EXECUTABLE_EXTENSIONS = array("action", "apk", "app", "bat", "bin", "cmd", "com", "command", "cpl", "csh", "exe", "gadget", "inf1", "ins", "inx", "ipa", "isu", "job", "jse", "ksh", "lnk", "msc", "msi", "msp", "mst", "osx", "out", "paf", "pif", "prg", "ps1", "reg", "rgs", "run", "scr", "sct", "shb", "shs", "u3p", "vb", "vbe", "vbs", "vbscript", "workflow", "ws", "wsf", "wsh");
	const FILE_COMPRESSED_EXTENSIONS = array("bz2", "f", "gz", "lz", "lzma", "lzo", "rz", "sfark", "sz", "xz", "z", "Z", "infl", "7z", "s7z", "ace", "afa", "alz", "apk", "arc", "arj", "b1", "ba", "bh", "cab", "car", "cfs", "cpt", "dar", "dd", "dgc", "dmg", "ear", "gca", "ha", "hki", "ice", "jar", "kgb", "lzh", "lha", "lzx", "pak", "partimg", "paq6", "paq7", "paq8", "pea", "pim", "pit", "qda", "rar", "rk", "sda", "sea", "sen", "sfx", "shk", "sit", "sitx", "sqx", "tar.gz", "tgz", "tar.Z", "tar.bz2", "tbz2", "tar.lzma", "tlz", "uc", "uc0", "uc2", "ucn", "ur2", "ue2", "uca", "uha", "war", "wim", "xar", "xp3", "yz1", "zip", "zipx", "zoo", "zpaq", "zz");
	const FILE_ARCHIVE_EXTENSIONS = array("a", "ar", "cpio", "shar", "lbr", "iso", "lbr", "mar", "tar");

	const GLYPHICON_DEFAULT_FILE = "oi-file";
	const GLYPHICON_DOCUMENT = "oi-document";
	const GLYPHICON_IMAGE = "oi-image";
	const GLYPHICON_VIDEO = "oi-video";
	const GLYPHICON_MUSIC = "oi-musical-note";
	const GLYPHICON_EXECUTABLE = "oi-cog";
	const GLYPHICON_COMPRESSED = "oi-briefcase";
	const GLYPHICON_ARCHIVE = "oi-box";

	const HTACCESS = ".htaccess";
	const HTPASSWD = ".htpasswd";

	protected string $path;
	protected string $logicalPath;
	protected string $name;
	protected ?int $size;
	protected ?string $date;
	protected ?string $extension;
	protected string $glyphicon;
	protected string $url;

	/**
	 *
	 * @return string
	 */
	private function determineGlyphicon(): string {
		if (in_array($this->getExtension(), self::FILE_DOCUMENT_EXTENSIONS)) {
			return self::GLYPHICON_DOCUMENT;
		}
		if (in_array($this->getExtension(), self::FILE_IMAGE_EXTENSIONS)) {
			return self::GLYPHICON_IMAGE;
		}
		if (in_array($this->getExtension(), self::FILE_VIDEO_EXTENSIONS)) {
			return self::GLYPHICON_VIDEO;
		}
		if (in_array($this->getExtension(), self::FILE_MUSIC_EXTENSIONS)) {
			return self::GLYPHICON_MUSIC;
		}
		if (in_array($this->getExtension(), self::FILE_EXECUTABLE_EXTENSIONS)) {
			return self::GLYPHICON_EXECUTABLE;
		}
		if (in_array($this->getExtension(), self::FILE_COMPRESSED_EXTENSIONS)) {
			return self::GLYPHICON_COMPRESSED;
		}
		if (in_array($this->getExtension(), self::FILE_ARCHIVE_EXTENSIONS)) {
			return self::GLYPHICON_ARCHIVE;
		}

		return self::GLYPHICON_DEFAULT_FILE;
	}

	/**
	 *
	 * @param string $path
	 * @param string $name
	 */
	public function __construct(string $path, string $name) {
		$this->path = realpath($path);
		$this->name = $name;

		$this->logicalPath = FileSystem::getLogicalPath($this->path);
		$this->url = "http://" . $_SERVER['HTTP_HOST'] . FileSystem::getLogicalRoot() . $this->logicalPath;

		if ($this->isValid()) {
			$pathinfo = pathinfo($this->path);
			$this->extension =  isset($pathinfo['extension']) && ".".$pathinfo['extension'] !== $name ? strtolower($pathinfo['extension']) : null;
			$this->size = filesize($this->path);
			$this->date = date(Config::DATE_FORMAT, filectime($this->path));
		} else {
			$this->extension = null;
			$this->size = null;
			$this->date = null;
		}

		$this->glyphicon = $this->determineGlyphicon();
	}

	/**
	 *
	 * @return boolean
	 */
	public function isValid(): bool {
		return file_exists($this->path) && !is_dir($this->path);
	}

	/**
	 *
	 * @return string
	 */
	public function getPath(): string {
		return $this->path;
	}

	/**
	 *
	 * @return string
	 */
	public function getLogicalPath(): string {
		return $this->logicalPath;
	}

	/**
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 *
	 * @return number
	 */
	public function getSize(): ?int {
		return $this->size;
	}

	/**
	 *
	 * @return string
	 */
	public function getdate(): ?string {
		return $this->date;
	}

	/**
	 *
	 * @return string
	 */
	public function getExtension(): ?string {
		return $this->extension;
	}

	/**
	 *
	 * @return string
	 */
	public function getGlyphicon(): string {
		return $this->glyphicon;
	}

	/**
	 *
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->path;
	}

}
