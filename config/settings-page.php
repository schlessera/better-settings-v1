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

/*
 * -----------------------------------------------------------------------------
 * Admin pages.
 * -----------------------------------------------------------------------------
 */

/*
 * Details for a single page.
 *
 * Valid keys shared between top-level menu pages and submenu pages:
 *
 * 'page_title' (string)  => The text to be displayed in the title tags of the
 *                           page when the menu is selected.
 *
 * 'menu_title' (string)  => The text to be used for the menu.
 *
 * 'capability' (string)  => The capability required for this menu to be
 *                           displayed to the user.
 *
 * 'menu_slug' (string)   => The slug name to refer to this menu by (should be
 *                           unique for this menu).
 *
 * 'view' (string|View)   => View to be used to render the element. Can be a
 *                           path to a view file or an instance of a View class.
 *
 * Valid keys for top-level menu pages only:
 *
 * 'icon_url' (string)    => Optional. The URL to the icon to be used for this
 *                           menu, a base64-encoded SVG, a Dashicons class, or
 *                           'none' for an empty stylable div.
 *
 * 'position' (int)       => The position in the menu order this page should
 *                           appear.
 *
 * Valid keys for submenu pages only:
 *
 * 'parent_slug' (string) => The slug name for the parent menu (or the file
 *                           name of a standard WordPress admin page).
 */
$assb1_page = [
	'parent_slug' => MenuPageSlug::SETTINGS,
	'page_title'  => 'as-settings-better-v1',
	'menu_title'  => 'as-settings-better-v1',
	'capability'  => 'manage_options',
	'view'        => AS_BETTER_SETTINGS_1_DIR . 'views/options-page.php',
];

/*
 * -----------------------------------------------------------------------------
 * Settings.
 * -----------------------------------------------------------------------------
 */

/*
 * Details for a single set of settings.
 *
 * Valid keys:
 *
 * 'option_group' (string) => Optional. Choose a specific name for your options
 *                            group as it is stored in the database. If none
 *                            provided, the option group gets the same name as
 *                            the set of settings.
 *
 * 'sanitization_callback' => Optional. Callback to use for sanitization.
 *    (callable|null)
 *
 * 'sections' (array)      => Array of settings sections to add to the settings
 *                            page.
 */
$assb1_settings = [
	'sections' => [

		/*
		 * Details for a single section.
		 *
		 * Valid keys:
		 *
		 * 'title' (string)     => Title to display as the heading for the
		 *                         section.
		 *
		 * 'view' (string|View) => View to use for rendering the section. Can be
		 *                         a path to a view file or an instance of a
		 *                         View object.
		 *
		 * 'fields' (array)     => Array of settings fields to attach to this
		 *                         section.
		 */

		'assb1_settings_section' => [
			'title'  => __( 'Useless Name Settings', 'as-settings-better-v1' ),
			'view'   => AS_BETTER_SETTINGS_1_DIR . 'views/section-description.php',
			'fields' => [

				/*
				 * Details of a single field.
				 *
				 * Valid keys:
				 *
				 * 'title' (string)     => Title to display as the heading for
				 *                         the section.
				 *
				 * 'default' (mixed)    => Default value to use when none was
				 *                         stored yet.
				 *
				 * 'view' (string|View) => View to use for rendering the
				 *                         section. Can be a path to a view file
				 *                         or an instance of a View object.
				 */

				'assb1_text_field_first_name' => [
					'title'   => __( 'First Name', 'as-settings-better-v1' ),
					'view'    => AS_BETTER_SETTINGS_1_DIR . 'views/first-name-field.php',
				],
				'assb1_text_field_last_name'  => [
					'title'   => __( 'Last Name', 'as-settings-better-v1' ),
					'view'    => AS_BETTER_SETTINGS_1_DIR . 'views/last-name-field.php',
				],
			],
		],
	],
];

/*
 * -----------------------------------------------------------------------------
 * Assemble Config.
 * -----------------------------------------------------------------------------
 */

/*
 * This simple version of a Config file returns a normal PHP array.
 *
 * We've split up the array into several variables within this file to avoid
 * too many levels of indentation. However, the entire Config could just as well
 * be directly coded as one single array.
 */
return [

	/*
	 * Array of pages to be added.
	 *
	 * Each entry's key is the menu slug of the page to be registered. The page
	 * is either registered as a top-level menu page or a submenu page,
	 * depending on whether there is a parent slug property attached to it.
	 */
	'pages'    => [
		'as-settings-better-v1' => $assb1_page,
	],

	/*
	 * Array of settings to be registered.
	 *
	 * Each entry's key is the name of a coherent set of settings that are
	 * persisted as a whole and are accessed as one combined array.
	 */
	'settings' => [
		'assb1_settings' => $assb1_settings,
	],
];
