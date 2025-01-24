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

	public function testDefaultLanguage() {
		$this->assertNotNull(Config::get()->defaultLanguage());
		$this->assertEquals("fr", Config::get()->defaultLanguage());
	}

	public function testDefaultShowHidden() {
		$this->assertTrue(Config::get()->defaultShowHidden());
	}

	public function testDefaultListView() {
		$this->assertTrue(Config::get()->defaultListView());
	}

	public function testApplicationName() {
		$this->assertNotNull(Config::get()->applicationName());
		$this->assertEquals("M-WEBNAV TEST", Config::get()->applicationName());
	}

	public function testApplicationStyleGradient() {
		$this->assertNotNull(Config::get()->applicationCustomCss());
		$this->assertTrue(Config::get()->applicationStyleGradient());
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

	public function testEnableMenu(): void {
		$this->assertTrue(Config::get()->enableMenu());
		$this->assertTrue(Config::get()->enableMenu(""));
		$this->assertTrue(Config::get()->enableMenu("foldertree"));
		$this->assertTrue(Config::get()->enableMenu("refresh"));
		$this->assertTrue(Config::get()->enableMenu("back"));
		$this->assertTrue(Config::get()->enableMenu("next"));
		$this->assertTrue(Config::get()->enableMenu("showhidden"));
		$this->assertTrue(Config::get()->enableMenu("changeview"));
		$this->assertTrue(Config::get()->enableMenu("sort"));
		$this->assertTrue(Config::get()->enableMenu("changelanguage"));
		$this->assertTrue(Config::get()->enableMenu("close"));
		$this->assertTrue(Config::get()->enableMenu("filter"));
		$this->assertFalse(Config::get()->enableMenu("foo"));
	}

}

