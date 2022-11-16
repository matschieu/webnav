<?php
namespace core;

require_once './conf/conf.php';

/**
 * @author Matschieu
 */
final class Config {

	private function __construct() { }

	const DEBUG = CONF_DEBUG;

	const APPLICATION_NAME = CONF_APPLICATION_NAME;

	const APPLICATION_CUSTOM_CSS = CONF_APPLICATION_CUSTOM_CSS;

	const APPLICATION_FAVICON = CONF_APPLICATION_FAVICON;

	const APPLICATION_HEADER = CONF_APPLICATION_HEADER;

	const APPLICATION_FOOTER = CONF_APPLICATION_FOOTER;

	const FILE_SYSTEM_ROOT = CONF_FILE_SYSTEM_ROOT;

	const DATE_FORMAT = CONF_DATE_FORMAT;

}
