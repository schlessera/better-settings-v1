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
 * Interface OptionsStoreInterface.
 *
 * Options data retrieval abstraction.
 *
 * @since   0.1.0
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Dylan Kuhn <dylan@cyberhobo.net>
 */
interface OptionsStoreInterface {

	/**
	 * Get an option value.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $option  Name of option to retrieve. Expected to not be SQL-escaped.
	 * @return mixed
	 */
	public function get( $option );
}
