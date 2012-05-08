<?php

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
		$this->files = glob($this->dir . $glob);

		$this->files = array_map('realpath', $this->files);

		return $this->files;
	}

}