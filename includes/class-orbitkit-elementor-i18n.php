<?php
/**
 * Internationalization helpers.
 *
 * PHP: use __(), _e(), esc_html__(), etc. with text domain `orbitkit-addons-for-elementor`
 * (must match the plugin slug). Translations: /languages/{domain}-{locale}.mo.
 *
 * WordPress 4.6+ loads plugin translations automatically; do not call
 * load_plugin_textdomain(). Regenerate files with WP-CLI (see languages/readme.txt).
 *
 * @see https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared strings for wp_localize_script (JavaScript has no gettext).
 */
class OrbitKit_Elementor_I18n {

	/**
	 * Admin settings screen (assets/js/admin-settings.js).
	 *
	 * @return array<string, string>
	 */
	public static function get_admin_script_strings() {
		return array(
			'active'     => __( 'Active', 'orbitkit-addons-for-elementor' ),
			'inactive'   => __( 'Inactive', 'orbitkit-addons-for-elementor' ),
			'configured' => __( 'Configured', 'orbitkit-addons-for-elementor' ),
			'notSet'     => __( 'Not set', 'orbitkit-addons-for-elementor' ),
			'enableAll'  => __( 'Enable all', 'orbitkit-addons-for-elementor' ),
			'disableAll' => __( 'Disable all', 'orbitkit-addons-for-elementor' ),
		);
	}

	/**
	 * Interactive Map Elementor editor (assets/js/widget-interactive-map-editor.js).
	 *
	 * @return array<string, string>
	 */
	public static function get_map_editor_script_strings() {
		return array(
			'searching' => __( 'Searching…', 'orbitkit-addons-for-elementor' ),
			'found'     => __( 'Coordinates updated.', 'orbitkit-addons-for-elementor' ),
			'notFound'  => __( 'No places found. Try another search.', 'orbitkit-addons-for-elementor' ),
			'typeMore'  => __( 'Type at least 5 characters to see suggestions.', 'orbitkit-addons-for-elementor' ),
			'error'     => __( 'Search failed. Please try again.', 'orbitkit-addons-for-elementor' ),
		);
	}
}
