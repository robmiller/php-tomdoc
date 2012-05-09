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

Currently, php-tomdoc is just a library, but soon it will have a command-line
tool for viewing docs on the command line and for generating an HTML version
of your documentation.

## Todos

* Parse "example" sections of doc blocks
* Write command line tool
* Write output streams: console, HTML, plain-text
* Parse out default values for optional arguments
* Parse out the function signature to go with the doc block
* Test with regular global functions (not just class methods)
* Support class-level docs, not just method-level
