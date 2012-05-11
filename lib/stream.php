<?php

abstract class TomDocStream {
	abstract public function write($file, $blocks);
}

// A stream that simply var_dumps the blocks passed to it; probably useful
//     only for debugging.
class VarDumpStream {

	// Writes the stream
	//
	// $blocks - the blocks to output
	//
	// Returns nothing
	public function write($file, $blocks) {
		var_dump($file, $blocks);
	}
}

require_once dirname(__FILE__) . '/streams/console.php';
require_once dirname(__FILE__) . '/streams/html.php';
