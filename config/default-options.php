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
 * This simple version of a Config file returns a normal PHP array.
 */
return [

	/*
	 * We use only one option that contains two values, first and last name, but we could configure many.
	 *
	 * The key is the option name.
	 */
	'assb1_settings'    => [
		'assb1_text_field_first_name' => 'Elliot',
		'assb1_text_field_last_name' => 'Alderson',
	],

];
