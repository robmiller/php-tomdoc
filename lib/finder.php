<?php

// Provides some functions for (recursively) finding files within a directory.
class TomDocFinder {

	public $files = array();

	private $dir;

	// Public: sets up a Finder instance for the specified directory.
	public function __construct($dir) {
		$this->dir = $dir;
		if ( !preg_match('/\/$/', $this->dir) ) {
			$this->dir .= '/';
		}
	}

	// Recursively fetches all files under the given directory.
	//
	// $dir - The directory in which to look.
	// $glob - A pattern to match files against (default: '*')
	//
	// Returns an array of filenames.
	private function get_files($dir, $glob = '*') {
		$files = glob("$dir/$glob");

		$dirs  = glob("$dir/*", GLOB_ONLYDIR);
		foreach ( (array) $dirs as $subdir ) {
			$files = array_merge($files, $this->get_files($subdir, $glob));
		}

		return $files;
	}

	// Public: searches for files that match the given pattern in the current
	//     directory.
	//
	// $glob - A glob pattern that matches the files that you want to match.
	//
	// Examples:
	//
	// $finder->find('**/*.php');
	//
	// Returns an array of the files matched.
	public function find($glob) {
		$this->files = $this->get_files($this->dir, $glob);

		$this->files = array_map('realpath', $this->files);

		return $this->files;
	}

}