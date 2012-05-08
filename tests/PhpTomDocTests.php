<?php

require_once dirname(__FILE__) . '/../lib/finder.php';
require_once dirname(__FILE__) . '/../lib/parser.php';

class PhpTomDocTests extends PHPUnit_Framework_TestCase {

	protected function setUp() {

	}

	protected function tearDown() {

	}

	public function testFinder() {
		$finder = new TomDocFinder(dirname(__FILE__) . '/../lib/');

		$files = $finder->find('*.php');

		$this->assertCount(2, $finder->files);

		return $files;
	}

	/**
	 * @depends testFinder
	 */
	public function testParse(array $files) {
		$parser = new TomDocParser($files[0]);

		$blocks = $parser->find_blocks();

		$this->assertNotEmpty($blocks);

		foreach ( (array) $blocks as $block ) {
			$elements = $parser->parse_block($block);

			$this->assertNotEmpty($elements->description);

			$this->assertNotEmpty($elements->return);
		}
	}

}