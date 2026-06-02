<?php
/**
 * Internationalization helpers.
 *
 * PHP: use __(), _e(), esc_html__(), etc. with text domain `rocketkit-addons-for-elementor`
 * (must match the plugin slug). Translations: /languages/{domain}-{locale}.mo.
 *
 * WordPress 4.6+ loads plugin translations automatically; do not call
 * load_plugin_textdomain(). Regenerate files with WP-CLI (see languages/readme.txt).
 *
 * @see https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/
 * @package RocketKit\Elementor
 */

namespace RocketKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared strings for wp_localize_script (JavaScript has no gettext).
 */
class RocketKit_Elementor_I18n {

	/**
	 * Admin settings screen (assets/js/admin-settings.js).
	 *
	 * @return array<string, string>
	 */
	public static function get_admin_script_strings() {
		return array(
			'active'     => __( 'Active', 'rocketkit-addons-for-elementor' ),
			'inactive'   => __( 'Inactive', 'rocketkit-addons-for-elementor' ),
			'configured' => __( 'Configured', 'rocketkit-addons-for-elementor' ),
			'notSet'     => __( 'Not set', 'rocketkit-addons-for-elementor' ),
			'enableAll'  => __( 'Enable all', 'rocketkit-addons-for-elementor' ),
			'disableAll' => __( 'Disable all', 'rocketkit-addons-for-elementor' ),
		);
	}

	/**
	 * Interactive Map Elementor editor (assets/js/widget-interactive-map-editor.js).
	 *
	 * @return array<string, string>
	 */
	public static function get_map_editor_script_strings() {
		return array(
			'searching' => __( 'Searching…', 'rocketkit-addons-for-elementor' ),
			'found'     => __( 'Coordinates updated.', 'rocketkit-addons-for-elementor' ),
			'notFound'  => __( 'No places found. Try another search.', 'rocketkit-addons-for-elementor' ),
			'typeMore'  => __( 'Type at least 5 characters to see suggestions.', 'rocketkit-addons-for-elementor' ),
			'error'     => __( 'Search failed. Please try again.', 'rocketkit-addons-for-elementor' ),
		);
	}
}
