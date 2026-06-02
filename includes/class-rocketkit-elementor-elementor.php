<?php
/**
 * Elementor integration.
 *
 * @package RocketKit\Elementor
 */

namespace RocketKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers category, widgets, and per-widget assets.
 */
class RocketKit_Elementor_Elementor {

	public function init() {
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/trait-widget-style-controls.php';
		require_once ROCKETKIT_ELEMENTOR_PATH . 'includes/trait-widget-base.php';

		$this->load_widget_files();

		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Load only enabled widget class files.
	 */
	private function load_widget_files() {
		foreach ( RocketKit_Widget_Registry::get_widgets() as $slug => $widget ) {
			if ( ! RocketKit_Elementor_Settings::is_widget_enabled( $slug ) ) {
				continue;
			}
			require_once ROCKETKIT_ELEMENTOR_PATH . $widget['file'];
		}
	}

	/**
	 * @param \Elementor\Elements_Manager $elements_manager Manager.
	 */
	public function register_category( $elements_manager ) {
		$has_active = false;
		foreach ( RocketKit_Widget_Registry::get_widgets() as $slug => $widget ) {
			if ( RocketKit_Elementor_Settings::is_widget_enabled( $slug ) ) {
				$has_active = true;
				break;
			}
		}

		if ( ! $has_active ) {
			return;
		}

		$elements_manager->add_category(
			'rocketkit',
			array(
				'title' => esc_html__( 'RocketKit', 'rocketkit-addons-for-elementor' ),
				'icon'  => 'eicon-kit-parts',
			)
		);
	}

	/**
	 * @param \Elementor\Widgets_Manager $widgets_manager Manager.
	 */
	public function register_widgets( $widgets_manager ) {
		foreach ( RocketKit_Widget_Registry::get_widgets() as $slug => $widget ) {
			if ( ! RocketKit_Elementor_Settings::is_widget_enabled( $slug ) ) {
				continue;
			}
			$class = $widget['class'];
			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class() );
			}
		}
	}

	/**
	 * Register CSS/JS only for enabled widgets.
	 */
	public function register_assets() {
		$needs_leaflet = RocketKit_Elementor_Settings::is_widget_enabled( 'interactive_map' );

		if ( $needs_leaflet ) {
			$leaflet_base = ROCKETKIT_ELEMENTOR_URL . 'assets/vendor/leaflet/';
			wp_register_style( 'leaflet', $leaflet_base . 'leaflet.css', array(), '1.9.4' );
			wp_register_script( 'leaflet', $leaflet_base . 'leaflet.js', array(), '1.9.4', true );
			wp_register_script( 'leaflet-heat', $leaflet_base . 'leaflet-heat.js', array( 'leaflet' ), '0.2.0', true );
		}

		foreach ( RocketKit_Widget_Registry::get_widgets() as $slug => $widget ) {
			if ( ! RocketKit_Elementor_Settings::is_widget_enabled( $slug ) ) {
				continue;
			}

			if ( ! empty( $widget['style'] ) && ! empty( $widget['style_file'] ) ) {
				wp_register_style(
					$widget['style'],
					ROCKETKIT_ELEMENTOR_URL . $widget['style_file'],
					isset( $widget['style_deps'] ) ? $widget['style_deps'] : array(),
					ROCKETKIT_ELEMENTOR_VERSION
				);
			}

			if ( ! empty( $widget['script'] ) && ! empty( $widget['script_file'] ) ) {
				wp_register_script(
					$widget['script'],
					ROCKETKIT_ELEMENTOR_URL . $widget['script_file'],
					isset( $widget['script_deps'] ) ? $widget['script_deps'] : array(),
					ROCKETKIT_ELEMENTOR_VERSION,
					true
				);
			}
		}

		if ( RocketKit_Elementor_Settings::is_widget_enabled( 'interactive_map' ) ) {
			wp_register_style(
				'rocketkit-widget-interactive-map-editor',
				ROCKETKIT_ELEMENTOR_URL . 'assets/css/widget-interactive-map-editor.css',
				array(),
				ROCKETKIT_ELEMENTOR_VERSION
			);

			wp_register_script(
				'rocketkit-widget-interactive-map-editor',
				ROCKETKIT_ELEMENTOR_URL . 'assets/js/widget-interactive-map-editor.js',
				array( 'jquery' ),
				ROCKETKIT_ELEMENTOR_VERSION,
				true
			);

			wp_localize_script(
				'rocketkit-widget-interactive-map-editor',
				'rocketkitMapEditor',
				array(
					'restUrl'    => rest_url( 'rocketkit/v1/geocode' ),
					'nonce'      => wp_create_nonce( 'wp_rest' ),
					'minChars'   => 5,
					'maxResults' => 3,
					'i18n'       => RocketKit_Elementor_I18n::get_map_editor_script_strings(),
				)
			);

		}
	}

	/**
	 * REST routes for editor tools.
	 */
	public function register_rest_routes() {
		register_rest_route(
			'rocketkit/v1',
			'/geocode',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_geocode_location' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'args'                => array(
					'q'     => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'limit' => array(
						'default'           => 3,
						'type'              => 'integer',
						'minimum'           => 1,
						'maximum'           => 3,
						'sanitize_callback' => function ( $value ) {
							return min( 3, max( 1, (int) $value ) );
						},
					),
				),
			)
		);
	}

	/**
	 * Geocode a place name via OpenStreetMap Nominatim (editor only).
	 *
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function rest_geocode_location( $request ) {
		$query = trim( (string) $request->get_param( 'q' ) );
		$limit = (int) $request->get_param( 'limit' );
		if ( $limit < 1 || $limit > 3 ) {
			$limit = 3;
		}

		if ( '' === $query ) {
			return new \WP_Error(
				'rocketkit_empty_query',
				__( 'Search query is empty.', 'rocketkit-addons-for-elementor' ),
				array( 'status' => 400 )
			);
		}

		if ( mb_strlen( $query ) < 5 ) {
			return new \WP_Error(
				'rocketkit_query_too_short',
				__( 'Query must be at least 5 characters.', 'rocketkit-addons-for-elementor' ),
				array( 'status' => 400 )
			);
		}

		$url = add_query_arg(
			array(
				'format'         => 'json',
				'limit'          => $limit,
				'q'              => $query,
				'addressdetails' => 0,
			),
			'https://nominatim.openstreetmap.org/search'
		);

		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 15,
				'headers' => array(
					'User-Agent' => 'RocketKit-Elementor-Addon/' . ROCKETKIT_ELEMENTOR_VERSION . ' (' . home_url() . ')',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code ) {
			return new \WP_Error(
				'rocketkit_geocode_failed',
				__( 'Geocoding service unavailable.', 'rocketkit-addons-for-elementor' ),
				array( 'status' => 502 )
			);
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $body ) || empty( $body ) ) {
			return new \WP_Error(
				'rocketkit_geocode_not_found',
				__( 'Location not found.', 'rocketkit-addons-for-elementor' ),
				array( 'status' => 404 )
			);
		}

		$results = array();
		foreach ( array_slice( $body, 0, $limit ) as $item ) {
			if ( empty( $item['lat'] ) || empty( $item['lon'] ) ) {
				continue;
			}
			$results[] = array(
				'lat'     => (float) $item['lat'],
				'lng'     => (float) $item['lon'],
				'display' => isset( $item['display_name'] ) ? sanitize_text_field( $item['display_name'] ) : $query,
			);
		}

		if ( empty( $results ) ) {
			return new \WP_Error(
				'rocketkit_geocode_not_found',
				__( 'Location not found.', 'rocketkit-addons-for-elementor' ),
				array( 'status' => 404 )
			);
		}

		return rest_ensure_response(
			array(
				'results' => $results,
			)
		);
	}

	/**
	 * Whether Elementor meets the minimum required version.
	 *
	 * @return bool
	 */
	public static function meets_minimum_elementor_version() {
		return defined( 'ELEMENTOR_VERSION' )
			&& version_compare( ELEMENTOR_VERSION, ROCKETKIT_ELEMENTOR_MIN_ELEMENTOR, '>=' );
	}

	/**
	 * Admin notice when Elementor is not installed.
	 */
	public static function missing_elementor_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		echo '<div class="notice notice-warning"><p>';
		esc_html_e( 'RocketKit Addons For Elementor requires Elementor to be installed and active.', 'rocketkit-addons-for-elementor' );
		echo '</p></div>';
	}

	/**
	 * Admin notice when Elementor is too old.
	 */
	public static function minimum_elementor_version_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		echo '<div class="notice notice-warning"><p>';
		printf(
			/* translators: %s: minimum Elementor version number */
			esc_html__( 'RocketKit Addons For Elementor requires Elementor version %s or newer.', 'rocketkit-addons-for-elementor' ),
			esc_html( ROCKETKIT_ELEMENTOR_MIN_ELEMENTOR )
		);
		echo '</p></div>';
	}
}
