<?php

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

	/**
	 *
	 * @param string $path
	 * @param string $name
	 */
	public function __construct(string $path, string $name) {
		$this->path = realpath($path);
		$this->name = $name;

		if (!is_dir($this->path)) {
			$pathinfo = pathinfo($this->path);
			$this->extension =  isset($pathinfo['extension']) && ".".$pathinfo['extension'] !== $name ? strtolower($pathinfo['extension']) : null;
		} else {
			$this->extension = null;
		}

		$this->logicalPath = $this->generateLogicalPath();
		$this->url = "http://" . $_SERVER['HTTP_HOST'] . FileSystem::getLogicalRoot() . $this->logicalPath;

		if ($this->isValid() && $name !== FileSystem::SELF_FOLDER && $name !== FileSystem::PARENT_FOLDER) {
			$this->size = filesize($this->path);
			$this->date = date(Config::DATE_FORMAT, filectime($this->path));
		} else {
			$this->size = null;
			$this->date = null;
		}

		$this->glyphicon = FileSystem::getGlyphicon($this);
	}

	/**
	 *
	 * @return string
	 */
	private function generateLogicalPath(): string {
		$rootPathes = array(FileSystem::getRoot() . DIRECTORY_SEPARATOR, FileSystem::getRoot());
		return str_replace($rootPathes, FileSystem::ROOT, $this->path);
	}

	/**
	 *
	 * @return boolean
	 */
	public function isValid(): bool {
		return file_exists($this->path);
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
