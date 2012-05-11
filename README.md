# php-tomdoc

php-tomdoc is a [TomDoc][] parser for PHP files.

TomDoc is an alternative to the kludgey horridness you see in things like
phpDoc or JavaDoc; rather than writing inline documentation that's designed
for computers to read, you write lovely, human-readable documentation that's
as readable inline as it is when it's generated.

Here's an example:

	// Public: connects to the database server with the given credentials. If
	//     they're ommitted, default values will be used.
	//
	// $hostname - The hostname of the database server (default: 'localhost')
	// $username - The username to authenticate with (default: 'root')
	// $password - The password to use when authenticating (default: '')
	//
	// Returns a new database resource.
	public function connect($hostname = '', $username = '', $password = '') {

It looks much like the sort of comment you'd write even if you weren't using
a particular documentation system, and reads perfectly; it just has the added
bonus of being readable by a machine, too.

[TomDoc]: http://tomdoc.org/

## How to use

To view documentation for a specific file, call:

	$ php-tomdoc /path/to/file.php

To view documentation for all the PHP files within a directory, call:

	$ php-tomdoc /path/to/directory

For now, you can only view output in your terminal; coming soon will be the
ability to write documentation to HTML and plain-text files.

## Example output

![Example of php-tomdoc output](http://i48.tinypic.com/1jpaif.png)

## Todos

* Write output stream for plain-text
* Improve HTML rendering
* Allow choice of output in command line mode
* When outputting in HTML, allow choice of single file (default) or multiple
  files (one per source file).
* Explore proper nesting of functions within classes -- reflection?
* Test with regular global functions (not just class methods)
