<?php

/**
 * @author Matschieu
 */
class Folder extends File {

	/**
	 *
	 * @param string $path
	 * @param string $name
	 */
	public function __construct(string $path, string $name) {
		parent::__construct($path, $name);
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
			$appFolder = Application::build()->getInstallationFolder();

			// If the folder is the one where the application is installed, it's not added to the list
			if ($appFolder === $filePath) {
				continue;
			}

			switch($file) {
				case "":
				case FileSystem::SELF_FOLDER:
					break;
				case FileSystem::PARENT_FOLDER:
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
	 * @return array of File
	 */
	public function getFileChildren(): array {
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
				case FileSystem::SELF_FOLDER:
				case FileSystem::PARENT_FOLDER:
				case FileSystem::HTACCESS:
				case FileSystem::HTPASSWD:
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
	 * @return string
	 */
	public function __toString(): string {
		return $this->getDisplayName();
	}

}
