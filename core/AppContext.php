<?php

/**
 *
 * @author Matschieu
 *
 */
class AppContext {

	private ?string $location;
	private ?string $language;
	private bool $displayList;
	private bool $showHidden;

	/**
	 *
	 * @param string $location
	 * @param string $language
	 * @param bool $displayList
	 * @param bool $showHidden
	 */
	public function __construct(?string $location = null, ?string $language = null, bool $displayList = false, bool $showHidden = false) {
		$this->location = $location;
		$this->language = $language;
		$this->displayList = $displayList;
		$this->showHidden = $showHidden;
	}

	/**
	 * @return string
	 */
	public function getLocation(): ?string {
		return $this->location;
	}

	/**
	 * @return string
	 */
	public function getLanguage(): ?string {
		return $this->language;
	}

	/**
	 * @return boolean
	 */
	public function getDisplayList(): bool {
		return $this->displayList;
	}

	/**
	 * @return boolean
	 */
	public function getShowHidden(): bool {
		return $this->showHidden;
	}

}
