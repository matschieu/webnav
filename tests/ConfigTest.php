<?php

require_once("./core/autoload.php");

use PHPUnit\Framework\TestCase;
use core\Config;

class ConfigTest extends TestCase {

	public static function setUpBeforeClass(): void {
		TestConfig::get();
	}

	public function testGetValue() {
		$this->assertNull(Config::get()->getValue("test.key"));
		$this->assertNotNull(Config::get()->getValue("application.name"));
		$this->assertEquals("M-WEBNAV TEST", Config::get()->getValue("application.name"));
	}

	public function testGet() {
		$config = Config::get();
		$this->assertNotNull($config);
		$this->assertTrue($config instanceof Config);
	}

	public function testDebug() {
		$this->assertNotNull(Config::get()->debug());
		$this->assertFalse(Config::get()->debug());
	}

	public function testApplicationName() {
		$this->assertNotNull(Config::get()->applicationName());
		$this->assertEquals("M-WEBNAV TEST", Config::get()->applicationName());
	}

	public function testApplicationCustomCss() {
		$this->assertNotNull(Config::get()->applicationCustomCss());
		$this->assertEquals("./styles/custom.css", Config::get()->applicationCustomCss());
	}

	public function testApplicationFavicon() {
		$this->assertNotNull(Config::get()->applicationFavicon());
		$this->assertEquals("./img/favicon.png", Config::get()->applicationFavicon());
	}

	public function testApplicationHeader() {
		$this->assertNotNull(Config::get()->applicationHeader());
		$this->assertEquals("./header.php", Config::get()->applicationHeader());
	}

	public function testApplicationFooter() {
		$this->assertNotNull(Config::get()->applicationFooter());
		$this->assertEquals("./footer.php", Config::get()->applicationFooter());
	}

	public function testFileSystemRoot() {
		$this->assertNotNull(Config::get()->fileSystemRoot());
		$this->assertEquals("./example", Config::get()->fileSystemRoot());
	}

	public function testDateFormat() {
		$this->assertNotNull(Config::get()->dateFormat());
		$this->assertEquals("Y-m-d G:i", Config::get()->dateFormat());
	}

}

