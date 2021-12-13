<?php

/**
 *
 * @author Matschieu
 */
final class DisplayHelper {

	public static $element = 0;

	/**
	 *
	 * @return string
	 */
	final public static function getRowOpening(): string {
		return self::$element % 6 === 0 ? '<div class="row mt-3 mb-3">'.PHP_EOL : "";
	}

	/**
	 *
	 * @return string
	 */
	final public static function getRowClosing(): string {
		return ++self::$element % 6 === 0 ? '</div>'.PHP_EOL : "";
	}

	/**
	 *
	 * @return string
	 */
	final public static function getLastRowClosing(): string {
		return (self::$element - 1) % 6 !== 0 ? '</div>'.PHP_EOL : "";
	}

}
