<?php
namespace core;

/**
 * @author Matschieu
 */
class Config implements \JsonSerializable {

	protected const CONFIG_FILE = APP_ROOT.'/conf/app.ini';

	private static ?Config $config = null;

	private array $values = array();

	private function __construct(string $filepath) {
		$conf = parse_ini_file($filepath, false, INI_SCANNER_TYPED);
		if ($conf) {
			$this->values = $conf;
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getValue($key): string|bool|null {
		if (isset($key) && isset($this->values[$key])) {
			return $this->values[$key];
		}
		return null;
	}

	public static function get(): Config {
		if (self::$config == null) {
			self::$config = new Config(static::CONFIG_FILE);
		}
		return self::$config;
	}

	/**
	 *
	 * @return bool
	 */
	public static function debug() : bool {
		return filter_var(self::get()->getValue("debug"), FILTER_VALIDATE_BOOLEAN);
	}

	/**
	 *
	 * @return string
	 */
	public static function defaultLanguage() : string {
		return self::get()->getValue("application.default.language");
	}

	/**
	 *
	 * @return bool
	 */
	public static function defaultShowHidden() : bool {
		return filter_var(self::get()->getValue("application.default.showhidden"), FILTER_VALIDATE_BOOLEAN);
	}

	/**
	 *
	 * @return bool
	 */
	public static function defaultListView() : bool {
		return filter_var(self::get()->getValue("application.default.listview"), FILTER_VALIDATE_BOOLEAN);
	}

	/**
	 *
	 * @return string
	 */
	public static function applicationName() : string {
		return self::get()->getValue("application.name");
	}

	/**
	 *
	 * @return bool
	 */
	public static function applicationStyleGradient() : bool {
		return filter_var(self::get()->getValue("application.style.gradient"), FILTER_VALIDATE_BOOLEAN);
	}

	/**
	 *
	 * @return string
	 */
	public static function applicationCustomCss() : ?string {
		return self::get()->getValue("application.style.customcss");
	}

	/**
	 *
	 * @return string
	 */
	public static function applicationFavicon() : string {
		return self::get()->getValue("application.favicon");
	}

	/**
	 *
	 * @return string
	 */
	public static function applicationHeader() : string {
		return self::get()->getValue("application.header");
	}

	/**
	 *
	 * @return string
	 */
	public static function applicationFooter() : string {
		return self::get()->getValue("application.footer");
	}

	/**
	 *
	 * @return string
	 */
	public static function fileSystemRoot() : string {
		return self::get()->getValue("filesystem.root");
	}

	/**
	 *
	 * @return string
	 */
	public static function dateFormat() : string {
		return self::get()->getValue("application.date.format");
	}

	/**
	 *
	 * @return bool
	 */
	public static function enableMenu(string $key = "") : bool {
		$realKey = "application.enable.menu";

		if (!empty($key)) {
			$realKey .= ".".$key;
		}

		if (self::get()->getValue($realKey) == null) {
			return false;
		}

		return filter_var(self::get()->getValue($realKey), FILTER_VALIDATE_BOOLEAN);
	}

	/**
	 *
	 * {@inheritDoc}
	 * @see \JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize() {
		return array_filter($this->values, function ($key) { return str_starts_with($key, "application."); }, ARRAY_FILTER_USE_KEY);
	}

}
