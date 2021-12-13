<?php

/**
 *
 * @author Matschieu
 *
 */
class AppContext {

	private ?string $location;
	private ?string $language;
	private ?string $viewType;
	private bool $showHidden;

	/**
	 *
	 * @param string $location
	 * @param string $language
	 * @param string $viewType
	 * @param bool $showHidden
	 */
	public function __construct(?string $location = null, ?string $language = null, ?string $viewType = null, ?bool $showHidden = false) {
		$this->location = $location;
		$this->language = $language;
		$this->viewType = $viewType;
		$this->showHidden = $showHidden == null ? false : $showHidden;
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
	 * @return string
	 */
	public function getViewType(): ?string {
		return $this->viewType;
	}

	/**
	 * @return boolean
	 */
	public function getShowHidden(): bool {
		return $this->showHidden;
	}

}
