CLIA: Command Line Interface Abstraction
================================================

CLIA is a PHP Command Line Interface abstraction for short initialization.

How to
------

Your program must have:

* A class with the same name of the file;
* File (program) must be dasherized, that is, `lowercase-dash-separated-words`;
* Class must be `CamelCasedLikeThis`, with the same name as the file;
* Your program class, must be a Subclass of `CliProgram`;


Take a look at the code below for enlightenment:


```php
#!/usr/bin/env php
<?
require 'CliParamsAbstraction.php';

class ExampleProgram implements CliProgram {

	public static function main($args) {
		$togo = '';

		// Use it as key-valued arrays
		if ( isset( $args['to-go'] ) ) {
			$togo = " to go..";
		}

		$potato = $args['potato'];

		print "You asked for {$potato} potatoes{$togo}.";
	}

	// This are your params configurations: keys for shorthands, values for params
	public static function getParams() {
		return array( 'p' => 'potato', 'to-go' );
	}
}

// Black magic
run();
```

So, if you ask in the command-line (don't forget to `chmod +x example-program`):


```shell
$ ./example-program -p roasted --to-go
```

You would have:

```
You asked for roasted potatoes to go...
```

> That's all, folks!