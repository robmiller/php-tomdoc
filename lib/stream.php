<?php

// Provides a template of functions that all output streams are expected to
//     implement.
abstract class TomDocStream {
	// Is expected to write the stream (or some equivalent functionality). Is
	//     called once the parsing is complete, and the structure of the
	//     file's documentation is known.
	//
	// $file - The absolute filename of the source file being parsed.
	// $blocks - The structure of the documentation of that file; an array of
	//     objects, each representing a documentation block.
	//
	// Returns nothing.
	abstract public function write($file, $blocks);
}

// A stream that simply var_dumps the blocks passed to it; probably useful
//     only for debugging.
class VarDumpStream {

	// Writes the stream
	//
	// $file - The source file.
	// $blocks - The blocks to output.
	//
	// Returns nothing.
	public function write($file, $blocks) {
		var_dump($file, $blocks);
	}
}

require_once dirname(__FILE__) . '/streams/console.php';
require_once dirname(__FILE__) . '/streams/html.php';
