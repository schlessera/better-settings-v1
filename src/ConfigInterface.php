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

/**
 * Interface ConfigInterface.
 *
 * Config data abstraction that can be used to inject arbitrary Config values
 * into other classes.
 *
 * @since   0.1.0
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface ConfigInterface {

	/**
	 * Check whether the Config has a specific key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key The key to check the existence for.
	 *
	 * @return bool Whether the specified key exists.
	 */
	public function has_key( $key );

	/**
	 * Get the value of a specific key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key The key to get the value for.
	 *
	 * @return mixed Value of the requested key.
	 */
	public function get_key( $key );

	/**
	 * Get an array with all the keys.
	 *
	 * @since 0.1.0
	 *
	 * @return array Array of config keys.
	 */
	public function get_keys();
}
