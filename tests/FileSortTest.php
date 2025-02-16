<?php

require_once("./core/autoload.php");

use PHPUnit\Framework\TestCase;
use core\FileSort;

class FileSortTest extends TestCase {

	public function testFrom() {
		$this->assertEquals(FileSort::NameAscending, FileSort::from(0));
		$this->assertEquals(FileSort::NameDescending, FileSort::from(1));

		$this->expectException(ValueError::class);
		$this->assertNull(FileSort::from("3"));
	}

	public function testTryFrom() {
		$this->assertEquals(FileSort::NameAscending, FileSort::tryFrom(0));
		$this->assertEquals(FileSort::NameDescending, FileSort::tryFrom(1));
		$this->assertNull(FileSort::tryFrom(3));
		$this->assertEquals(FileSort::NameAscending, FileSort::tryFrom(3) ?? FileSort::NameAscending);
	}

}
