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

return [
	'menu_pages'    => [],
	'submenu_pages' => [
		[
			'parent_slug' => 'options-general.php',
			'page_title'  => 'as-settings-better-v1',
			'menu_title'  => 'as-settings-better-v1',
			'capability'  => 'manage_options',
			'menu_slug'   => 'as-settings-better-v1',
			'view'        => AS_BETTER_SETTINGS_1_DIR . 'views/options-page.php',
		],
	],
	'settings'      => [
		'assb1_settings' => [
			'option_group'      => 'pluginPage',
			'sanitize_callback' => null,
			'sections'          => [
				'assb1_pluginPage_section' => [
					'title'  => __( 'Useless Name Settings', 'as-settings-better-v1' ),
					'view'   => AS_BETTER_SETTINGS_1_DIR . 'views/section-description.php',
					'fields' => [
						'assb1_text_field_first_name' => [
							'title' => __( 'First Name', 'as-settings-better-v1' ),
							'view'  => AS_BETTER_SETTINGS_1_DIR . 'views/first-name-field.php',
						],
						'assb1_text_field_last_name'  => [
							'title' => __( 'Last Name', 'as-settings-better-v1' ),
							'view'  => AS_BETTER_SETTINGS_1_DIR . 'views/last-name-field.php',
						],
					],
				],
			],
		],
	],
];
