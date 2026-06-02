<?php
/**
 * Uninstall cleanup.
 *
 * @package OrbitKit\Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'orbitkit_elementor_active_widgets' );
delete_option( 'orbitkit_elementor_integrations' );
delete_option( 'orbitkit_elementor_active_widgets' );
delete_option( 'orbitkit_elementor_integrations' );
