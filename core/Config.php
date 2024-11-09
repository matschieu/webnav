<?php
namespace core;

/**
 * @author Matschieu
 */
final class Config {

	const CONFIG_FILE = './conf/app.ini';

	private static ?Config $config = null;

	private array $values = array();

	private function __construct(string $filepath) {
		$conf = parse_ini_file($filepath, false, INI_SCANNER_RAW);
		if ($conf) {
			$this->values = $conf;
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getValue($key): ?string {
		if (isset($key) && isset($this->values[$key])) {
			return $this->values[$key];
		}
		return null;
	}

	public static function get(): Config {
		if (self::$config == null){
			self::$config = new Config(self::CONFIG_FILE);
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
	public static function applicationName() : string {
		return self::get()->getValue("application.name");
	}

	/**
	 *
	 * @return string
	 */
	public static function applicationCustomCss() : ?string {
		return self::get()->getValue("application.custom.css");
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
		return self::get()->getValue("date.format");
	}

}
