<?php
namespace core\gui;

use core\FileSort;
use core\Config;

/**
 *
 * @author Matschieu
 *
 */
class AppContext implements \JsonSerializable {

	private ?string $location;
	private ?string $language;
	private bool $displayList;
	private bool $showHidden;
	private int $fileSort;

	/**
	 *
	 * @param string $location
	 * @param string $language
	 * @param bool $displayList
	 * @param bool $showHidden
	 * @param FileSort $fileSort
	 */
	public function __construct(?string $location = null, ?string $language = null, bool $displayList = false, bool $showHidden = false, FileSort $fileSort = FileSort::NameAscending) {
		$this->location = $location;
		$this->language = $language;
		$this->displayList = $displayList;
		$this->showHidden = $showHidden;
		$this->fileSort = $fileSort->value;
	}

	/**
	 * @param string $location
	 */
	public function setLocation(?string $location) {
		$this->location = $location;
	}

	/**
	 * @return string
	 */
	public function getLocation(): ?string {
		return $this->location;
	}

	/**
	 * @param string $language
	 */
	public function setLanguage(string $language) {
		$this->language = $language;
	}

	/**
	 * @return string
	 */
	public function getLanguage(): ?string {
		return $this->language;
	}

	/**
	 * @param bool $displayList
	 */
	public function setDisplayList(bool $displayList) {
		$this->displayList = $displayList;
	}

	/**
	 * @return bool
	 */
	public function getDisplayList(): bool {
		return $this->displayList;
	}

	/**
	 * @param bool $showHidden
	 */
	public function setShowHidden(bool $showHidden) {
		$this->showHidden = $showHidden;
	}

	/**
	 * @return bool
	 */
	public function getShowHidden(): bool {
		return $this->showHidden;
	}

	/**
	 * @return \core\FileSort
	 */
	public function getFileSort(): FileSort {
		return FileSort::from($this->fileSort);
	}

	/**
	 * @param \core\FileSort $fileSort
	 */
	public function setFileSort($fileSort) {
		$this->fileSort = $fileSort->value;
	}

	/**
	 *
	 * @return AppContext
	 */
	public function copy(): AppContext {
		$appContext = new AppContext();
		$attributes = get_object_vars($this);
		foreach(array_keys($attributes) as $key) {
			$appContext->$key = $attributes[$key];
		}
		return $appContext;
	}

	/**
	 *
	 * {@inheritDoc}
	 * @see \JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize(): mixed {
		return get_object_vars($this);
	}

	/**
	 *
	 * @return string
	 */
	public function encode(): string {
		return base64_encode(json_encode($this));
	}

	/**
	 *
	 * @param string $token
	 * @return AppContext
	 */
	public static function decode(?string $token, Config $config): AppContext {
		$appContext = new AppContext();

		if ($token != null) {
			$vars = json_decode(base64_decode($token), true);

			if ($vars != null) {
				foreach(array_keys($vars) as $key) {
					$appContext->$key = $vars[$key];
				}
			}
		} else {
			$appContext->setLanguage($config->defaultLanguage());
			$appContext->setShowHidden($config->defaultShowHidden());
			$appContext->setDisplayList($config->defaultListView());
		}

		return $appContext;
	}

}
