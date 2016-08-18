<?php

/**
 * @author Matschieu
 */
abstract class Config {

	// To display some debug information on the page
	const DEBUG = false;

	// Application information
	const APPLICATION_NAME = "MNAV";
	// Relative path to the custom CSS file (should be in ./styles)
	const APPLICATION_CUSTOM_CSS = "";
	// Relative path to the custom favicon (should be in ./img)
	const APPLICATION_FAVICON = "./img/favicon.png";
	// Header of the file explorer, can contains some HTML
	const APPLICATION_HEADER = "";
	// Footer of the file explorer, can contains some HTML
	const APPLICATION_FOOTER = "";

	// The root dir of the file system to explore
	// It can be a relative (to the index.php) path or an absolute path
	const FILE_SYSTEM_ROOT = "/home/mathieu/www";

	// Date format used to display file date/time
	const DATE_FORMAT = "Y-m-d G:i";

}
