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

	/*
	 * Configuration keys to use for the different Config sections.
	 */
	const CONFIG_KEY_PAGES    = 'pages';
	const CONFIG_KEY_SETTINGS = 'settings';

	/**
	 * Config instance.
	 *
	 * @since 0.1.0
	 *
	 * @var ConfigInterface;
	 */
	protected $config;

	/**
	 * Option store instance.
	 *
	 * @since 0.1.0
	 *
	 * @var OptionStoreInterface;
	 */
	protected $option_store;

	/**
	 * Hooks to the settings pages that have been registered.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $page_hooks = [];

	/**
	 * Array of allowed tags to let through escaping.
	 *
	 * @since 0.1.4
	 *
	 * @var array
	 */
	protected $allowed_tags = [];

	/**
	 * Instantiate Settings object.
	 *
	 * @since 0.1.0
	 *
	 * @param ConfigInterface $config       Config object that contains
	 *                                      Settings
	 *                                      configuration.
	 * @param OptionStoreInterface $option_store Option store.
	 * @param array|null      $allowed_tags Optional. Array of allowed tags to
	 *                                      let through escaping functions. Set
	 *                                      to sane defaults if none provided.
	 */
	public function __construct( ConfigInterface $config, OptionStoreInterface $option_store, array $allowed_tags = null ) {
		global $allowedposttags;
		$this->config       = $config;
		$this->option_store = $option_store;
		$this->allowed_tags = null === $allowed_tags
			? $this->prepare_allowed_tags( $allowedposttags )
			: $allowed_tags;
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
		$this->iterate( static::CONFIG_KEY_PAGES );
	}

	/**
	 * Initialize the settings persistence.
	 *
	 * @since 0.1.0
	 */
	public function init_settings() {
		$this->iterate( static::CONFIG_KEY_SETTINGS );
	}

	/**
	 * Iterate over a given collection of Config entries.
	 *
	 * @since 0.1.2
	 *
	 * @param string $type Type of entries to iterate over.
	 */
	protected function iterate( $type ) {
		if ( ! $this->config->has_key( "${type}" ) ) {
			return;
		}

		$entries = $this->config->get_key( "${type}" );
		array_walk( $entries, [ $this, "add_${type}_entry" ] );
	}

	/**
	 * Add a single page to the WordPress admin backend.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $data              Arguments for page creation function.
	 * @param string $name              Current page name.
	 * @throws InvalidArgumentException If the page addition function could not
	 *                                  be invoked.
	 */
	protected function add_pages_entry( $data, $name ) {
		// Skip page creation if it already exists. This allows reuse of 1 page
		// for several plugins.
		if ( ! empty( $GLOBALS['admin_page_hooks'][ $name ] ) ) {
			return;
		}

		// If we have a parent slug, add as a submenu instead of a menu.
		$function = array_key_exists( 'parent_slug', $data )
			? 'add_submenu_page'
			: 'add_menu_page';

		// Add the page name as manue slug.
		$data['menu_slug'] = $name;

		// Prepare rendering callback.
		$data['function'] = function () use ( $data ) {
			if ( ! array_key_exists( 'view', $data ) ) {
				return;
			}

			$this->render_view( $data['view'] );
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
	protected function add_settings_entry( $data, $name ) {
		// Default to using the same option group name as the settings name.
		$option_group = isset( $data['option_group'] )
			? $data['option_group']
			: $name;

		register_setting(
			$option_group,
			$name,
			// Optionally use a sanitization callback.
			isset( $data['sanitize_callback'] )
				? $data['sanitize_callback']
				: null
		);

		// Prepare array to pass to array_walk as third parameter.
		$args['setting_name'] = $name;
		$args['page']         = $option_group;

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
			if ( ! array_key_exists( 'view', $data ) ) {
				return;
			}

			$this->render_view( $data['view'] );
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
		$render_callback = function () use ( $data, $name, $args ) {
			if ( ! array_key_exists( 'view', $data ) ) {
				return;
			}

			// Fetch $options to pass into view.
			$options = $this->option_store->get_option( $args['setting_name'] );

			$this->render_view( $data['view'], [ 'options' => $options ] );
		};

		add_settings_field(
			$name,
			$data['title'],
			$render_callback,
			$args['page'],
			$args['section']
		);
	}

	/**
	 * Render a given view.
	 *
	 * @since 0.1.4
	 *
	 * @param string|View $view    View to render. Can be a path to a view file
	 *                             or an instance of a View object.
	 * @param array|null  $context Optional. Context array for which to render
	 *                             the view.
	 */
	protected function render_view( $view, array $context = [] ) {
		$view_object = is_string( $view ) ? new View( $view ) : $view;
		echo wp_kses(
			$view_object->render( $context ),
			$this->allowed_tags
		);
	}

	/**
	 * Prepare an array of allowed tags by adding form elements to the existing
	 * array.
	 *
	 * This makes sure that the basic form elements always pass through the
	 * escaping functions.
	 *
	 * @since 0.1.4
	 *
	 * @param array $allowed_tags Allowed tags as fetched from the WordPress
	 *                            defaults.
	 * @return array Modified tags array.
	 */
	protected function prepare_allowed_tags( $allowed_tags ) {
		$form_tags = [
			'form'  => [
				'id'     => true,
				'class'  => true,
				'action' => true,
				'method' => true,
			],
			'input' => [
				'id'    => true,
				'class' => true,
				'type'  => true,
				'name'  => true,
				'value' => true,
			],
		];
		return array_replace_recursive( $allowed_tags, $form_tags );
	}
}
