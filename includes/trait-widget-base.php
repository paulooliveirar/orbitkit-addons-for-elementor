<?php
/**
 * Shared widget helpers.
 *
 * @package RocketKit\Elementor
 */

namespace RocketKit\Elementor\Includes\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RocketKit\Elementor\Includes\RocketKit_Widget_Registry;

/**
 * Widget slug and asset depends.
 */
trait Widget_Base {

	/**
	 * Widget registry slug (e.g. interactive_map).
	 *
	 * @return string
	 */
	abstract protected function get_widget_slug();

	/**
	 * @return array<int, string>
	 */
	public function get_style_depends() {
		return RocketKit_Widget_Registry::get_style_depends( $this->get_widget_slug() );
	}

	/**
	 * @return array<int, string>
	 */
	public function get_script_depends() {
		return RocketKit_Widget_Registry::get_script_depends( $this->get_widget_slug() );
	}
}
