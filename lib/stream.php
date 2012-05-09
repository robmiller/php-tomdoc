<?php

abstract class TomDocStream {
	abstract public function write($blocks);
}

// A stream that simply var_dumps the blocks passed to it; probably useful
//     only for debugging.
class VarDumpStream {

	// Writes the stream
	//
	// $blocks - the blocks to output
	//
	// Returns nothing
	public function write($blocks) {
		var_dump($blocks);
	}
}