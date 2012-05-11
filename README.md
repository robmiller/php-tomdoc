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

To generate documentation for a specific file and write it to docs/file.html,
call:

	$ php-tomdoc /path/to/file.php docs/file.html

To generate documentation for all the PHP files within a directory and write
it to docs/index.html, call:

	$ php-tomdoc /path/to/directory docs/index.html

For now, only HTML output can be generated through the command line tool, but
there's a coloured console mode that will be enabled soon!

## Example output

![Example of php-tomdoc output](http://i48.tinypic.com/1jpaif.png)

## Todos

* Write output stream for plain-text.
* Improve HTML rendering.
* Allow choice of output in command line mode
* When outputting in HTML, allow choice of single file (default) or multiple
  files (one per source file).
* Table of contents for single-file HTML output.
* Make recursive file searching optional.
* Explore proper nesting of functions within classes -- reflection?
* Test with regular global functions (not just class methods).
