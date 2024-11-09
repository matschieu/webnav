<?php
namespace core;

/**
 * @author Matschieu
 */
class Folder extends File {

	const GLYPHICON_FOLDER = "fa-solid fa-folder";

	private $rootFolder;

	private $folderChildren;

	private $fileChildren;

	/**
	 *
	 * @param string $path
	 * @param string $name
	 * @param bool $rootFolder
	 * @param array $folderChildren
	 * @param array $fileChildren
	 */
	public function __construct(string $path, string $name, bool $rootFolder = false, array $folderChildren = array(), array $fileChildren = array()) {
		parent::__construct($path, $name);

		if (FileSystem::isValidFolder($this->path)) {
			if ($name == FileSystem::SELF_FOLDER || $name == FileSystem::PARENT_FOLDER) {
				$this->size = null;
				$this->date = null;
			} else {
				$this->size = filesize($this->path);
				$this->date = date(Config::dateFormat(), filectime($this->path));
			}
		}

		$this->glyphicon = self::GLYPHICON_FOLDER;
		$this->rootFolder = $rootFolder;
		$this->folderChildren = $folderChildren;
		$this->fileChildren = $fileChildren;
	}

	/**
	 *
	 * @return number
	 */
	public function getFolderChildrenSize(): int {
		$files = $this->folderChildren;
		$totalSize = 0;

		for($i = 0; $i < count($files); $i++) {
			$totalSize += $files[$i]->getSize();
		}

		return $totalSize;
	}

	/**
	 *
	 * @return number
	 */
	public function getFileChildrenSize(): int {
		$files = $this->fileChildren;
		$totalSize = 0;

		for($i = 0; $i < count($files); $i++) {
			$totalSize += $files[$i]->getSize();
		}

		return $totalSize;
	}

	/**
	 *
	 * @return number
	 */
	public function getChildrenSize(): int {
		return $this->getFileChildrenSize() + $this->getFolderChildrenSize();
	}

	/**
	 *
	 * @return number
	 */
	public function getFileChildrenCount(): int {
		return count($this->fileChildren);
	}

	/**
	 *
	 * @return number
	 */
	public function getFolderChildrenCount(): int {
		foreach($this->folderChildren as $folder) {
			// Don't count the parent folder in the content of the current folder
			if ($folder->getName() === FileSystem::PARENT_FOLDER) {
				return count($this->folderChildren) - 1;
			}
		}
		return count($this->folderChildren);
	}

	/**
	 *
	 * @return number
	 */
	public function getChildrenCount(): int {
		return $this->getFolderChildrenCount() + $this->getFileChildrenCount();
	}

	/**
	 *
	 * @return array of File
	 */
	public function getChildren(): array {
		return array_merge($this->folderChildren, $this->fileChildren);
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayName(): string {
		switch($this->name) {
			case FileSystem::SELF_FOLDER:
				return $this->name . Translation::get("content.currentFolder");
			case FileSystem::PARENT_FOLDER:
				return $this->name . Translation::get("content.parentFolder");
			default:
				return $this->name;
		}
	}

	/**
	 *
	 * @return bool
	 */
	public function isRoot(): bool {
		return $this->rootFolder;
	}

	/**
	 *
	 * @return array
	 */
	public function getFolderChildren(): array {
		return $this->folderChildren;
	}

	/**
	 *
	 * @return array
	 */
	public function getFileChildren(): array {
		return $this->fileChildren;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->getDisplayName();
	}

}
