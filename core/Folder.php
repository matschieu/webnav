<?php

/**
 * @author Matschieu
 */
class Folder extends File {

	const GLYPHICON_FOLDER = "oi-folder";

	const SELF_FOLDER = ".";
	const PARENT_FOLDER = "..";

	private ?array $flatTree = null;

	/**
	 *
	 * @param string $path
	 * @param string $name
	 */
	public function __construct(string $path, string $name) {
		parent::__construct($path, $name);

		if ($name == self::SELF_FOLDER || $name == self::PARENT_FOLDER) {
			$this->size = null;
			$this->date = null;
		}

		$this->glyphicon = self::GLYPHICON_FOLDER;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isValid(): bool {
		return file_exists($this->path) && is_dir($this->path);
	}

	/**
	 *
	 * @return number
	 */
	public function getFolderChildrenSize(): int {
		$files = $this->getFolderChildren();
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
		$files = $this->getFileChildren();
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
		return count($this->getFileChildren());
	}

	/**
	 *
	 * @return number
	 */
	public function getFolderChildrenCount(): int {
		return count($this->getFolderChildren());
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
	 * @return array of Folder
	 */
	public function getFolderChildren(): array {
		if (!$this->isValid()) {
			return array();
		}

		$filesList = array();
		$idx = 0;

		foreach(scandir($this->getPath()) as $file) {
			$filePath = $this->getPath() . DIRECTORY_SEPARATOR . $file;
			$appFolder = FileViewerApplication::build()->getInstallationFolder();

			// If the folder is the one where the application is installed, it's not added to the list
			if ($appFolder === $filePath) {
				continue;
			}

			switch($file) {
				case "":
				case self::SELF_FOLDER:
					break;
				case self::PARENT_FOLDER:
					if ($this->isRoot()) {
						break;
					}
				default:
					if (file_exists($filePath) && is_dir($filePath)) {
						$filesList[$idx++] = new Folder($filePath, $file);
					}
			}
		}

		return $filesList;
	}

	/**
	 *
	 * @return array of File
	 */
	public function getFileChildren(): array {
		if (!$this->isValid()) {
			return array();
		}

		$filesList = array();
		$idx = 0;

		foreach(scandir($this->getPath()) as $file) {
			switch($file) {
				case "":
				case self::SELF_FOLDER:
				case self::PARENT_FOLDER:
				case self::HTACCESS:
				case self::HTPASSWD:
					continue;
				default:
					$filePath = $this->getPath() . DIRECTORY_SEPARATOR . $file;

					if (file_exists($filePath) && !is_dir($filePath)) {
						$filesList[$idx++] = new File($filePath, $file);
					}
			}
		}

		return $filesList;
	}

	/**
	 *
	 * @return array of File
	 */
	public function getChildren(): array {
		return array_merge($this->getFolderChildren(), $this->getFileChildren());
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayName(): string {
		switch($this->name) {
			case self::SELF_FOLDER:
				return $this->name . Translation::get("content.currentFolder");
			case self::PARENT_FOLDER:
				return $this->name . Translation::get("content.parentFolder");
			default:
				return $this->name;
		}
	}

	/**
	 *
	 * @param Folder $folder
	 * @param int $level
	 * @return array
	 */
	public function getFlatTree(int $level = 0): array {
		if (!isset($this->flatTree)) {
			$folderArray = array();

			if ($this->getName() !== self::PARENT_FOLDER) {
				$folderArray = array(new TreeElement($level, $this));
				if ($this->getChildrenCount() > 0) {
					foreach ($this->getFolderChildren() as $child) {
						$folderArray = array_merge($folderArray, $child->getFlatTree($level + 1));
					}
				}
			}

			$this->flatTree = $folderArray;
		}

		return $this->flatTree;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isRoot(): bool {
		return $this == FileSystem::getRoot() || $this->getPath() === FileSystem::getRoot();
	}

	/**
	 *
	 * @return string
	 */
	public function __toString(): string {
		return $this->getDisplayName();
	}

}
