<?php
/**
 * Plugin settings admin UI.
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Top-level menu and Essential Addons–style settings page.
 */
class OrbitKit_Elementor_Settings {

	const OPTION_KEY            = 'orbitkit_elementor_active_widgets';
	const INTEGRATIONS_OPTION_KEY = 'orbitkit_elementor_integrations';
	const PAGE_SLUG               = 'orbitkit-elementor';

	/**
	 * Register hooks.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_filter(
			'plugin_action_links_' . plugin_basename( ORBITKIT_ELEMENTOR_PATH . 'orbitkit-elementor-addon.php' ),
			array( $this, 'plugin_action_links' )
		);
	}

	/**
	 * @param array<string, string> $links Plugin links.
	 * @return array<string, string>
	 */
	public function plugin_action_links( $links ) {
		$links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=' . self::PAGE_SLUG ) ) . '">'
			. esc_html__( 'Settings', 'orbitkit-addons-for-elementor' ) . '</a>';
		return $links;
	}

	/**
	 * Top-level admin menu.
	 */
	public function add_menu_page() {
		add_menu_page(
			__( 'OrbitKit Addons For Elementor', 'orbitkit-addons-for-elementor' ),
			__( 'OrbitKit Addons', 'orbitkit-addons-for-elementor' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_settings_page' ),
			'dashicons-superhero',
			58
		);
	}

	/**
	 * Register option.
	 */
	public function register_settings() {
		register_setting(
			'orbitkit_elementor_settings_group',
			self::OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_widgets' ),
				'default'           => self::get_default_widgets(),
				'show_in_rest'      => false,
			)
		);

		register_setting(
			'orbitkit_elementor_settings_group',
			self::INTEGRATIONS_OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_integrations' ),
				'default'           => self::get_default_integrations(),
				'show_in_rest'      => false,
			)
		);
	}

	/**
	 * @return array<string, string>
	 */
	public static function get_default_integrations() {
		return array(
			'google_maps_api_key' => '',
		);
	}

	/**
	 * @param mixed $input Raw input.
	 * @return array<string, string>
	 */
	public function sanitize_integrations( $input ) {
		return array(
			'google_maps_api_key' => is_array( $input ) && isset( $input['google_maps_api_key'] )
				? sanitize_text_field( $input['google_maps_api_key'] )
				: '',
		);
	}

	/**
	 * Google Maps API key from Integrations settings.
	 *
	 * @return string
	 */
	public static function get_google_maps_api_key() {
		$integrations = get_option( self::INTEGRATIONS_OPTION_KEY, self::get_default_integrations() );
		if ( ! is_array( $integrations ) ) {
			return '';
		}
		return isset( $integrations['google_maps_api_key'] ) ? $integrations['google_maps_api_key'] : '';
	}

	/**
	 * @return array<string, string>
	 */
	public static function get_integrations() {
		$integrations = get_option( self::INTEGRATIONS_OPTION_KEY, null );
		if ( ! is_array( $integrations ) ) {
			return self::get_default_integrations();
		}
		return wp_parse_args( $integrations, self::get_default_integrations() );
	}

	/**
	 * @param string $hook Page hook.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_' . self::PAGE_SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'orbitkit-admin-settings',
			ORBITKIT_ELEMENTOR_URL . 'assets/css/admin-settings.css',
			array(),
			ORBITKIT_ELEMENTOR_VERSION
		);

		wp_enqueue_script(
			'orbitkit-admin-settings',
			ORBITKIT_ELEMENTOR_URL . 'assets/js/admin-settings.js',
			array( 'jquery' ),
			ORBITKIT_ELEMENTOR_VERSION,
			true
		);

		wp_localize_script(
			'orbitkit-admin-settings',
			'orbitkitAdmin',
			array(
				'i18n' => OrbitKit_Elementor_I18n::get_admin_script_strings(),
			)
		);
	}

	/**
	 * @return array<string, int>
	 */
	public static function get_default_widgets() {
		$defaults = array();
		foreach ( array_keys( OrbitKit_Widget_Registry::get_widgets() ) as $slug ) {
			$defaults[ $slug ] = 1;
		}
		return $defaults;
	}

	/**
	 * @param mixed $input Raw input.
	 * @return array<string, int>
	 */
	public function sanitize_widgets( $input ) {
		$sanitized = array();
		foreach ( OrbitKit_Widget_Registry::get_widgets() as $slug => $config ) {
			$val = is_array( $input ) && isset( $input[ $slug ] ) ? $input[ $slug ] : 0;
			$sanitized[ $slug ] = ( '1' === (string) $val || 1 === $val ) ? 1 : 0;
		}
		return $sanitized;
	}

	/**
	 * @param string $slug Widget slug.
	 * @return bool
	 */
	public static function is_widget_enabled( $slug ) {
		$active = self::get_active_widgets();
		return ! empty( $active[ $slug ] );
	}

	/**
	 * @return array<string, int>
	 */
	public static function get_active_widgets() {
		$active = get_option( self::OPTION_KEY, null );
		if ( null === $active || ! is_array( $active ) ) {
			return self::get_default_widgets();
		}
		return wp_parse_args( $active, self::get_default_widgets() );
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$orbitkit_widgets = OrbitKit_Widget_Registry::get_widgets();
		$orbitkit_active  = self::get_active_widgets();
		$orbitkit_grouped = array(
			'content' => array(),
			'media'   => array(),
		);

		foreach ( $orbitkit_widgets as $orbitkit_slug => $orbitkit_widget ) {
			$orbitkit_cat = isset( $orbitkit_widget['category'] ) ? $orbitkit_widget['category'] : 'content';
			if ( ! isset( $orbitkit_grouped[ $orbitkit_cat ] ) ) {
				$orbitkit_grouped[ $orbitkit_cat ] = array();
			}
			$orbitkit_grouped[ $orbitkit_cat ][ $orbitkit_slug ] = $orbitkit_widget;
		}

		require_once ORBITKIT_ELEMENTOR_PATH . 'admin/views/settings-page.php';

		orbitkit_elementor_render_settings_page_view(
			array(
				'widgets'          => $orbitkit_widgets,
				'active'           => $orbitkit_active,
				'categories'       => OrbitKit_Widget_Registry::get_categories(),
				'integrations'     => self::get_integrations(),
				'option_key'       => self::OPTION_KEY,
				'integrations_key' => self::INTEGRATIONS_OPTION_KEY,
				'grouped'          => $orbitkit_grouped,
				'total_widgets'    => count( $orbitkit_widgets ),
				'enabled_count'    => count( array_filter( $orbitkit_active ) ),
				'has_maps_key'     => '' !== self::get_google_maps_api_key(),
			)
		);
	}
}
