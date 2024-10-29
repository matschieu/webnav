<?php
namespace core;

/**
 *
 * @author Matschieu
 */
class Translation {

	const DEFAULT_LANGUAGE = "en";

	private static array $messages = array();

	private static bool $init = false;

	public static ?string $language = null;

	private static function parse_i18n($language): array|bool {
		return parse_ini_file('./i18n/'.strtolower($language).'.ini', false, INI_SCANNER_RAW);
	}

	/**
	 *
	 */
	private static function init(): void {
		if (!self::$init) {
			// If the language is not provided, set the default language
			if (self::$language == null) {
				self::$language = self::DEFAULT_LANGUAGE;
			}

			$ini = self::parse_i18n(self::$language);

			// If the file doesn't exist, set the default language
			if ($ini == false) {
				self::$language = self::DEFAULT_LANGUAGE;
				$ini = self::parse_i18n(self::$language);
			}

			if ($ini) {
				self::$messages = $ini;
			}

			self::$init = true;
		}
	}

	/**
	 *
	 * @return string
	 */
	public static function getLanguage(): string {
		self::init();
		return self::$language;
	}

	/**
	 *
	 * @return array
	 */
	public static function getAllMessages(): array {
		self::init();
		return self::$messages;
	}

	/**
	 *
	 * @return string
	 */
	public static function get($key): string {
		self::init();
		if (isset($key) && isset(self::$messages[$key])) {
			return htmlspecialchars(self::$messages[$key]);
		}
		return $key;
	}

}
