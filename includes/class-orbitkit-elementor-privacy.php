<?php
/**
 * Privacy policy suggestions for external services.
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers suggested privacy policy text (WordPress Privacy Policy guide).
 */
class OrbitKit_Elementor_Privacy {

	/**
	 * Register hooks.
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'register_privacy_content' ) );
	}

	/**
	 * Add suggested policy text when the site uses plugin features.
	 */
	public function register_privacy_content() {
		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
			return;
		}

		$content = '<p>' . esc_html__( 'OrbitKit Addons For Elementor may send data to third-party services depending on which widgets and settings you use:', 'orbitkit-addons-for-elementor' ) . '</p>'
			. '<ul>'
			. '<li><strong>OpenStreetMap / CARTO map tiles</strong> — ' . esc_html__( 'When the Interactive Map widget uses the Leaflet provider, visitors\' browsers load map tiles from tile servers. IP addresses and request metadata may be logged by those providers.', 'orbitkit-addons-for-elementor' ) . '</li>'
			. '<li><strong>Google Maps</strong> — ' . esc_html__( 'If you enable Google Maps and add an API key, map data is loaded from Google according to the Google Maps Platform Terms.', 'orbitkit-addons-for-elementor' ) . '</li>'
			. '<li><strong>Nominatim (OpenStreetMap)</strong> — ' . esc_html__( 'In the Elementor editor only, location search may query nominatim.openstreetmap.org. Only authenticated users who can edit content can trigger this.', 'orbitkit-addons-for-elementor' ) . '</li>'
			. '</ul>'
			. '<p>' . esc_html__( 'The plugin stores your widget enable/disable preferences and optional Google Maps API key in the WordPress database. No personal data is collected by the plugin authors.', 'orbitkit-addons-for-elementor' ) . '</p>';

		wp_add_privacy_policy_content(
			'OrbitKit Addons For Elementor',
			wp_kses_post( $content )
		);
	}
}
