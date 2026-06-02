<?php
/**
 * RocketKit Addons For Elementor
 *
 * @package           RocketKit\Elementor
 * @author            Paulo Oliveira Rodrigues <paulo.rodrigues@orbittech.com.br>
 * @copyright         2026 Orbittech — suporte@orbittech.com.br
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       RocketKit Addons For Elementor
 * Description:       Extends Elementor with RocketKit widgets: maps, pricing, team, countdown, image compare, image stack, and more — with full style controls.
 * Version:           1.5.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * Author:            Paulo Oliveira Rodrigues
 * Author URI:        https://orbittech.com.br
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rocketkit-addons-for-elementor
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROCKETKIT_ELEMENTOR_VERSION', '1.5.0' );
define( 'ROCKETKIT_ELEMENTOR_MIN_ELEMENTOR', '3.5.0' );
define( 'ROCKETKIT_ELEMENTOR_NAME', 'RocketKit Addons For Elementor' );
define( 'ROCKETKIT_ELEMENTOR_SLUG', 'rocketkit-addons-for-elementor' );
define( 'ROCKETKIT_ELEMENTOR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ROCKETKIT_ELEMENTOR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin activation: seed default options.
 */
function rocketkit_elementor_addon_activate() {
	require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-widget-registry.php';
	require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-elementor-settings.php';

	if ( false === get_option( RocketKit\Elementor\Includes\RocketKit_Elementor_Settings::OPTION_KEY ) ) {
		add_option(
			RocketKit\Elementor\Includes\RocketKit_Elementor_Settings::OPTION_KEY,
			RocketKit\Elementor\Includes\RocketKit_Elementor_Settings::get_default_widgets()
		);
	}

	if ( false === get_option( RocketKit\Elementor\Includes\RocketKit_Elementor_Settings::INTEGRATIONS_OPTION_KEY ) ) {
		add_option(
			RocketKit\Elementor\Includes\RocketKit_Elementor_Settings::INTEGRATIONS_OPTION_KEY,
			RocketKit\Elementor\Includes\RocketKit_Elementor_Settings::get_default_integrations()
		);
	}
}

register_activation_hook( __FILE__, 'rocketkit_elementor_addon_activate' );

/**
 * Bootstrap plugin.
 */
function rocketkit_elementor_addon_run() {
	require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-elementor.php';
	$plugin = new RocketKit\Elementor\Includes\RocketKit_Elementor();
	$plugin->run();
}

add_action( 'plugins_loaded', 'rocketkit_elementor_addon_run', 20 );
