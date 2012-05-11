<?php

// A stream that writes documentation to an HTML file.
class HTMLStream {

	public function __construct($file) {
		$this->file = $file;
		file_put_contents($this->file, $this->get_header());
	}

	private function get_header() {
		return "
			<!DOCTYPE html>
			<html>
				<head>
					<title>php-tomdoc</title>
					<meta charset='utf-8'>

					<style type='text/css'>
						body {
							font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;
							font-size:14px;
						}

						#search {
							font-size:18px;
						}

						article {
							margin:2em 0 0 2em;
							padding:1em 0 0 0;
							border-top:1px solid #ccc;
						}

						section {
							margin:2em 0 0 2em;
							padding-left:2em;
							border-top:1px solid #ccc;
						}

						h1 + section {
							margin-top:0;
							border:none;
						}

						article h1 {
							font-weight:100;
							font-size:20px;
							margin:0 0 0.25em 0;
							padding:0;
						}

						article h2 {
							font-weight:normal;
							font-size:16px;
						}

						article h3 {
							font-weight:normal;
							font-size:16px;
							margin-left:-2em;
						}

						code {
							font-family:Monaco, monospace;
							font-size:0.9em;
						}
					</style>
				</head>

				<body>

					<input type='text' placeholder='search&hellip;' id='search'>

					<script type='text/javascript'>
						document.getElementById('search').onkeyup = function() {
							term = this.value;

							functions = document.getElementsByTagName('section');
							for ( var i = 0; i < functions.length; i++ ) {
								if ( term.length == 0 ) {
									functions[i].style.display = 'block';
								} else {
									console.log(functions[i].innerHTML);
									if ( functions[i].innerHTML.match(term) ) {
										functions[i].style.display = 'block';
									} else {
										functions[i].style.display = 'none';
									}
								}
							}
						}
					</script>
		";
	}

	// Writes a stream for a particular source file. By default, will append
	//     the documentation for the file to a single HTML document.
	//
	// $blocks - the blocks to output
	//
	// Returns nothing
	public function write($file, $blocks) {
		if ( empty($blocks) ) {
			return;
		}

		ob_start();

		echo "<article class='file'>";

		echo "<h1>$file</h1>\n\n";

		foreach ( (array) $blocks as $block ) {
			$hash = substr(md5($block->signature), 0, 5);

			if ( $block->type == 'class' ) {
				echo "<h2 id='class-$hash'><a href='#class-$hash'>#</a> Class: <code>$block->signature</code></h2>\n\n";

				echo "<p>$block->description</p>";
			}

			if ( $block->type == 'function' ) {
				echo "<section class='function' id='function-$hash'>";

				echo "<h3><a href='#function-$hash'>#</a> Function: <code>$block->signature</code></h3>\n\n";

				if ( !empty($block->description) ) {
					echo "<p>$block->description</p>\n\n";
				}

				if ( count($block->arguments) ) {
					echo "<p class='args'>Accepts " . count($block->arguments) . " argument" . ( count($block->arguments) != 1 ? "s" : "" ) . ":</p>\n\n";

					foreach ( (array) $block->arguments as $argument ) {
						if ( empty($argument->variable) ) {
							continue;
						}

						echo "<dt><code>$argument->variable</code>";

						if ( $argument->optional ) {
							echo "<span class='optional'>";
							if ( !empty($argument->default) ) {
								echo " (Optional, default $argument->default)";
							} else {
								echo " (Optional)";
							}
							echo "</span>";
						}

						echo "</dt>\n";

						echo "<dd>" . ucfirst($argument->description) . "</dd>\n\n";
					}

					echo "\n";
				} else {
					echo "<p class='args noargs'>Accepts no arguments.</p>\n\n";
				}

				if ( !empty($block->returns) ) {

					echo "<p class='returns'>$block->returns</p>\n\n";
				}

				echo '</section>';
			}
		}

		echo "</article>";

		$html = ob_get_clean();
		file_put_contents($this->file, $html, FILE_APPEND);
	}
}
