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
 * Interface MenuPageSlug.
 *
 * This class defines constants to use as the parent slug for attaching submenu
 * pages to built-in WordPress admin pages.
 *
 * @since   0.1.2
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface MenuPageSlug {

	/**
	 * Slugs to use as parents for the built-in WordPress menu pages.
	 *
	 * @since 0.1.2
	 */
	const DASHBOARD         = 'index.php';
	const POSTS             = 'edit.php';
	const MEDIA             = 'upload.php';
	const PAGES             = 'edit.php?post_type=page';
	const COMMENTS          = 'edit-comments.php';
	const CUSTOM_POST_TYPES = 'edit.php?post_type=your_post_type';
	const APPEARANCE        = 'themes.php';
	const PLUGINS           = 'plugins.php';
	const USERS             = 'users.php';
	const TOOLS             = 'tools.php';
	const SETTINGS          = 'options-general.php';
	const NETWORK_SETTINGS  = 'settings.php';
}
