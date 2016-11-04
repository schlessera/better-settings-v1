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

use ArrayObject;
use Exception;
use RuntimeException;

/**
 * Class Config.
 *
 * This is a very basic Config class that can be used to abstract away the
 * loading of a PHP array from a file.
 *
 * @since   0.1.0
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class Config extends ArrayObject implements ConfigInterface {

	/**
	 * Instantiate the Config object.
	 *
	 * @since 0.1.0
	 *
	 * @param array|string $config Array with settings or path to Config file.
	 */
	public function __construct( $config ) {
		// If a string was passed to the constructor, assume it is the path to
		// a PHP Config file.
		if ( is_string( $config ) ) {
			$config = $this->load_uri( $config ) ?: [];
		}

		// Make sure the config entries can be accessed as properties.
		parent::__construct( $config, ArrayObject::ARRAY_AS_PROPS );
	}

	/**
	 * Check whether the Config has a specific key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key The key to check the existence for.
	 *
	 * @return bool Whether the specified key exists.
	 */
	public function has_key( $key ) {
		return array_key_exists( $key, (array) $this );
	}

	/**
	 * Get the value of a specific key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key The key to get the value for.
	 *
	 * @return mixed Value of the requested key.
	 */
	public function get_key( $key ) {
		return $this[ $key ];
	}

	/**
	 * Get an array with all the keys.
	 *
	 * @since 0.1.0
	 *
	 * @return array Array of config keys.
	 */
	public function get_keys() {
		return array_keys( (array) $this );
	}

	/**
	 * Load the contents of a resource identified by an URI.
	 *
	 * @since 0.1.0
	 *
	 * @param string $uri URI of the resource.
	 *
	 * @return array|null Raw data loaded from the resource. Null if no data
	 *                    found.
	 * @throws RuntimeException If the resource could not be loaded.
	 */
	protected function load_uri( $uri ) {
		try {
			// Try to load the file through PHP's include().
			// Make sure we don't accidentally create output.
			ob_start();
			$data = include $uri;
			ob_end_clean();
			return $data;
		} catch ( Exception $exception ) {
			throw new RuntimeException(
				sprintf(
					'Could not include PHP config file "%1$s". Reason: "%2$s".',
					$uri,
					$exception->getMessage()
				),
				$exception->getCode(),
				$exception
			);
		}
	}
}
