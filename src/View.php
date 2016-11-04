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
 * Class View.
 *
 * Accepts a filename of a PHP file and renders its content on request.
 *
 * @package AlainSchlesser\BetterSettings1
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class View {

	/**
	 * Filename of the PHP view to render.
	 *
	 * @var string
	 */
	protected $filename;

	/**
	 * Instantiate a View object.
	 *
	 * @param string $filename Filename of the PHP view to render.
	 */
	public function __construct( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * Render the associated view.
	 *
	 * @param array $context Optional. Associative array with context variables.
	 * @return string HTML rendering of the view.
	 */
	public function render( array $context = [] ) {
		if ( ! is_readable( $this->filename ) ) {
			return '';
		}

		// We use output buffering to capture the output of the PHP view.
		ob_start();

		// We extract the `$context` variable so that its individual elements
		// are available as variables within the view's namespace.
		extract( $context );

		include $this->filename;
		return ob_get_clean();
	}
}
