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
		$this->iterate( 'page' );
	}

	/**
	 * Initialize the settings persistence.
	 *
	 * @since 0.1.0
	 */
	public function init_settings() {
		$this->iterate( 'setting' );
	}

	/**
	 * Iterate over a given collection of elements.
	 *
	 * @since 0.1.2
	 *
	 * @param string $element Type of element to iterate over.
	 */
	protected function iterate( $element ) {
		if ( ! $this->config->has_key( "${element}s" ) ) {
			return;
		}

		$elements = $this->config->get_key( "${element}s" );
		array_walk( $elements, [ $this, "add_${element}" ] );
	}

	/**
	 * Add a single page to the WordPress admin backend.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $data              Arguments for page creation function.
	 * @param string $key               Current page name.
	 * @throws InvalidArgumentException If the page addition function could not
	 *                                  be invoked.
	 */
	protected function add_page( $data, $key ) {
		// Skip page creation if it already exists. This allows reuse of 1 page
		// for several plugins.
		if ( ! empty( $GLOBALS['admin_page_hooks'][ $data['menu_slug'] ] ) ) {
			return;
		}

		// If we have a parent slug, add as a submenu instead of a menu.
		$function = array_key_exists( 'parent_slug', $data )
			? 'add_submenu_page'
			: 'add_menu_page';

		// Prepare rendering callback.
		$data['function'] = function () use ( $data ) {
			if ( array_key_exists( 'view', $data ) ) {
				$view = new View( $data['view'] );
				echo $view->render();
			}
		};

		$page_hook          = $this->invoke_function( $function, $data );
		$this->page_hooks[] = $page_hook;
	}

	/**
	 * Add option groups.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $data Arguments for the register_setting WP function.
	 * @param string $name Name of the option group.
	 */
	protected function add_setting( $data, $name ) {
		register_setting(
			$data['option_group'],
			$name,
			$data['sanitize_callback']
		);

		// Prepare array to pass to array_walk as third parameter.
		$args['setting_name'] = $name;
		$args['page']         = $data['option_group'];

		array_walk( $data['sections'], [ $this, 'add_section' ], $args );
	}

	/**
	 * Add options section.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $data Arguments for the add_settings_section WP function.
	 * @param string $name Name of the option section.
	 * @param string $args Additional arguments to pass on.
	 */
	protected function add_section( $data, $name, $args ) {
		// prepare the rendering callback.
		$render_callback = function () use ( $data ) {
			if ( array_key_exists( 'view', $data ) ) {
				$view = new View( $data['view'] );
				echo $view->render();
			}
		};

		add_settings_section(
			$name,
			$data['title'],
			$render_callback,
			$args['page']
		);

		// Extend array to pass to array_walk as third parameter.
		$args['section'] = $name;

		array_walk( $data['fields'], [ $this, 'add_field' ], $args );
	}

	/**
	 * Add options field.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $data Arguments for the add_settings_field WP function.
	 * @param string $name Name of the settings field.
	 * @param array  $args Contains both page and section name.
	 */
	protected function add_field( $data, $name, $args ) {
		// Prepare the rendering callback.
		$render_callback = function () use ( $data, $args ) {
			// Fetch $options to pass into view.
			$options = get_option( $args['setting_name'] );
			if ( array_key_exists( 'view', $data ) ) {
				$view = new View( $data['view'] );
				echo $view->render( [
					'options' => $options,
				] );
			}
		};

		add_settings_field(
			$name,
			$data['title'],
			$render_callback,
			$args['page'],
			$args['section']
		);
	}
}
