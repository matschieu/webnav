<?php

require_once("./core/autoload.php");

use PHPUnit\Framework\TestCase;
use core\FileSystem;
use core\Folder;
use core\Config;

class FileSystemTest extends TestCase {

	private static $phpUnitPath;

	public static function setUpBeforeClass(): void {
		TestConfig::get();

		self::$phpUnitPath = dirname(shell_exec('which phpunit'));

		$_SERVER['SCRIPT_FILENAME'] = realpath(__FILE__);
		$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_FILENAME'];
		$_SERVER['HTTP_HOST'] = "test";
	}

	public function testGetLogicalPath() {
		$this->assertSame("/", FileSystem::getLogicalPath(FileSystem::getRoot()));
		$this->assertSame("/tmp", FileSystem::getLogicalPath(FileSystem::getRoot()."/tmp"));
	}

	public function testGetLogicalRoot() {
		$this->assertSame(getcwd().DIRECTORY_SEPARATOR."tests".DIRECTORY_SEPARATOR.Config::fileSystemRoot(), FileSystem::getLogicalRoot());
	}

	public function testGetRoot() {
		$this->assertSame(realpath(getcwd().DIRECTORY_SEPARATOR."tests".DIRECTORY_SEPARATOR.Config::fileSystemRoot()), FileSystem::getRoot());
	}

	public function testGetRootFolder() {
		$folder = FileSystem::getRootFolder();
		$this->assertSame("/", $folder->getName());
		$this->assertSame("/", $folder->getLogicalPath());
		$this->assertEquals(16, $folder->getChildrenCount());
		$this->assertEquals(15, $folder->getFileChildrenCount());
		$this->assertEquals(1, $folder->getFolderChildrenCount());
		$this->assertEquals($folder->getChildrenCount(), count($folder->getChildren()));
		$this->assertEquals($folder->getFileChildrenCount(), count($folder->getFileChildren()));
		$this->assertEquals($folder->getFolderChildrenCount(), count($folder->getFolderChildren()));
		$this->assertEquals(4096, $folder->getSize());
		$this->assertNotNull($folder->getPath());
		$this->assertNotNull($folder->getUrl());
		$this->assertNull($folder->getExtension());
	}

	public function testGetFolderFromLogicalPath() {
		$folder = FileSystem::getFolderFromLogicalPath(FileSystem::getLogicalPath(FileSystem::getRoot()));

		$this->assertNotNull($folder);
		$this->assertTrue($folder->isRoot());
	}

	public function testConvertSize() {
		$this->assertEquals("1 byte", FileSystem::convertSize(1));
		$this->assertEquals("1 Kb", FileSystem::convertSize(1024));
		$this->assertEquals("1 Mb", FileSystem::convertSize(1024 * 1024));
		$this->assertEquals("1 Gb", FileSystem::convertSize(1024 * 1024 * 1024));

		$this->assertEquals("2 bytes", FileSystem::convertSize(2));
		$this->assertEquals("2 Kb", FileSystem::convertSize(1024 * 2));
		$this->assertEquals("2 Mb", FileSystem::convertSize(1024 * 1024 * 2));
		$this->assertEquals("2.01 Gb", FileSystem::convertSize(1024 * 1024 * 1024 * 2));
	}

	public function testGetFlatTree() {
		$this->assertEmpty(FileSystem::getFlatTree());
		$this->assertNotEmpty(FileSystem::getFlatTree(FileSystem::getRootFolder()));
	}

	public function testIsValidFolder() {
		$folders = FileSystem::getFolderChildren(FileSystem::getRoot(), false);

		$this->assertNotNull($folders);
		$this->assertGreaterThan(0, count($folders));
		$this->assertNotNull($folders[0]);
		$this->assertTrue(FileSystem::isValidFolder($folders[0]->getPath()));
	}

	public function testIsValidFile() {
		$files = FileSystem::getFileChildren(FileSystem::getRoot(), false);

		$this->assertNotNull($files);
		$this->assertGreaterThan(0, count($files));
		$this->assertNotNull($files[0]);
		$this->assertTrue(FileSystem::isValidFile($files[0]->getPath()));
	}

	public function testIsRoot() {
		$this->assertTrue(FileSystem::isRoot(FileSystem::getRoot()));
	}

	public function testGetFolderChildren() {
		$folders = FileSystem::getFolderChildren(FileSystem::getRoot(), false);
		$folderSize = count($folders);

		$this->assertNotNull($folders);
		$this->assertEquals(1, $folderSize);
		$this->assertGreaterThan(0, $folderSize);

		$allFolders = FileSystem::getFolderChildren(FileSystem::getRoot(), true);
		$allFoldersSize = count($allFolders);

		$this->assertNotNull($allFolders);
		$this->assertEquals(2, $allFoldersSize);
		$this->assertGreaterThan($folderSize, $allFoldersSize);
	}

	public function testGetFileChildren() {
		$files = FileSystem::getFileChildren(FileSystem::getRoot(), false);
		$fileSize = count($files);

		$this->assertNotNull($files);
		$this->assertEquals(15, $fileSize);
		$this->assertGreaterThan(0, $fileSize);

		$allFiles = FileSystem::getFileChildren(FileSystem::getRoot(), true);
		$allFilesSize = count($allFiles);

		$this->assertNotNull($allFiles);
		$this->assertEquals(16, $allFilesSize);
		$this->assertGreaterThan($fileSize, $allFilesSize);
	}
}

