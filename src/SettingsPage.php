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

use InvalidArgumentException;

/**
 * Class SettingsPage.
 *
 * This class registers a settings page via the WordPress Settings API.
 *
 * It enables you an entire collection of settings pages and options fields as
 * as hierarchical text representation in your Config file. In this way, you
 * don't have to deal with all the confusing callback code that the WordPress
 * Settings API forces you to use.
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class SettingsPage {

	use FunctionInvokerTrait;

	/**
	 * Config instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ConfigInterface;
	 */
	protected $config;

	/**
	 * Hooks to the settings pages that have been registered.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $page_hooks = array();

	/**
	 * Instantiate Settings object.
	 *
	 * @since 0.1.0
	 *
	 * @param ConfigInterface $config Config object that contains Settings
	 *                                configuration.
	 */
	public function __construct( ConfigInterface $config ) {
		$this->config = $config;
	}

	/**
	 * Register necessary hooks.
	 *
	 * @since 0.1.0
	 */
	public function register() {
		add_action( 'admin_menu', [ $this, 'add_pages' ] );
		add_action( 'admin_init', [ $this, 'init_settings' ] );
	}

	/**
	 * Add the pages from the configuration settings to the WordPress admin
	 * backend.
	 *
	 * @since 0.1.0
	 */
	public function add_pages() {
		$pages = [ 'menu_page', 'submenu_page' ];
		foreach ( $pages as $page ) {
			if ( $this->config->has_key( "${page}s" ) ) {
				$pages = $this->config->get_key( "${page}s" );
				array_walk( $pages, [ $this, 'add_page' ], "add_${page}" );
			}
		}
	}

	/**
	 * Initialize the settings page.
	 *
	 * @since 0.1.0
	 */
	public function init_settings() {
		if ( $this->config->has_key( 'settings' ) ) {
			$settings = $this->config->get_key( 'settings' );
			array_walk(
				$settings,
				[ $this, 'add_setting' ]
			);
		}
	}

	/**
	 * Add a single page to the WordPress admin backend.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $data              Arguments for page creation function.
	 * @param string $key               Current page name.
	 * @param string $function          Page creation function to be used. Must
	 *                                  be either
	 *                                  'add_menu_page' or 'add_submenu_page'.
	 * @throws InvalidArgumentException If the page addition function could not
	 *                                  be invoked.
	 */
	protected function add_page( $data, $key, $function ) {
		// Skip page creation if it already exists. This allows reuse of 1 page
		// for several plugins.
		if ( empty( $GLOBALS['admin_page_hooks'][ $data['menu_slug'] ] ) ) {
			$data['function']   = function () use ( $data ) {
				if ( array_key_exists( 'view', $data ) ) {
					$view = new View( $data['view'] );
					echo $view->render();
				}
			};
			$page_hook          = $this->invoke_function( $function, $data );
			$this->page_hooks[] = $page_hook;
		}
	}

	/**
	 * Add option groups.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $setting_data Arguments for the register_setting WP
	 *                             function.
	 * @param string $setting_name Name of the option group.
	 */
	protected function add_setting( $setting_data, $setting_name ) {
		register_setting(
			$setting_data['option_group'],
			$setting_name,
			$setting_data['sanitize_callback']
		);

		// Prepare array to pass to array_walk as third parameter.
		$args['setting_name'] = $setting_name;
		$args['page']         = $setting_data['option_group'];
		array_walk( $setting_data['sections'], [
			$this,
			'add_section',
		], $args );
	}

	/**
	 * Add options section.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $section_data Arguments for the add_settings_section WP
	 *                             function.
	 * @param string $section_name Name of the option section.
	 * @param string $args         Additional arguments to pass on.
	 */
	protected function add_section( $section_data, $section_name, $args ) {
		add_settings_section(
			$section_name,
			$section_name,
			function () use ( $section_data ) {
				if ( array_key_exists( 'view', $section_data ) ) {
					$view = new View( $section_data['view'] );
					echo $view->render();
				}
			},
			$args['page']
		);

		// Extend array to pass to array_walk as third parameter.
		$args['section'] = $section_name;
		array_walk( $section_data['fields'], [ $this, 'add_field' ], $args );
	}

	/**
	 * Add options field.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $field_data Arguments for the add_settings_field WP
	 *                           function.
	 * @param string $field_name Name of the settings field.
	 * @param array  $args       Contains both page and section name.
	 */
	protected function add_field( $field_data, $field_name, $args ) {
		add_settings_field(
			$field_name,
			$field_data['title'],
			function () use ( $field_data, $args ) {
				// Fetch $options to pass into view.
				$options = get_option( $args['setting_name'] );
				if ( array_key_exists( 'view', $field_data ) ) {
					$view = new View( $field_data['view'] );
					echo $view->render( [
						'options' => $options,
					] );
				}
			},
			$args['page'],
			$args['section']
		);
	}
}
