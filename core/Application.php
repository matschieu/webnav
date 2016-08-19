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

	const VIEW_BLOCK = "bk";
	const VIEW_LIST = "ls";

	private static $application = null;

	/**
	 *
	 * @return Application
	 */
	public static function getInstance() {
		if (self::$application == null){
			self::$application = new Application();
		}

		return self::$application;
	}

	/**
	 *
	 */
	private function __construct() {
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

			echo "PHP version = " . phpversion() . "<br/>";
			echo "SCRIPT_FILENAME = " . $_SERVER['SCRIPT_FILENAME'] . "<br/>";
		} else {
			error_reporting(E_ERROR | E_PARSE);
		}

		require_once("./core/autoload.php");
	}

	/**
	 *
	 * @return string
	 */
	public function getFolderContext() {
		if (isset($_GET[self::HTTP_PARAM_PATH]) && !empty($_GET[self::HTTP_PARAM_PATH])) {
			return $_GET[self::HTTP_PARAM_PATH];
		}
		return null;
	}

	/**
	 *
	 * @return string
	 */
	public function getViewContext() {
		if (isset($_GET[self::HTTP_PARAM_VIEW]) && !empty($_GET[self::HTTP_PARAM_VIEW])) {
			return $_GET[self::HTTP_PARAM_VIEW];
		}
		return null;
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
	public function getParameterizedUrl(Folder $folder = null, $view = null) {
		$url = $this->getUrl();

		if (isset($folder) || isset($view)) {
			$url .= "?";
		}

		if (isset($folder)) {
			$url .= self::HTTP_PARAM_PATH . "=" . $folder->getLogicalPath();
		}

		if (isset($view)) {
			$url .= "&" . self::HTTP_PARAM_VIEW . "=" . $view;
		} else {
			$url .= "&" . self::HTTP_PARAM_VIEW . "=" . $this->getViewContext();
		}

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
