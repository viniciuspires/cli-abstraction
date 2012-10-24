<?php

class CliParamsAbstraction {
	public static function init($class, $params, $defaultParams = array() ) {
		// Retirando o nome do programa, caso seja necessário utilizar futuramente, só armazenar o retorno numa variável
		array_shift( $params );

		if ( empty( $defaultParams ) ) {
			$class::main( array() );
		} else {
			$paramsArray = array();

			for ($i=0; $i<count($params); $i++) {
				if ( strpos( $params[$i], "--") !== false ) {
					print "param: ";
				} else if ( strpos( $params[$i], "-") !== false ) {
					$param = self::getParam( $params[$i], $defaultParams );
					if ( $param !== false ) {
						print "shorthand (to {$param}): ";
					} else {
						throw new UnregisteredParamShorthandException();
					}
				} else {
					print "value: ";
				}
				print $params[$i].PHP_EOL;
			}
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