<?php
namespace core;

/**
 * @author Matschieu
 */
class FileViewerApplication {

	const HTTP_PARAM_CONTEXT = "ctx";

	private static ?FileViewerApplication $application = null;

	private float $startExecTime;

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
		$this->initDebug();
		$this->startExecTime = microtime(true);
		$this->appContext = AppContext::decode($this->getHttpParam(self::HTTP_PARAM_CONTEXT));
		Translation::$language = $this->appContext->getLanguage();
	}

	/**
	 *
	 * Initializes the application and some options of PHP
	 */
	private function initDebug(): void {
		date_default_timezone_set('Europe/Paris');

		if(Config::debug()) {
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
	private function buildUrl($page = "index.php"): string {
		return (!empty($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/" . $page;
	}

	/**
	 *
	 * @param array $params
	 * @return string
	 */
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
		if(Config::debug()) {
			echo "Page generation time = " . $this->getExecTime() . " s<br />";
			echo "Memory used = " . memory_get_usage() . " o (max = " . memory_get_peak_usage() . " o)<br />";
		}
	}

	/**
	 *
	 * @return float
	 */
	final public function getExecTime(): float {
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
		$context = $this->appContext->copy();
		$context->setLocation(null);
		$url = $this->buildUrl().$this->formatHttpParams(array(self::HTTP_PARAM_CONTEXT => $context->encode()));
		return $url;
	}

	/**
	 *
	 * @param string $page
	 * @return string
	 */
	public function getUrl(string $page = "index.php"): string {
		$url = $this->buildUrl($page).$this->formatHttpParams(array(self::HTTP_PARAM_CONTEXT => $this->appContext->encode()));
		return $url;
	}

	/**
	 *
	 * @param string $language
	 * @return string
	 */
	public function getUrlWithLanguage(string $language): string {
		$context = $this->appContext->copy();
		$context->setLanguage($language);
		$url = $this->buildUrl().$this->formatHttpParams(array(self::HTTP_PARAM_CONTEXT => $context->encode()));
		return $url;
	}

	/**
	 *
	 * @param bool $value
	 * @return string
	 */
	public function getUrlWithDisplayList(bool $value): string {
		$context = $this->appContext->copy();
		$context->setDisplayList($value);
		$url = $this->buildUrl().$this->formatHttpParams(array(self::HTTP_PARAM_CONTEXT => $context->encode()));
		return $url;
	}

	/**
	 *
	 * @param Folder $folder
	 * @return string
	 */
	public function getUrlWithFolder(Folder $folder): string {
		$context = $this->appContext->copy();
		$context->setLocation($folder->getLogicalPath());
		$url = $this->buildUrl().$this->formatHttpParams(array(self::HTTP_PARAM_CONTEXT => $context->encode()));
		return $url;
	}

	/**
	 *
	 * @param bool $showHidden
	 * @return string
	 */
	public function getUrlWithShowHidden(bool $showHidden): string {
		$context = $this->appContext->copy();
		$context->setShowHidden($showHidden);
		$url = $this->buildUrl().$this->formatHttpParams(array(self::HTTP_PARAM_CONTEXT => $context->encode()));
		return $url;
	}

	/**
	 *
	 * @return string
	 */
	public function getName(): string {
		return Config::applicationName();
	}

	/**
	 *
	 * @return string
	 */
	public function getCustomCss(): string {
		return Config::applicationCustomCss();
	}

	/**
	 *
	 * @return string
	 */
	public function getFavicon(): string {
		return Config::applicationFavicon();
	}

	/**
	 *
	 * @return string
	 */
	public function getHeader(): string {
		return Config::applicationHeader();
	}

	/**
	 *
	 * @return string
	 */
	public function getFooter(): string {
		return Config::applicationFooter();
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
	 * @return bool
	 */
	public function isSelectedLanguage(string $language): bool {
		return $this->appContext->getLanguage() === $language || $this->appContext->getLanguage() == null && Translation::DEFAULT_LANGUAGE === $language;
	}

}
