<?php

use core\Config;

require_once("./core/autoload.php");

class TestConfig extends Config {
	protected const CONFIG_FILE = APP_ROOT.'/tests/conf/app.ini';
}
