<?php

class CliParamsAbstraction {
	public static function init($class, $params, $defaultParams = array() ) {
		// Taking the program's name off
		array_shift( $params );

		if ( ! empty( $defaultParams ) ) {

			$tempArray = array();

			for ($i=0; $i<count($params); $i++) {

				$value = self::is( $params[$i] ) != 'value' ? self::getParam( $params[$i], $defaultParams ) : $params[$i];
				$type = self::is( $params[$i] );
				
				$tempArray += array( $type => $value );
			}
		}

		return $class::main( self::postFix( $tempArray ) );
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
	public static function main($args);
}

class UnregisteredParamShorthandException extends Exception {
	protected $message = "Unregistered parameter shorthand.";
}