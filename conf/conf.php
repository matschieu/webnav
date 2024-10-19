<?php

// To display some debug information on the page
const CONF_DEBUG = false;

// Application information
const CONF_APPLICATION_NAME = "MD-WEBNAV";
// Relative path to the custom CSS file (should be in ./styles)
const CONF_APPLICATION_CUSTOM_CSS = "";
// Relative path to the custom favicon (should be in ./img)
const CONF_APPLICATION_FAVICON = "./img/favicon.png";
// Header of the file explorer, can contains some HTML
const CONF_APPLICATION_HEADER = "<i class=\"fa-solid fa-splotch\" style=\"color: orange\"></i> ".CONF_APPLICATION_NAME;
// Footer of the file explorer, can contains some HTML
const CONF_APPLICATION_FOOTER = "
<div class=\"row\">
	<div class=\"col-md-6 text-start\">
		Powered by
		<a href=\"https://php.net\" target=\"new\"><span class=\"fs-5 fa-brands fa-php\" style=\"color: #7A86B8\"></span></a>
		<a href=\"https://getbootstrap.com\" target=\"new\"><span class=\"fs-5 fa-brands fa-bootstrap\" style=\"color: #712cf9\"></span></a>
		<a href=\"https://fontawesome.com\" target=\"new\"><span class=\"fs-5 fa-brands fa-font-awesome\" style=\"color: rgb(83,141,215)\"></span></a>
	</div>
	<div class=\"col-md-6 text-end\">
		<a href=\"https://github.com/matschieu\" target=\"new\">By Matschieu</a>
	</div>
</div>
";

// The root dir of the file system to explore
// It can be a relative (to the index.php) path or an absolute path
const CONF_FILE_SYSTEM_ROOT = ".";

// Date format used to display file date/time
const CONF_DATE_FORMAT = "Y-m-d G:i";
