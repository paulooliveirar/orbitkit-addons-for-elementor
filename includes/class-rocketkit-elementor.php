<?php
/**
 * Core plugin class.
 *
 * @package RocketKit\Elementor
 */

namespace RocketKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RocketKit_Elementor {

	/** @var RocketKit_Elementor_Loader */
	protected $loader;

	public function __construct() {
		$this->load_dependencies();
		$this->define_elementor_hooks();
		$this->define_admin_hooks();
	}

	private function load_dependencies() {
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-elementor-i18n.php';
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-elementor-loader.php';
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-widget-registry.php';
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-elementor-settings.php';
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-elementor-privacy.php';
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-elementor-elementor.php';
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/class-rocketkit-sanitizer.php';
		$this->loader = new RocketKit_Elementor_Loader();
	}

	private function define_elementor_hooks() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			$this->loader->add_action( 'admin_notices', array( RocketKit_Elementor_Elementor::class, 'missing_elementor_notice' ) );
			return;
		}

		if ( ! RocketKit_Elementor_Elementor::meets_minimum_elementor_version() ) {
			$this->loader->add_action( 'admin_notices', array( RocketKit_Elementor_Elementor::class, 'minimum_elementor_version_notice' ) );
			return;
		}

		$elementor = new RocketKit_Elementor_Elementor();
		$this->loader->add_action( 'elementor/init', $elementor, 'init' );
	}

	private function define_admin_hooks() {
		$settings = new RocketKit_Elementor_Settings();
		$settings->init();

		$privacy = new RocketKit_Elementor_Privacy();
		$privacy->init();

		$this->loader->add_filter( 'plugin_row_meta', $this, 'plugin_row_meta', 10, 2 );
	}

	/**
	 * @param array<int, string> $links       Links.
	 * @param string             $plugin_file Plugin basename.
	 * @return array<int, string>
	 */
	public function plugin_row_meta( $links, $plugin_file ) {
		if ( plugin_basename( ROCKETKIT_ELEMENTOR_PATH . 'rocketkit-elementor-addon.php' ) !== $plugin_file ) {
			return $links;
		}
		$links[] = '<a href="' . esc_url( 'https://orbittech.com.br' ) . '" target="_blank" rel="noopener noreferrer">'
			. esc_html__( 'Documentation', 'rocketkit-addons-for-elementor' ) . '</a>';
		return $links;
	}

	public function run() {
		$this->loader->run();
	}
}
