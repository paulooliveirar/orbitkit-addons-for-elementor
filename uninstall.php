<?php
/**
 * Uninstall cleanup.
 *
 * @package RocketKit\Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'rocketkit_elementor_active_widgets' );
delete_option( 'rocketkit_elementor_integrations' );
