<?php

require_once './config/Config.php';

$path = "";

/**
 * @author Matschieu
 */
class FileViewerApplication {

	const CORE_DIR = "./core";
	const HTTP_PARAM_PATH = "dir";
	const HTTP_PARAM_LIST = "list";
	const HTTP_PARAM_LANG = "lang";
	const HTTP_PARAM_HIDDEN = "hid";
	const HTTP_PARAM_REFRESH = "rfr";

	private static ?FileViewerApplication $application = null;

	private $startExecTime;

	private AppContext $appContext;

	/**
	 *
	 * @return FileViewerApplication
	 */
	public static function build(): FileViewerApplication {
		if (self::$application == null){
			self::$application = new FileViewerApplication();
		}

		return self::$application;
	}

	/**
	 *
	 * @return string
	 */
	private function getHttpParam($param): ?string {
		if (isset($_GET[$param]) && !empty($_GET[$param])) {
			return $_GET[$param];
		}
		return null;
	}

	/**
	 *
	 */
	private function __construct() {
		$this->init();
		$this->startExecTime = microtime(true);
		$this->appContext = new AppContext(
			$this->getHttpParam(self::HTTP_PARAM_PATH),
			$this->getHttpParam(self::HTTP_PARAM_LANG),
			$this->getHttpParam(self::HTTP_PARAM_LIST) != null,
			$this->getHttpParam(self::HTTP_PARAM_HIDDEN) != null
		);
		Translation::$language = $this->appContext->getLanguage();
	}

	/**
	 *
	 * Adds a directory in the include path of PHP to autoload classes
	 * @param String $dirpath
	 */
	final private function addToPath($dirpath): void {
		$dir = dir($dirpath);

		//set_include_path(get_include_path() . PATH_SEPARATOR . $dir->path);
		global $path;
		$path = ($path !== "" ? $path . PATH_SEPARATOR : "") . $dir->path;

		while (false !== ($entry = $dir->read())) {
			if ($entry !== "." && $entry !== ".." && is_dir($dir->path . DIRECTORY_SEPARATOR. $entry)) {
				self::addToPath($dir->path . DIRECTORY_SEPARATOR . $entry);
			}
		}
		$dir->close();
	}

	/**
	 *
	 * Initializes the application and some options of PHP
	 */
	final private function init(): void {
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

	/**
	 *
	 * @return string
	 */
	private function getUrl(): string {
		return "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	}

	/**
	 *
	 * @param array $params
	 * @return string
	 */
	private function formatUrlWithParams(array $params): string {
		$url = $this->getUrl();

		if (count($params) > 0) {
			$url .= "?";
			$i = 0;

			foreach($params as $key => $value) {
				if ($value == null) {
					continue;
				}
				if ($i++ > 0) {
					$url .= "&";
				}
				$url .= $key."=".$value;
			}
		}

		return $url;
	}

	/**
	 *
	 */
	final public function postLoad(): void {
		if(Config::DEBUG) {
			echo "Page generation time = " . $this->getExecTime() . " s<br />";
			echo "Memory used = " . memory_get_usage() . " o (max = " . memory_get_peak_usage() . " o)<br />";
		}
	}

	/**
	 *
	 * @return number
	 */
	final public function getExecTime(): int {
		return (microtime(true) - $this->startExecTime);
	}

	/**
	 *
	 * @return AppContext
	 */
	public function getAppContext(): AppContext {
		return $this->appContext;
	}

	/**
	 *
	 * @return string
	 */
	public function getRootUrl(): string {
		return $this->formatUrlWithParams(array(
				self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
				self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
				self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
	}

	/**
	 *
	 * @return string
	 */
	public function getRefreshUrl(): string {
		return $this->formatUrlWithParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
			self::HTTP_PARAM_REFRESH => true,
		));
	}

	/**
	 *
	 * @param string $language
	 * @return string
	 */
	public function getChangeLanguageUrl(string $language): string {
		return $this->formatUrlWithParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $language,
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayBlockUrl(): string {
		return $this->formatUrlWithParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => false,
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayListUrl(): string {
		return $this->formatUrlWithParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => true,
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
	}

	/**
	 *
	 * @param Folder $folder
	 * @return string
	 */
	public function getChangeFolderUrl(Folder $folder): string {
		return $this->formatUrlWithParams(array(
			self::HTTP_PARAM_PATH => $folder->getLogicalPath(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
	}

	/**
	 *
	 * @param boolean $showHidden
	 * @return string
	 */
	public function getShowHiddenUrl(bool $showHidden): string {
		return $this->formatUrlWithParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $showHidden,
		));
	}

	/**
	 *
	 * @return string
	 */
	public function getName(): string {
		return Config::APPLICATION_NAME;
	}

	/**
	 *
	 * @return string
	 */
	public function getCustomCss(): string {
		return Config::APPLICATION_CUSTOM_CSS;
	}

	/**
	 *
	 * @return string
	 */
	public function getFavicon(): string {
		return Config::APPLICATION_FAVICON;
	}

	/**
	 *
	 * @return string
	 */
	public function getHeader(): string {
		return Config::APPLICATION_HEADER;
	}

	/**
	 *
	 * @return string
	 */
	public function getFooter(): string {
		return Config::APPLICATION_FOOTER;
	}

	/**
	 *
	 * @return Folder
	 */
	public function getRootFolder(): Folder {
		return FileSystem::getRootFolder($this->appContext->getShowHidden());
	}

	/**
	 *
	 * @return Folder
	 */
	public function getCurrentFolder(): Folder {
		return FileSystem::getFolderFromLogicalPath($this->appContext->getLocation(), $this->appContext->getShowHidden());
	}

	/**
	 *
	 * @param string $language
	 * @return boolean
	 */
	public function isSelectedLanguage(string $language): bool {
		return $this->appContext->getLanguage() === $language || $this->appContext->getLanguage() == null && Translation::DEFAULT_LANGUAGE === $language;
	}

}
