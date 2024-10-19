<?php
namespace core;

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
	 * @param string $param
	 * @return string|NULL
	 */
	private function getHttpParam(string $param): ?string {
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
	 * Initializes the application and some options of PHP
	 */
	private function init(): void {
		date_default_timezone_set('Europe/Paris');

		if(Config::DEBUG) {
			ini_set('display_errors', 'On');
			error_reporting(E_ALL | E_WARNING);

			echo "PHP version = " . phpversion() . "<br />";
			echo "SCRIPT_FILENAME = " . $_SERVER['SCRIPT_FILENAME'] . "<br />";
		} else {
			error_reporting(E_ERROR | E_PARSE);
		}
	}

	/**
	 *
	 * @param string $page
	 * @return string
	 */
	private function getUrl($page = "index.php"): string {
		return (!empty($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/" . $page;
	}

	private function formatHttpParams(array $params) {
		$param = "";
		if (count($params) > 0) {
			$param .= "?";
			$i = 0;

			foreach($params as $key => $value) {
				if ($value == null) {
					continue;
				}
				if ($i++ > 0) {
					$param .= "&";
				}
				$param .= $key."=".$value;
			}
		}
		return $param;
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
		$url = $this->getUrl();
		$url .= $this->formatHttpParams(array(
				self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
				self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
				self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getTreeUrl(): string {
		$url = $this->getUrl("tree.php");
		$url .= $this->formatHttpParams(array(
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getRefreshUrl(): string {
		$url = $this->getUrl();
		$url .= $this->formatHttpParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
			self::HTTP_PARAM_REFRESH => true,
		));
		return $url;
	}

	/**
	 *
	 * @param string $language
	 * @return string
	 */
	public function getChangeLanguageUrl(string $language): string {
		$url = $this->getUrl();
		$url .= $this->formatHttpParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $language,
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayBlockUrl(): string {
		$url = $this->getUrl();
		$url .= $this->formatHttpParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => false,
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getDisplayListUrl(): string {
		$url = $this->getUrl();
		$url .= $this->formatHttpParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => true,
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
		return $url;
	}

	/**
	 *
	 * @param Folder $folder
	 * @return string
	 */
	public function getChangeFolderUrl(Folder $folder): string {
		$url = $this->getUrl();
		$url .= $this->formatHttpParams(array(
			self::HTTP_PARAM_PATH => $folder->getLogicalPath(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $this->appContext->getShowHidden(),
		));
		return $url;
	}

	/**
	 *
	 * @param boolean $showHidden
	 * @return string
	 */
	public function getShowHiddenUrl(bool $showHidden): string {
		$url = $this->getUrl();
		$url .= $this->formatHttpParams(array(
			self::HTTP_PARAM_PATH => $this->appContext->getLocation(),
			self::HTTP_PARAM_LANG => $this->appContext->getLanguage(),
			self::HTTP_PARAM_LIST => $this->appContext->getDisplayList(),
			self::HTTP_PARAM_HIDDEN => $showHidden,
		));
		return $url;
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
