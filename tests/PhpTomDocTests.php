<?php

require_once dirname(__FILE__) . '/../lib/finder.php';
require_once dirname(__FILE__) . '/../lib/parser.php';

class PhpTomDocTests extends PHPUnit_Framework_TestCase {

	protected function setUp() {

	}

	protected function tearDown() {

	}

	public function testFinder() {
		$finder = new TomDocFinder(dirname(__FILE__) . '/test-code/');

		$this->files = $finder->find('*.php');

		$this->assertCount(1, $finder->files);

		return $this;
	}

	/**
	 * @depends testFinder
	 */
	public function testParse($tester) {
		$tester->parser = new TomDocParser($tester->files[0]);

		$tester->blocks = $tester->parser->find_blocks();

		$this->assertNotEmpty($tester->blocks);

		return $tester;
	}

	/**
	 * @depends testParse
	 */
	public function testClassBlock($tester) {
		$block = array_shift($tester->blocks);

		$elements = $tester->parser->parse_block($block);

		$this->assertNotEmpty($elements->description);
		$this->assertNotEmpty($elements->examples);

		return $tester;
	}

	/**
	 * @depends testClassBlock
	 */
	public function testFullFunctionBlock($tester) {
		$block = array_shift($tester->blocks);

		$elements = $tester->parser->parse_block($block);

		$this->assertNotEmpty($elements->description);
		$this->assertCount(2, $elements->arguments);
		$this->assertCount(2, get_object_vars($elements->arguments[0]));
		$this->assertNotEmpty($elements->examples);
		$this->assertNotEmpty($elements->returns);

		return $tester;
	}

	/**
	 * @depends testFullFunctionBlock
	 */
	public function testLimitedFunctionBlock($tester) {
		$block = array_shift($tester->blocks);

		$elements = $tester->parser->parse_block($block);

		$this->assertNotEmpty($elements->description);

		return $tester;
	}

}