<?php

class TomDocParser {

	// Public: Instantiates the parser for the given file.
	//
	// $file - The fully-qualified path to the file you wish to parse.
	public function __construct($file) {
		$this->file = $file;
	}

	// Public: parses the current file.
	//
	// Returns an array of objects, each representing a doc block. Use
	//     output() to do something useful with the parsed data, e.g. output
	//     it to a terminal or write it to a file.
	public function parse() {
		$blocks = $this->find_blocks();
	}

	// Public: given PHP code, will extract doc blocks from it.
	//
	// Returns an array of strings, each containing the raw source of a block.
	public function find_blocks() {
		$code = file_get_contents($this->file);

		preg_match_all(
			'/
			((^\s*\/\/ .*$\n)+)
			^\s*(\w*\s*)?function
			/xm',
			$code,
			$matches
		);

		$blocks = $matches[1];

		return $blocks;
	}

	// Public: actually does the nitty-gritty of the parsing, taking a doc
	//     block and returning the various components.
	//
	// Returns an object containing all of the possible parsed values, whether
	//     they exist in the block or not.
	public function parse_block($block) {

	}

	// Public: outputs the parsed documentation to the given stream.
	//
	// $stream - an output stream, usually an object that inherits from
	//     TomDocStream.
	//
	// Returns nothing; relies on the stream to output/return as it deems fit.
	public function output($stream) {

	}

}