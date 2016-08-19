<?php

/**
 * @author Matschieu
 */
class Folder extends File {

	/**
	 *
	 * @param type $path
	 * @param type $name
	 */
	public function __construct($path, $name) {
		parent::__construct($path, $name);
	}

	/**
	 *
	 * @return number
	 */
	public function getFolderChildrenSize() {
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
	public function getFileChildrenSize() {
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
	public function getChildrenSize() {
		return $this->getFileChildrenSize() + $this->getFolderChildrenSize();
	}

	/**
	 *
	 * @return number
	 */
	public function getFileChildrenCount() {
		return count($this->getFileChildren());
	}

	/**
	 *
	 * @return number
	 */
	public function getFolderChildrenCount() {
		return count($this->getFolderChildren());
	}

	/**
	 *
	 * @return number
	 */
	public function getChildrenCount() {
		return $this->getFolderChildrenCount() + $this->getFileChildrenCount();
	}

	/**
	 *
	 * @param boolean $addParent
	 * @return Folder[]
	 */
	public function getFolderChildren() {
		if (!$this->isValid()) {
			return array();
		}

		$content = opendir($this->getPath());
		$filesList = array();
		$idx = 0;

		while ($files[] = readdir($content));
		sort($files, SORT_STRING);

		foreach($files as $file) {
			$filePath = $this->getPath() . DIRECTORY_SEPARATOR . $file;
			$appFolder = Application::getInstance()->getApplicationFolder();

			// If the folder is the one where the application is installed, it's not added to the list
			if ($appFolder === $filePath) {
				continue;
			}

			switch($file) {
				case "":
				case FileSystem::SELF_DIR:
					break;
				case FileSystem::PARENT_DIR:
					if (FileSystem::isRoot($this)) {
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
	 * @return File[]
	 */
	public function getFileChildren() {
		if (!$this->isValid()) {
			return array();
		}

		$content = opendir($this->getPath());
		$filesList = array();
		$idx = 0;

		while ($files[] = readdir($content));
		sort($files, SORT_STRING);

		foreach($files as $file) {
			switch($file) {
				case "":
				case FileSystem::SELF_DIR:
				case FileSystem::PARENT_DIR:
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
	 * @return File[]
	 */
	public function getChildren() {
		return array_merge($this->getFolderChildren(), $this->getFileChildren());
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayFilesInfo() {
		$nbFiles = $this->getFileChildrenCount();
		return $nbFiles . " file" . ($nbFiles > 1 ? "s" : "");
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayFoldersInfo() {
		$nbDirs  = $this->getFolderChildrenCount();
		return $nbDirs . " folder" . ($nbDirs > 1 ? "s" : "");
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayName() {
		switch($this->name) {
			case FileSystem::SELF_DIR:
				return $this->name . " (current folder)";
			case FileSystem::PARENT_DIR:
				return $this->name . " (parent folder)";
			default:
				return $this->name;
		}
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getDisplayName();
	}

}
