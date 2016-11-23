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
 * Class Plugin.
 *
 * This class hooks our plugin into the WordPress life-cycle.
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class Plugin {

	/**
	 * Options store instance.
	 *
	 * @since 0.1.0
	 *
	 * @var OptionsStoreInterface;
	 */
	protected $options_store;

	/**
	 * Launch the initialization process.
	 */
	public function init() {
		$this->init_options_store();
		add_action( 'plugins_loaded', [ $this, 'init_settings_page' ] );
	}

	/**
	 * Initialize OptionsStore.
	 */
	protected function init_options_store() {
		// Load configuration for the option store.
		$default_options_config = new Config( AS_BETTER_SETTINGS_1_DIR . 'config/default-options.php' );
		// Initialize option store.
		$this->options_store = new OptionsStore( $default_options_config );
	}

	/**
	 * Initialize Settings page.
	 */
	public function init_settings_page() {
		// Load configuration for the settings page.
		$config = new Config( AS_BETTER_SETTINGS_1_DIR . 'config/settings-page.php' );
		// Initialize settings page.
		$settings_page = new SettingsPage( $config, $this->options_store );
		// Register the settings page with WordPress.
		add_action( 'init', [ $settings_page, 'register' ] );
	}
}
