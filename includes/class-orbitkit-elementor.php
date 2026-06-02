<?php
/**
 * Core plugin class.
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class OrbitKit_Elementor {

	/** @var OrbitKit_Elementor_Loader */
	protected $loader;

	public function __construct() {
		$this->load_dependencies();
		$this->define_elementor_hooks();
		$this->define_admin_hooks();
	}

	private function load_dependencies() {
		require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-elementor-i18n.php';
		require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-elementor-loader.php';
		require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-widget-registry.php';
		require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-elementor-settings.php';
		require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-elementor-privacy.php';
		require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-elementor-elementor.php';
		require_once ORBITKIT_ELEMENTOR_PATH . 'includes/class-orbitkit-sanitizer.php';
		$this->loader = new OrbitKit_Elementor_Loader();
	}

	private function define_elementor_hooks() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			$this->loader->add_action( 'admin_notices', array( OrbitKit_Elementor_Elementor::class, 'missing_elementor_notice' ) );
			return;
		}

		if ( ! OrbitKit_Elementor_Elementor::meets_minimum_elementor_version() ) {
			$this->loader->add_action( 'admin_notices', array( OrbitKit_Elementor_Elementor::class, 'minimum_elementor_version_notice' ) );
			return;
		}

		$elementor = new OrbitKit_Elementor_Elementor();
		$this->loader->add_action( 'elementor/init', $elementor, 'init' );
	}

	private function define_admin_hooks() {
		$settings = new OrbitKit_Elementor_Settings();
		$settings->init();

		$privacy = new OrbitKit_Elementor_Privacy();
		$privacy->init();

		$this->loader->add_filter( 'plugin_row_meta', $this, 'plugin_row_meta', 10, 2 );
	}

	/**
	 * @param array<int, string> $links       Links.
	 * @param string             $plugin_file Plugin basename.
	 * @return array<int, string>
	 */
	public function plugin_row_meta( $links, $plugin_file ) {
		if ( plugin_basename( ORBITKIT_ELEMENTOR_PATH . 'orbitkit-elementor-addon.php' ) !== $plugin_file ) {
			return $links;
		}
		$links[] = '<a href="' . esc_url( 'https://orbittech.com.br' ) . '" target="_blank" rel="noopener noreferrer">'
			. esc_html__( 'Documentation', 'orbitkit-addons-for-elementor' ) . '</a>';
		return $links;
	}

	public function run() {
		$this->loader->run();
	}
}
