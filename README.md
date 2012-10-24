CliParamsAbstraction
====================

A PHP Command Line Interface abstraction. (Still a work-in-progress)

How to
------

```php

#!/usr/bin/env php
<?
require 'CliParamsAbstraction.php';

// Declare your own class, that implements the CliProgram interface
class ExampleProgram implements CliProgram {

	// This is the method that must be implemented, I mean, your program.
	public static function main($args) {
		print "Hello, Command Line World!";

		if ( isset( $args['to-go'] ) ) {
			$togo = " to go..";
		}

		$potato = $args['potato'];

		print PHP_EOL . "You asked for {$potato} potatoes{$togo}.";
	}

}

// Must be in your code
try {
	CliParamsAbstraction::init('ExampleProgram', $argv, array( 'p' => 'potato', 'to-go' ) );
} catch (Exception $e) {
	print "Error: " . $e->getMessage();
}
```

So, if you ask in CLI:

```shell

$ example-program -p roasted --to-go
```

You would have:

`You asked for roasted potatoes to go...`