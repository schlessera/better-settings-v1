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
 * Class OptionStore.
 *
 * This is a very basic adapter for the WordPress get_option()
 * function that can be configured to supply consistent default
 * values for particular options.
 *
 * @since   0.1.0
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Dylan Kuhn <dylan@cyberhobo.net>
 */
class OptionStore implements OptionStoreInterface {

	/**
	 * Config instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ConfigInterface;
	 */
	protected $config;

	/**
	 * Instantiate the OptionStore object.
	 *
	 * @since 0.1.0
	 *
	 * @param ConfigInterface $config Config object that contains default option values.
	 */
	public function __construct( ConfigInterface $config ) {
		$this->config = $config;
	}

	/**
	 * Get an option value, falling back to default values if configured.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $option  Name of option to retrieve. Expected to not be SQL-escaped.
	 *
	 * @return bool Whether the specified key exists.
	 */
	public function get( $option ) {
		$default = $this->config->has_key( $option ) ? $this->config->get_key( $option ) : null;
		return get_option( $option, $default );
	}

}
