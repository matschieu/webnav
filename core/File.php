<?php

/**
 * @author Matschieu
 */
class File {

	const GLYPHICON_FOLDER = "glyphicon-folder-open";
	const GLYPHICON_DEFAULT_FILE = "glyphicon-file";
	const GLYPHICON_IMAGE = "glyphicon-picture";
	const GLYPHICON_VIDEO = "glyphicon-film";
	const GLYPHICON_MUSIC = "glyphicon-music";
	const GLYPHICON_EXECUTABLE = "glyphicon-cog";
	const GLYPHICON_COMPRESSED = "glyphicon-compressed";
	const GLYPHICON_ARCHIVE = "glyphicon-briefcase";

	protected $path;
	protected $logicalPath;
	protected $name;
	protected $size;
	protected $date;
	protected $extension;
	protected $glyphicon;
	protected $url;

	/**
	 *
	 * @param type $path
	 * @param type $name
	 */
	public function __construct($path, $name) {
		$this->path = realpath($path);
		$this->name = $name;
		$this->extension = !is_dir($this->path) ? strtolower(pathinfo($this->path)['extension']) : null;
		$this->glyphicon = $this->generateGlyphicon();
		$this->logicalPath = $this->generateLogicalPath();
		$this->url = "http://" . $_SERVER['HTTP_HOST'] . FileSystem::getLogicalRoot() . $this->logicalPath;

		if ($this->isValid() && $name !== FileSystem::SELF_DIR && $name !== FileSystem::PARENT_DIR) {
			$this->size = filesize($this->path);
			$this->date = date(Config::DATE_FORMAT, filectime($this->path));
		}
	}

	/**
	 *
	 * @return string
	 */
	private function generateLogicalPath() {
		$rootPathes = array(FileSystem::getRoot() . DIRECTORY_SEPARATOR, FileSystem::getRoot());
		return str_replace($rootPathes, FileSystem::ROOT, $this->path);
	}

	/**
	 *
	 * @return string
	 */
	private function generateGlyphicon() {
		if (is_dir($this->path)) {
			return self::GLYPHICON_FOLDER;
		}

		if (in_array($this->extension, FileSystem::FILE_IMAGE_EXTENSIONS)) {
			return self::GLYPHICON_IMAGE;
		}
		if (in_array($this->extension, FileSystem::FILE_VIDEO_EXTENSIONS)) {
			return self::GLYPHICON_VIDEO;
		}
		if (in_array($this->extension, FileSystem::FILE_MUSIC_EXTENSIONS)) {
			return self::GLYPHICON_MUSIC;
		}
		if (in_array($this->extension, FileSystem::FILE_EXECUTABLE_EXTENSIONS)) {
			return self::GLYPHICON_EXECUTABLE;
		}
		if (in_array($this->extension, FileSystem::FILE_COMPRESSED_EXTENSIONS)) {
			return self::GLYPHICON_COMPRESSED;
		}
		if (in_array($this->extension, FileSystem::FILE_ARCHIVE_EXTENSIONS)) {
			return self::GLYPHICON_ARCHIVE;
		}

		return self::GLYPHICON_DEFAULT_FILE;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isValid() {
		return file_exists($this->path);
	}

	/**
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 *
	 * @return string
	 */
	public function getLogicalPath() {
		return $this->logicalPath;
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @return string
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 *
	 * @return string
	 */
	public function getdate() {
		return $this->date;
	}

	/**
	 *
	 * @return string
	 */
	public function getExtension() {
		return $this->extension;
	}

	/**
	 *
	 * @return string
	 */
	public function getGlyphicon() {
		return $this->glyphicon;
	}

	/**
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->path;
	}

}
