<?php
namespace core;

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
	 * @see JsonSerializable::jsonSerialize()
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
	public static function decode(?string $token): AppContext {
		if ($token == null) {
			return new AppContext();
		}
		$vars = json_decode(base64_decode($token), true);
		$appContext = new AppContext();
		if ($vars != null) {
			foreach(array_keys($vars) as $key) {
				$appContext->$key = $vars[$key];
			}
		}
		return $appContext;
	}

}
