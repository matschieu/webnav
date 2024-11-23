<?php
namespace core;

/**
 *
 * @author Matschieu
 */
class Translation {

	public const DEFAULT_LANGUAGE = "en";

	private const FILE_NAME_PATTERN = './i18n/%s.ini';

	private static ?Translation $translation = null;

	private ?string $language = null;

	private array $messages = array();

	/**
	 *
	 * @param string $language
	 * @return array|bool
	 */
	private static function parse_i18n(string $language): array|bool {
		return parse_ini_file(sprintf(self::FILE_NAME_PATTERN, strtolower($language)), false, INI_SCANNER_RAW);
	}

	/**
	 *
	 * @param string $language
	 */
	private function __construct(?string $language) {
		// If the language is not provided, set the default language
		if ($language == null) {
			$this->language = self::DEFAULT_LANGUAGE;
		} else {
			$this->language = $language;
		}

		$ini = self::parse_i18n($this->language);

		// If the file doesn't exist, set the default language
		if ($ini == false) {
			$this->language = self::DEFAULT_LANGUAGE;
			$ini = self::parse_i18n($this->language);
		}

		if ($ini) {
			$this->messages = $ini;
		}
	}

	/**
	 *
	 * @return Translation
	 */
	public static function getInstance(): Translation {
		if (self::$translation == null) {
			self::init(self::DEFAULT_LANGUAGE);
		}
		return self::$translation;
	}

	public static function init(?string $language = null): void {
		self::$translation = new Translation($language);
	}

	/**
	 *
	 * @return string
	 */
	public function getLanguage(): string {
		return $this->language;
	}

	/**
	 *
	 * @return array
	 */
	public function getAllMessages(): array {
		return $this->messages;
	}

	/**
	 *
	 * @return string
	 */
	public function getLabel(string $key): string {
		if (isset($key) && isset($this->messages[$key])) {
			return htmlspecialchars($this->messages[$key]);
		}
		return $key;
	}

	/**
	 *
	 * @param string $key
	 * @return string
	 */
	public static function get(string $key): string {
		return self::getInstance()->getLabel($key);
	}
}
