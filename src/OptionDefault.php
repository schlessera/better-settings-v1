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
 * Class OptionDefault.
 *
 * This is a very basic way to filter for the WordPress get_option()
 * function that can be configured to supply consistent default
 * values for particular options.
 *
 * @since   0.1.0
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Dylan Kuhn <dylan@cyberhobo.net>
 */
class OptionDefault {

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
	 * Add default option filters.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		$options = $this->config->get_keys();
		array_walk( $options, [ $this, 'add_filter' ] );
	}

	/**
	 * Filter the default for an option value.
	 *
	 * @since 0.1.0
	 *
	 * @param  string $option Name of option to retrieve. Expected to not be SQL-escaped.
	 */
	protected function add_filter( $option ) {

		$default = $this->config->get_key( $option );

		add_filter( 'default_option_' . $option, function () use ( $default ) {
			return $default;
		} );
	}

}
