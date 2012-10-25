<?php
/**
  * The PHP CLI Absctraction Layer.
  * @author Vinicius da Costa Pires (https://github.com/viniciuspires)
  */
class CLIAbstraction {
	/**
	  * Makes the given class initialization
	  */
	public static function init( $program, $params ) {
		$tempArray = array();

		$defaultParams = $program::getParams();

		if ( ! empty( $defaultParams ) ) {

			for ($i=0; $i<count($params); $i++) {

				$value = self::is( $params[$i] ) != 'value' ? self::getParam( $params[$i], $defaultParams ) : $params[$i];
				$type = self::is( $params[$i] );
				
				$tempArray += array( $type => $value );
			}
		}

		return $program::main( self::postFix( $tempArray ) );
	}

	private static function postFix($tempArray) {
		$postFix = array();

		$parameters = array();
		$values = array();

		foreach ($tempArray as $type => $value) {
			if ( $type == 'value' ) {
				array_push($values, $value);
			} else {
				array_push($parameters, $value);
			}
		}

		foreach ($parameters as $i => $param) {
			$postFix[$param] = isset( $values[$i] ) ? $values[$i] : '' ;
		}

		return $postFix;
	}

	private static function is( $arg ) {
		if (strpos( $arg, "--") !== false) {
			return 'param';
		} else if ( strpos( $arg, "-") !== false ) {
			return 'shorthand';
		} else {
			return 'value';
		}
	}

	private static function getParam($needle, array $haystack) {
		$needle = str_replace('-', '', $needle);

		foreach ($haystack as $shorthand => $param) {
			if ( $needle == $shorthand ) {
				return $param;
			}
		}
		return false;
	}

}

interface CliProgram {
	/**
	  * The main program call
	  */
	public static function main($args);
	/**
	  * Returns an array of known program parameters
	  * e.g.: 
	  * public function getParams() {
	  * 	return array( 'shorthand' => 'parameter', 'another-parameter' );
	  * }
	  * @return array $parameters The parameters the program accepts
	  */
	public static function getParams();
}


class UnrecognizedCliProgram extends Exception {
	protected $message = "Given class isn't a CliProgram.";
}
class UnregisteredParameterException extends Exception {
	protected $message = 'Unregistered parameter.';
}
class UnregisteredParameterShorthandException extends Exception {
	protected $message = 'Unregistered parameter shorthand.';
}

function run() {
	// CAUTION: Ugly code ahead!
	global $argv;
	/* This is where Walt Disney cries:
	  ./my-random-name-cli-program turns into fine
	  MyRandomNameCliProgram (that is your subclassed CliProgram ;D) */
	$program =  str_replace( ' ', '', ucwords( str_replace( '-', ' ', basename( array_shift( $argv ) ) ) ) ) ;

	try {
		CLIAbstraction::init( $program, $argv );
	} catch (Exception $e) {
		print "Error: " . $e->getMessage();
	}
	print PHP_EOL;
}