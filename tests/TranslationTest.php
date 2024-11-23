<?php

require_once("./core/autoload.php");

use PHPUnit\Framework\TestCase;
use core\FileSystem;
use core\Folder;
use core\Config;
use core\Translation;

class TranslationTest extends TestCase {

	public function testGetInstance() {
		$translation = Translation::getInstance();
		$this->assertNotNull($translation);
	}

	public function testInit() {
		Translation::init();
		$translation = Translation::getInstance();

		$this->assertNotNull($translation);
		$this->assertEquals(Translation::DEFAULT_LANGUAGE, $translation->getLanguage());
		$this->assertGreaterThan(0, count($translation->getAllMessages()));

		Translation::init("fr");
		$translation = Translation::getInstance();

		$this->assertNotNull($translation);
		$this->assertEquals("fr", $translation->getLanguage());
		$this->assertGreaterThan(0, count($translation->getAllMessages()));
	}
	public function testGetLanguage() {
		Translation::init();
		$translation = Translation::getInstance();

		$this->assertEquals(Translation::DEFAULT_LANGUAGE, $translation->getLanguage());

		Translation::init("fr");
		$translation = Translation::getInstance();

		$this->assertEquals("fr", $translation->getLanguage());
	}

	public function testGetAllMessages() {
		$this->assertGreaterThan(0, count(Translation::getInstance()->getAllMessages()));
		$this->assertEquals(43, count(Translation::getInstance()->getAllMessages()));
	}

	public function testGetLabel() {
		Translation::init();
		$translation = Translation::getInstance();

		$this->assertEquals("Root", $translation->getLabel("menu.root"));
		$this->assertEquals("Navigation in", $translation->getLabel("statebar.navigation"));
		$this->assertEquals("file", $translation->getLabel("statebar.file"));
		$this->assertEquals("folder", $translation->getLabel("statebar.folder"));

		Translation::init("fr");
		$translation = Translation::getInstance();

		$this->assertEquals("Racine", $translation->getLabel("menu.root"));
		$this->assertEquals("Navigation dans", $translation->getLabel("statebar.navigation"));
		$this->assertEquals("fichier", $translation->getLabel("statebar.file"));
		$this->assertEquals("dossier", $translation->getLabel("statebar.folder"));
	}

	public function testGet() {
		Translation::init();

		$this->assertEquals("Root", Translation::get("menu.root"));
		$this->assertEquals("Navigation in", Translation::get("statebar.navigation"));
		$this->assertEquals("file", Translation::get("statebar.file"));
		$this->assertEquals("folder", Translation::get("statebar.folder"));

		Translation::init("fr");

		$this->assertEquals("Racine", Translation::get("menu.root"));
		$this->assertEquals("Navigation dans", Translation::get("statebar.navigation"));
		$this->assertEquals("fichier", Translation::get("statebar.file"));
		$this->assertEquals("dossier", Translation::get("statebar.folder"));
	}

}
