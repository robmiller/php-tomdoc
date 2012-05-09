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

// A stream that writes to the console using a pager.
class ConsoleStream {

	public function __construct() {
		$this->use_colours = (function_exists('posix_isatty') && posix_isatty(STDOUT));

		$this->colours = array(
			'default' => "\x1b[0m",
			'green'   => "\x1b[32m",
			'yellow'  => "\x1b[33m"
		);
	}

	private function colour_echo($text, $colour) {
		if ( $this->use_colours ) {
			echo $this->colours[$colour];
		}

		echo $text;

		if ( $this->use_colours ) {
			echo $this->colours['default'];
		}
	}

	// Writes the stream
	//
	// $blocks - the blocks to output
	//
	// Returns nothing
	public function write($file, $blocks) {
		echo "$file\n\n";

		foreach ( (array) $blocks as $block ) {
			if ( $block->type == 'class' ) {
				echo "##\n\n";

				echo "Class: ";
				$this->colour_echo($block->signature, 'yellow');
				echo "\n\n";
			}

			if ( $block->type == 'function' ) {
				echo "\tFunction: ";
				$this->colour_echo("$block->signature\n\n", 'yellow');

				if ( !empty($block->description) ) {
					$block->description = wordwrap($block->description, 50);
					$block->description = str_replace("\n", "\n\t\t", $block->description);

					echo "\t\t$block->description\n\n";
				}

				if ( count($block->arguments) ) {
					echo "\t\tAccepts " . count($block->arguments) . " arguments:\n\n";

					foreach ( (array) $block->arguments as $argument ) {
						$this->colour_echo("\t\t$argument->variable\n", 'yellow');

						echo "\t\t\t" . ucfirst($argument->description) . "\n";
					}

					echo "\n";
				} else {
					echo "\t\tAccepts no arguments.\n\n";
				}

				if ( !empty($block->returns) ) {
					$block->returns = wordwrap($block->returns, 50);
					$block->returns = str_replace("\n", "\n\t\t", $block->returns);

					echo "\t\t$block->returns\n\n";
				}

				echo "\t\t--\n\n";
			}
		}
	}

}