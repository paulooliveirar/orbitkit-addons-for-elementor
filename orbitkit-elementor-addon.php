<?php
/**
 * OrbitKit Addons For Elementor
 *
 * @package           OrbitKit\Elementor
 * @author            Orbittech <suporte@orbittech.com.br>
 * @copyright         2026 Orbittech — suporte@orbittech.com.br
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       OrbitKit Addons For Elementor
 * Description:       Extends Elementor with OrbitKit widgets: maps, pricing, team, countdown, image compare, image stack, and more — with full style controls.
 * Version:           1.5.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * Author:            Orbittech
 * Author Email:      suporte@orbittech.com.br
 * Author URI:        https://orbittech.com.br
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       orbitkit-addons-for-elementor
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ORBITKIT_ELEMENTOR_VERSION', '1.5.0' );
define( 'ORBITKIT_ELEMENTOR_MIN_ELEMENTOR', '3.5.0' );
define( 'ORBITKIT_ELEMENTOR_NAME', 'OrbitKit Addons For Elementor' );
define( 'ORBITKIT_ELEMENTOR_SLUG', 'orbitkit-addons-for-elementor' );
define( 'ORBITKIT_ELEMENTOR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ORBITKIT_ELEMENTOR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin activation: seed default options.
 */
function orbitkit_elementor_addon_activate() {
	require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-widget-registry.php';
	require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-elementor-settings.php';

	$widgets_key       = OrbitKit\Elementor\Includes\OrbitKit_Elementor_Settings::OPTION_KEY;
	$integrations_key  = OrbitKit\Elementor\Includes\OrbitKit_Elementor_Settings::INTEGRATIONS_OPTION_KEY;
	$legacy_widgets    = get_option( 'orbitkit_elementor_active_widgets', false );
	$legacy_integrations = get_option( 'orbitkit_elementor_integrations', false );

	if ( false !== $legacy_widgets && false === get_option( $widgets_key ) ) {
		update_option( $widgets_key, $legacy_widgets );
		delete_option( 'orbitkit_elementor_active_widgets' );
	}

	if ( false !== $legacy_integrations && false === get_option( $integrations_key ) ) {
		update_option( $integrations_key, $legacy_integrations );
		delete_option( 'orbitkit_elementor_integrations' );
	}

	if ( false === get_option( $widgets_key ) ) {
		add_option( $widgets_key, OrbitKit\Elementor\Includes\OrbitKit_Elementor_Settings::get_default_widgets() );
	}

	if ( false === get_option( $integrations_key ) ) {
		add_option( $integrations_key, OrbitKit\Elementor\Includes\OrbitKit_Elementor_Settings::get_default_integrations() );
	}
}

register_activation_hook( __FILE__, 'orbitkit_elementor_addon_activate' );

/**
 * Bootstrap plugin.
 */
function orbitkit_elementor_addon_run() {
	require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-elementor.php';
	$plugin = new OrbitKit\Elementor\Includes\OrbitKit_Elementor();
	$plugin->run();
}

add_action( 'plugins_loaded', 'orbitkit_elementor_addon_run', 20 );
