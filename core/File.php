<?php
namespace core;

/**
 * @author Matschieu
 */
class File {

	protected string $path;
	protected string $logicalPath;
	protected string $name;
	protected ?int $size;
	protected ?string $date;
	protected ?string $extension;
	protected string $glyphicon;
	protected string $url;
	protected bool $image;

	/**
	 *
	 * @return string
	 */
	private function determineGlyphicon(): string {
		switch ($this->getExtension()) {
			case "cfg":
			case "json":
			case "properties":
			case "txt":
			case "yml":
				return "fa-solid fa-file-lines";
			case "pdf":
				return "fa-solid fa-file-pdf";
			case "csv":
				return "fa-solid fa-file-csv";
			case "doc":
			case "docx":
				return "fa-solid fa-file-word";
			case "xls":
			case "xlsx":
				return "fa-solid fa-file-excel";
			case "ppt":
			case "pptx":
				return "fa-solid fa-file-powerpoint";
			case "tif":
			case "tiff":
			case "gif":
			case "jpeg":
			case "jpg":
			case "jif":
			case "jfif":
			case "jp2":
			case "jpx":
			case "j2k":
			case "j2c":
			case "fpx":
			case "pcd":
			case "png":
			case "bmp":
				return "fa-solid fa-file-image";
			case "webm":
			case "mkv":
			case "flv":
			case "flv":
			case "vob":
			case "ogv":
			case "ogg":
			case "drc":
			case "gif":
			case "gifv":
			case "mng":
			case "avi":
			case "mov":
			case "qt":
			case "wmv":
			case "yuv":
			case "rm":
			case "rmvb":
			case "asf":
			case "amv":
			case "mp4":
			case "m4p":
			case "m4v":
			case "mpg":
			case "mp2":
			case "mpeg":
			case "mpe":
			case "mpv":
			case "mpg":
			case "mpeg":
			case "m2v":
			case "m4v":
			case "svi":
			case "3gp":
			case "3g2":
			case "mxf":
			case "roq":
			case "nsv":
			case "flv":
			case "f4v":
			case "f4p":
			case "f4a":
			case "f4b":
				return "fa-solid fa-file-video";
			case "3gp":
			case "aa":
			case "aac":
			case "aax":
			case "act":
			case "aiff":
			case "amr":
			case "ape":
			case "au":
			case "awb":
			case "dct":
			case "dss":
			case "dvf":
			case "flac":
			case "gsm":
			case "iklax":
			case "ivs":
			case "m4a":
			case "m4b":
			case "m4p":
			case "mmf":
			case "mp3":
			case "mpc":
			case "msv":
			case "ogg":
			case "oga":
			case "opus":
			case "ra":
			case "rm":
			case "raw":
			case "sln":
			case "tta":
			case "vox":
			case "wav":
			case "wma":
			case "wv":
			case "webm":
				return "fa-solid fa-file-audio";
			case "c":
			case "cpp":
			case "c++":
			case "cs":
			case "css":
			case "html":
			case "java":
			case "js":
			case "jsp":
			case "ksh":
			case "php":
			case "py":
			case "sh":
			case "sql":
			case "ts":
			case "xml":
				return "fa-solid fa-file-code";
			case "action":
			case "apk":
			case "app":
			case "bat":
			case "bin":
			case "cmd":
			case "com":
			case "command":
			case "cpl":
			case "csh":
			case "exe":
			case "gadget":
			case "inf1":
			case "ins":
			case "inx":
			case "ipa":
			case "isu":
			case "job":
			case "jse":
			case "ksh":
			case "lnk":
			case "msc":
			case "msi":
			case "msp":
			case "mst":
			case "osx":
			case "out":
			case "paf":
			case "pif":
			case "prg":
			case "ps1":
			case "reg":
			case "rgs":
			case "run":
			case "scr":
			case "sct":
			case "shb":
			case "shs":
			case "u3p":
			case "vb":
			case "vbe":
			case "vbs":
			case "vbscript":
			case "workflow":
			case "ws":
			case "wsf":
			case "wsh":
				return "fa-solid fa-terminal";
			case "bz2":
			case "f":
			case "gz":
			case "lz":
			case "lzma":
			case "lzo":
			case "rz":
			case "sfark":
			case "sz":
			case "xz":
			case "z":
			case "Z":
			case "infl":
			case "7z":
			case "s7z":
			case "ace":
			case "afa":
			case "alz":
			case "apk":
			case "arc":
			case "arj":
			case "b1":
			case "ba":
			case "bh":
			case "cab":
			case "car":
			case "cfs":
			case "cpt":
			case "dar":
			case "dd":
			case "dgc":
			case "dmg":
			case "ear":
			case "gca":
			case "ha":
			case "hki":
			case "ice":
			case "jar":
			case "kgb":
			case "lzh":
			case "lha":
			case "lzx":
			case "pak":
			case "partimg":
			case "paq6":
			case "paq7":
			case "paq8":
			case "pea":
			case "pim":
			case "pit":
			case "qda":
			case "rar":
			case "rk":
			case "sda":
			case "sea":
			case "sen":
			case "sfx":
			case "shk":
			case "sit":
			case "sitx":
			case "sqx":
			case "tar.gz":
			case "tgz":
			case "tar.Z":
			case "tar.bz2":
			case "tbz2":
			case "tar.lzma":
			case "tlz":
			case "uc":
			case "uc0":
			case "uc2":
			case "ucn":
			case "ur2":
			case "ue2":
			case "uca":
			case "uha":
			case "war":
			case "wim":
			case "xar":
			case "xp3":
			case "yz1":
			case "zip":
			case "zipx":
			case "zoo":
			case "zpaq":
			case "zz":
				return "fa-solid fa-file-zipper";
			case "a":
			case "ar":
			case "cpio":
			case "shar":
			case "lbr":
			case "iso":
			case "lbr":
			case "mar":
			case "tar":
				return "fa-solid fa-box-archive";
			case "tar":
				return "fa-solid fa-file";
			default:
				return "fa-solid fa-file";
		}
	}

	/**
	 *
	 * @return bool
	 */
	private function determineImage(): bool {
		switch ($this->getExtension()) {
			case "tif":
			case "tiff":
			case "gif":
			case "jpeg":
			case "jpg":
			case "jif":
			case "jfif":
			case "jp2":
			case "jpx":
			case "j2k":
			case "j2c":
			case "fpx":
			case "pcd":
			case "png":
			case "bmp":
				return true;
			default:
				return false;
		}
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
		$this->url = (!empty($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . FileSystem::getLogicalRoot() . $this->logicalPath;

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
		$this->image = $this->determineImage();
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
	 * @return bool
	 */
	public function isImage(): bool {
		return $this->image;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->path;
	}

}
