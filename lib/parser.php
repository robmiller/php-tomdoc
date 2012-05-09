<?php

class TomDocParser {

	// Set up some reusable parsing tokens.
	private $comment        = '(\/\/|\#)';
	private $argument       = '\s*(\$\w+)\s+(-|–|—)\s+';
	private $code_signature = '^\s*(\w*\s*)?(function|class)';

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
			((^\s*' . $this->comment . ' .*$\n)+
			' . $this->code_signature . '.*?)(\s*{)?$
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
		$output = (object) array(
			'signature' => '',
			'description' => '',
			'arguments' => array(),
			'examples' => '',
			'returns' => ''
		);

		// First, split the block into lines, and strip any leading comments.
		$lines = explode("\n", $block);
		$lines = array_map(array(&$this, 'strip_comments'), $lines);

		$line_number = 0;
		foreach ( (array) $lines as $line ) {
			$line_number++;

			// Empty lines should be ignored, except in examples.
			$is_empty = $this->is_empty($line);
			if ( $is_empty && ( empty($thing) || $thing != 'examples' ) ) {
				continue;
			}

			$is_continuation = $this->is_continuation($line);
			if ( $is_empty || $is_continuation ) {
				if ( empty($thing) ) {
					continue;
				}

				if ( $thing == 'argument' ) {
					$output->arguments[count($output->arguments)] .= ' ' . trim($line);
					continue;
				}

				if ( $thing == 'examples' ) {
					$output->examples .= "\n" . rtrim($line);
					continue;
				}

				$output->$thing .= ' ' . trim($line);
				continue;
			}

			$is_description = ( empty($thing) );
			if ( $is_description ) {
				$thing = 'description';
			}

			$is_return = $this->is_return($line);
			if ( $is_return ) {
				$thing = 'returns';
			}

			$is_argument = $this->is_argument($line);
			if ( $is_argument ) {
				$thing = 'argument';
			}

			$is_example = $this->is_example($line);
			if ( $is_example ) {
				$thing = 'examples';
				$output->examples = ltrim($line);
				continue;
			}

			$is_signature = ( $line_number == count($lines) );
			if ( $is_signature ) {
				$thing = 'signature';
			}

			if ( empty($thing) ) {
				continue;
			}

			if ( $thing == 'argument' ) {
				$output->arguments[] = trim($line);
				continue;
			}

			$output->$thing = trim($line);
		}

		// For arguments, split them into variables and descriptions.
		$arg = 0;
		foreach ( (array) $output->arguments as $argument ) {
			preg_match('/' . $this->argument . '(.*)$/', $argument, $matches);
			list(, $variable, , $description) = $matches;
			$output->arguments[$arg] = (object) compact('variable', 'description');

			$arg++;
		}

		// If this is a class block, then there won't be args/return values.
		if ( preg_match('/class/', $output->signature) ) {
			unset($output->arguments);
			unset($output->returns);
		}

		return $output;
	}

	// Strips the comment tags from a line. Will strip leading whitespace, 
	//     then "//" or "#"
	//
	// $line - The line to strip comments from
	//
	// Returns the line without the comments
	private function strip_comments($line) {
		return preg_replace('/^\s*' . $this->comment . '/', '', $line);
	}

	// Checks whether a line, after having had its comment tag stripped from
	//     it, is empty (since TomDoc usually has blank comment lines between
	//     sections.) Use with array_filter to trim blocks down to size.
	//
	// $line - The line to check
	//
	// Returns true if the line is nonempty.
	private function is_empty($line) {
		return trim($line) == "";
	}

	// Checks whether a line is a continuation of an existing section, rather
	//     than the start of a new one. In TomDoc, continuation lines must 
	//     start with four spaces, so that's our criteria here.
	//
	// $line - The line to check
	//
	// Returns true if the line is a continuation line, or false if it's the
	//     start of a new section.
	private function is_continuation($line) {
		return preg_match('/^\s{2,}/', $line);
	}

	// Checks if a line is a description of a function's return value.
	//
	// $line - The line to check
	//
	// Returns true if the line is a return description, false otherwise.
	private function is_return($line) {
		return preg_match('/^\s*(Returns(:|\s))/i', $line);
	}

	// Checks if a line is a description of a function argument. It checks for
	//     optional leading whitespace, then a dollar sign, then some word
	//     characters, then some whitespace, then a hyphen, en-dash or
	//     em-dash, then more whitespace. Whew!
	//
	// $line - The line to check
	//
	// Returns true if the line is an argument.
	private function is_argument($line) {
		return preg_match('/^' . $this->argument . '/', $line);
	}

	// Checks if a line is the beginning of an examples section.
	//
	// $line - The line to check
	//
	// Returns true if the line starts an examples section.
	private function is_example($line) {
		return preg_match('/^\s*Examples?\s*$/i', $line);
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