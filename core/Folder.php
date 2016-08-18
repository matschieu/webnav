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
	public function getFolderSize() { 
		$files = $this->getFolderList(false);
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
	public function getFileSize() {
		$files = $this->getFileList();
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
	public function getTotalSize() {
		return $this->getFileSize() + $this->getFolderSize();
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getFileCount() { 
		return count($this->getFileList()); 
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getFolderCount() { 
		return count($this->getFolderList(false));
	}
	
	/**
	 * 
	 * @return number
	 */
	public function getTotalCount() { 
		return $this->getFolderCount() + $this->getFileCount();
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getDisplayFilesInfo() { 
		$nbFiles = $this->getFileCount();
		return $nbFiles . " file" . ($nbFiles > 1 ? "s" : "");
	}

	/**
	 * 
	 * @return string
	 */
	public function getDisplayFoldersInfo() { 
		$nbDirs  = $this->getFolderCount();
		return $nbDirs . " folder" . ($nbDirs > 1 ? "s" : "");
	}

	/**
	 * 
	 * @param boolean $addParent
	 * @return array of Folder
	 */
	public function getFolderList($addParent) {
		if (!$this->isValid()) {
			return array();
		}
		
		$content = opendir($this->path);
		$filesList = array();
		$idx = 0;
		$tmp = explode(DIRECTORY_SEPARATOR, $_SERVER['PHP_SELF']);
		
		while ($files[] = readdir($content));
		sort($files, SORT_STRING);
		
		foreach($files as $file) {
			switch($file) {
				// This application folder (security)
				case $tmp[count($tmp) - 2] :
				case "": 
				case FileSystem::SELF_DIR:
					break;
				case FileSystem::PARENT_DIR:
					if (!$addParent) {
						break;
					}
				default: 
					$filePath = $this->path . DIRECTORY_SEPARATOR . $file;
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
	public function getFileList() { 
		if (!$this->isValid()) {
			return array();
		}
		
		$content = opendir($this->path);
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
					$filePath = $this->path . DIRECTORY_SEPARATOR . $file;
					if (file_exists($filePath) && !is_dir($filePath)) {
						$filesList[$idx++] = new File($filePath, $file);
					}
			}
		}
		
		return $filesList;
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
