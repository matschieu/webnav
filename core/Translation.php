<?php

/**
 *
 * @author Matschieu
 */
class Translation {

	const DEFAULT_LANGUAGE = "en";

	private static $language;
	private static $messages;
	private static $init = false;

	/**
	 *
	 */
	private static function init() {
		if (!self::$init) {
			self::$language = strtolower(Application::build()->getLanguageContext());

			// If the language is not provided, set the default language
			if (self::$language == null) {
				self::$language = self::DEFAULT_LANGUAGE;
			}

			$file = './languages/'.self::$language.'.php';

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
	public static function getLanguage() {
		self::init();
		return self::$language;
	}

	/**
	 *
	 * @return array
	 */
	public static function getAllMessages() {
		self::init();
		return self::$messages;
	}

	/**
	 *
	 * @return string
	 */
	public static function get($key) {
		self::init();
		if (isset($key) && isset(self::$messages[$key])) {
			return htmlspecialchars(self::$messages[$key]);
		}
		return null;
	}

}
