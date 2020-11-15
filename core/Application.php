<?php

require_once './config/Config.php';

$path = "";

/**
 * @author Matschieu
 */
class Application {

	const CORE_DIR = "./core";
	const HTTP_PARAM_PATH = "!";
	const HTTP_PARAM_VIEW = "v";
	const HTTP_PARAM_LANG = "l";

	const VIEW_BLOCK = "bk";
	const VIEW_LIST = "ls";

	private static $application = null;

	private $startExecTime;

	/**
	 *
	 * @return Application
	 */
	public static function build() {
		if (self::$application == null){
			self::$application = new Application();
		}

		return self::$application;
	}

	/**
	 *
	 */
	private function __construct() {
		$this->startExecTime = microtime(true);
	}

	/**
	 * Adds a directory in the include path of PHP to autoload classes
	 * @param String $dirpath
	 */
	final private function addToPath($dirpath) {
		$dir = dir($dirpath);

		//set_include_path(get_include_path() . PATH_SEPARATOR . $dir->path);
		global$path;
		$path = ($path !== "" ? $path . PATH_SEPARATOR : "") . $dir->path;

		while (false !== ($entry = $dir->read())) {
			if ($entry !== "." && $entry !== ".." && is_dir($dir->path . DIRECTORY_SEPARATOR. $entry)) {
				self::addToPath($dir->path . DIRECTORY_SEPARATOR . $entry);
			}
		}
		$dir->close();
	}

	/**
	 * Initializes the application and some options of PHP
	 */
	final public function init() {
		date_default_timezone_set('Europe/Paris');

		self::addToPath(self::CORE_DIR);

		if(Config::DEBUG) {
			ini_set('display_errors', 'On');
			error_reporting(E_ALL | E_WARNING);

			echo "PHP version = " . phpversion() . "<br />";
			echo "SCRIPT_FILENAME = " . $_SERVER['SCRIPT_FILENAME'] . "<br />";
		} else {
			error_reporting(E_ERROR | E_PARSE);
		}

		require_once("./core/autoload.php");
	}

	final public function postLoad() {
		if(Config::DEBUG) {
			echo "Page generation time = " . $this->getExecTime() . " s<br />";
			echo "Memory used = " . memory_get_usage() . " o (max = " . memory_get_peak_usage() . " o)<br />";
		}
	}

	/**
	 *
	 * @return number
	 */
	final public function getExecTime() {
		return (microtime(true) - $this->startExecTime);
	}

	/**
	 *
	 * @return string
	 */
	private function getHttpParam($param) {
		if (isset($_GET[$param]) && !empty($_GET[$param])) {
			return $_GET[$param];
		}
		return null;
	}

	/**
	 *
	 * @return string
	 */
	public function getFolderContext() {
		return $this->getHttpParam(self::HTTP_PARAM_PATH);
	}

	/**
	 *
	 * @return string
	 */
	public function getViewContext() {
		return $this->getHttpParam(self::HTTP_PARAM_VIEW);
	}

	/**
	 *
	 * @return string
	 */
	public function getLanguageContext() {
		return $this->getHttpParam(self::HTTP_PARAM_LANG);
	}

	/**
	 *
	 * @return string
	 */
	public function getInstallationFolder() {
		return dirname($_SERVER['SCRIPT_FILENAME']);
	}

	/**
	 *
	 * @return string
	 */
	public function getUrl() {
		return "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	}

	/**
	 *
	 * @return string
	 */
	public function getRootUrl() {
		$url = $this->getUrl();
		$language = $this->getLanguageContext();

		if ($language != null) {
			$url .= "?". self::HTTP_PARAM_LANG . "=" . $language;
		}

		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getChangeLanguageUrl($language) {
		$url = $this->getUrl() . "?";

		$folder = $this->getFolderContext();
		$view = $this->getViewContext();

		if ($folder != null) {
			$url .= self::HTTP_PARAM_PATH . "=" . $folder . "&";
		}

		if ($view != null) {
			$url .= self::HTTP_PARAM_VIEW . "=" . $view . "&";
		}

		$url .= self::HTTP_PARAM_LANG . "=" . $language;

		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getChangeViewUrl($view) {
		$url = $this->getUrl() . "?";

		$folder = $this->getFolderContext();
		$language = $this->getLanguageContext();

		if ($folder != null) {
			$url .= self::HTTP_PARAM_PATH . "=" . $folder . "&";
		}

		if ($language != null) {
			$url .= self::HTTP_PARAM_LANG . "=" . $language . "&";
		}

		$url .= self::HTTP_PARAM_VIEW . "=" . $view;

		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getChangeFolderUrl(Folder $folder) {
		$url = $this->getUrl() . "?";

		$view = $this->getViewContext();
		$language = $this->getLanguageContext();

		if ($view != null) {
			$url .= self::HTTP_PARAM_VIEW . "=" . $view . "&";
		}

		if ($language != null) {
			$url .= self::HTTP_PARAM_LANG . "=" . $language . "&";
		}

		$url .= self::HTTP_PARAM_PATH . "=" . $folder->getLogicalPath();

		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getName() {
		return Config::APPLICATION_NAME;
	}

	/**
	 *
	 * @return string
	 */
	public function getCustomCss() {
		return Config::APPLICATION_CUSTOM_CSS;
	}

	/**
	 *
	 * @return string
	 */
	public function getFavicon() {
		return Config::APPLICATION_FAVICON;
	}

	/**
	 *
	 * @return string
	 */
	public function getHeader() {
		return Config::APPLICATION_HEADER;
	}

	/**
	 *
	 * @return string
	 */
	public function getFooter() {
		return Config::APPLICATION_FOOTER;
	}

}
