<?php

require_once dirname(__FILE__) . '/../lib/finder.php';
require_once dirname(__FILE__) . '/../lib/parser.php';
require_once dirname(__FILE__) . '/../lib/stream.php';

class PhpTomDocTests extends PHPUnit_Framework_TestCase {

	protected function setUp() {

	}

	protected function tearDown() {

	}

	public function testFinder() {
		$finder = new TomDocFinder(dirname(__FILE__) . '/test-find/');

		$this->files = $finder->find('*.php');

		$this->assertCount(3, $finder->files);

		return $this;
	}

	public function testParse() {
		$sample = dirname(__FILE__) . '/test-code/sample.php';

		$this->parser = new TomDocParser($sample);

		$this->blocks = $this->parser->find_blocks();

		$this->assertNotEmpty($this->blocks);

		return $this;
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
		$this->assertCount(3, get_object_vars($elements->arguments[0]));
		$this->assertNotEmpty($elements->arguments[1]->default);
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

	/**
	 * @depends testLimitedFunctionBlock
	 */
	public function testStream($tester) {
		$stream = new VarDumpStream();

		$tester->parser->parse();

		ob_start();
		$tester->parser->output(&$stream);
		$output = ob_get_clean();

		$stream = new ConsoleStream();
		$tester->parser->output(&$stream);

		$this->assertNotEmpty($output);

		return $tester;
	}

	/**
	 * @depends testStream
	 */
	public function testHTMLStream($tester) {
		$html_file = 'test.html';

		$stream = new HTMLStream($html_file);

		$finder = new TomDocFinder('.');
		$files = $finder->find('*.php');

		foreach ( (array) $files as $file ) {
			$parser = new TomDocParser($file);

			$parser->parse();

			$parser->output(&$stream);
		}

		$html = file_get_contents($html_file);
		$this->assertFileExists($html_file);
		$this->assertNotEmpty($html_file);
	}

}