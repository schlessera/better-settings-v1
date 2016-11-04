<?php
/**
 * Example Code: Settings Page - Better Implementation v1.
 *
 * This code is part of the article "Using A Config To Write Reusable Code"
 * as published on https://www.alainschlesser.com/.
 *
 * @see       https://www.alainschlesser.com/config-files-for-reusable-code/
 *
 * @package   AlainSchlesser\BetterSettings1
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   GPL-2.0+
 * @link      https://www.alainschlesser.com/
 * @copyright 2016 Alain Schlesser
 */

namespace AlainSchlesser\BetterSettings1;

use Exception;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionParameter;
use RuntimeException;

/**
 * Trait FunctionInvokerTrait.
 *
 * Reusable trait that you can pull into any class to have a way of pass an
 * associative array as sorted arguments.
 *
 * @since   0.1.0
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
trait FunctionInvokerTrait {

	/**
	 * Check the accepted arguments for a given function and pass associative
	 * array values in the right order.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $function Name of the function to invoke.
	 * @param  array  $args     Associative array that contains the arguments.
	 * @return mixed            Return value of the invoked function.
	 * @throws InvalidArgumentException If a valid function is missing.
	 */
	public function invoke_function( $function, array $args = array() ) {
		if ( ! $function || ! is_string( $function ) || '' === $function ) {
			throw new InvalidArgumentException( 'Missing valid function to invoke.' );
		}
		try {
			$reflection        = new ReflectionFunction( $function );
			$ordered_arguments = $this->parse_parameters(
				$reflection->getParameters(),
				$args
			);
			return $reflection->invokeArgs( $ordered_arguments );
		} catch ( Exception $exception ) {
			throw new InvalidArgumentException(
				sprintf(
					'Failed to invoke function "%1$s". Reason: "%2$s"',
					$function,
					$exception->getMessage()
				)
			);
		}
	}

	/**
	 * Parse the parameters of a function to get the needed order.
	 *
	 * @since 0.1.0
	 *
	 * @param ReflectionParameter[] $params The reflection parameters to parse.
	 * @param array                 $args   The arguments to check against.
	 * @return array The correctly ordered arguments to pass to the reflected
	 *                                      callable.
	 * @throws RuntimeException If a $param does not have a name() method.
	 */
	public function parse_parameters( array $params, $args ) {
		$ordered_args = array();
		foreach ( $params as $param ) {
			if ( ! $param instanceof ReflectionParameter ) {
				throw new RuntimeException(
					sprintf(
						'Parameter "%1$s" is not an instance of ReflectionParameter.',
						$param
					)
				);
			}
			$ordered_args[] = array_key_exists( $param->name, $args )
				? $args[ $param->name ]
				: $param->getDefaultValue();
		}
		return $ordered_args;
	}
}
