<?php

/**
 *
 * @author Matschieu
 */
class Translation {

	const DEFAULT_LANGUAGE = "en";

	public static ?string $language = null;
	private static array $messages = array();
	private static bool $init = false;

	/**
	 *
	 */
	private static function init(): void {
		if (!self::$init) {
			// If the language is not provided, set the default language
			if (self::$language == null) {
				self::$language = self::DEFAULT_LANGUAGE;
			}

			$file = './i18n/'.strtolower(self::$language).'.php';

			// If the file doesn't exist, set the default language
			if (is_file($file)) {
				self::$language = self::DEFAULT_LANGUAGE;
			}

			self::$messages = (include $file);

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
		return "";
	}

}
