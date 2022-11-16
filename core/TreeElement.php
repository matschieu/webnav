<?php
namespace core;

/**
 * @author Matschieu
 *
 */
class TreeElement {

	private int $level;
	private Folder $folder;

	/**
	 *
	 * @param int $level
	 * @param Folder $folder
	 */
	public function __construct(int $level, Folder $folder) {
		$this->level = $level;
		$this->folder = $folder;
	}

	/**
	 * @return number
	 */
	public function getLevel(): int {
		return $this->level;
	}

	/**
	 * @return Folder
	 */
	public function getFolder(): Folder {
		return $this->folder;
	}

	/**
	 * @param number $level
	 */
	public function setLevel(int $level) {
		$this->level = $level;
	}

	/**
	 * @param Folder $folder
	 */
	public function setFolder(Folder $folder) {
		$this->folder = $folder;
	}



}

