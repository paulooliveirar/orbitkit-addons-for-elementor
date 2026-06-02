<?php
/**
 * Widget registry (slugs, assets, classes).
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Central widget definitions.
 */
class OrbitKit_Widget_Registry {

	/**
	 * @return array<string, array<string, mixed>>
	 */
	public static function get_widgets() {
		return array(
			'interactive_map' => array(
				'title'       => __( 'Interactive Map', 'orbitkit-addons-for-elementor' ),
				'description' => __( 'Maps with markers, heatmap, or selectable regions.', 'orbitkit-addons-for-elementor' ),
				'category'    => 'media',
				'icon'        => 'dashicons-location-alt',
				'badge'       => 'popular',
				'class'       => 'OrbitKit\\Elementor\\Widgets\\Widget_Interactive_Map',
				'style'       => 'orbitkit-widget-interactive-map',
				'script'      => 'orbitkit-widget-interactive-map',
				'style_file'  => 'assets/css/widget-interactive-map.css',
				'script_file' => 'assets/js/widget-interactive-map.js',
				'style_deps'  => array( 'leaflet' ),
				'script_deps' => array( 'jquery', 'leaflet', 'leaflet-heat' ),
				'file'        => 'widgets/class-widget-interactive-map.php',
			),
			'pricing_table'   => array(
				'title'       => __( 'Pricing Table', 'orbitkit-addons-for-elementor' ),
				'description' => __( 'Multi-column pricing plans.', 'orbitkit-addons-for-elementor' ),
				'category'    => 'content',
				'icon'        => 'dashicons-cart',
				'badge'       => '',
				'class'       => 'OrbitKit\\Elementor\\Widgets\\Widget_Pricing_Table',
				'style'       => 'orbitkit-widget-pricing-table',
				'script'      => '',
				'style_file'  => 'assets/css/widget-pricing-table.css',
				'script_file' => '',
				'style_deps'  => array(),
				'script_deps' => array(),
				'file'        => 'widgets/class-widget-pricing-table.php',
			),
			'team_member'     => array(
				'title'       => __( 'Team Member', 'orbitkit-addons-for-elementor' ),
				'description' => __( 'Team profile card with photo and links.', 'orbitkit-addons-for-elementor' ),
				'category'    => 'content',
				'icon'        => 'dashicons-groups',
				'badge'       => 'popular',
				'class'       => 'OrbitKit\\Elementor\\Widgets\\Widget_Team_Member',
				'style'       => 'orbitkit-widget-team-member',
				'script'      => '',
				'style_file'  => 'assets/css/widget-team-member.css',
				'script_file' => '',
				'style_deps'  => array(),
				'script_deps' => array(),
				'file'        => 'widgets/class-widget-team-member.php',
			),
			'countdown'       => array(
				'title'       => __( 'Countdown', 'orbitkit-addons-for-elementor' ),
				'description' => __( 'Event countdown timer.', 'orbitkit-addons-for-elementor' ),
				'category'    => 'content',
				'icon'        => 'dashicons-clock',
				'badge'       => '',
				'class'       => 'OrbitKit\\Elementor\\Widgets\\Widget_Countdown',
				'style'       => 'orbitkit-widget-countdown',
				'script'      => 'orbitkit-widget-countdown',
				'style_file'  => 'assets/css/widget-countdown.css',
				'script_file' => 'assets/js/widget-countdown.js',
				'style_deps'  => array(),
				'script_deps' => array( 'jquery' ),
				'file'        => 'widgets/class-widget-countdown.php',
			),
			'image_compare'   => array(
				'title'       => __( 'Image Compare', 'orbitkit-addons-for-elementor' ),
				'description' => __( 'Before and after image slider with horizontal or vertical handle.', 'orbitkit-addons-for-elementor' ),
				'category'    => 'media',
				'icon'        => 'dashicons-images-alt2',
				'badge'       => 'new',
				'class'       => 'OrbitKit\\Elementor\\Widgets\\Widget_Image_Compare',
				'style'       => 'orbitkit-widget-image-compare',
				'script'      => 'orbitkit-widget-image-compare',
				'style_file'  => 'assets/css/widget-image-compare.css',
				'script_file' => 'assets/js/widget-image-compare.js',
				'style_deps'  => array(),
				'script_deps' => array( 'jquery' ),
				'file'        => 'widgets/class-widget-image-compare.php',
			),
			'image_stack'     => array(
				'title'       => __( 'Image Stack Group', 'orbitkit-addons-for-elementor' ),
				'description' => __( 'Overlapping image stack with shapes, tooltips, and spread on hover.', 'orbitkit-addons-for-elementor' ),
				'category'    => 'media',
				'icon'        => 'dashicons-format-gallery',
				'badge'       => 'new',
				'class'       => 'OrbitKit\\Elementor\\Widgets\\Widget_Image_Stack',
				'style'       => 'orbitkit-widget-image-stack',
				'script'      => 'orbitkit-widget-image-stack',
				'style_file'  => 'assets/css/widget-image-stack.css',
				'script_file' => 'assets/js/widget-image-stack.js',
				'style_deps'  => array(),
				'script_deps' => array( 'jquery' ),
				'file'        => 'widgets/class-widget-image-stack.php',
			),
		);
	}

	/**
	 * @param string $slug Widget slug.
	 * @return array<string, mixed>|null
	 */
	public static function get_widget( $slug ) {
		$widgets = self::get_widgets();
		return isset( $widgets[ $slug ] ) ? $widgets[ $slug ] : null;
	}

	/**
	 * @param string $slug Widget slug.
	 * @return array<int, string>
	 */
	public static function get_style_depends( $slug ) {
		if ( ! OrbitKit_Elementor_Settings::is_widget_enabled( $slug ) ) {
			return array();
		}

		$widget = self::get_widget( $slug );
		if ( ! $widget || empty( $widget['style'] ) ) {
			return array();
		}

		$deps = isset( $widget['style_deps'] ) ? $widget['style_deps'] : array();
		return array_merge( $deps, array( $widget['style'] ) );
	}

	/**
	 * @param string $slug Widget slug.
	 * @return array<int, string>
	 */
	public static function get_script_depends( $slug ) {
		if ( ! OrbitKit_Elementor_Settings::is_widget_enabled( $slug ) ) {
			return array();
		}

		$widget = self::get_widget( $slug );
		if ( ! $widget || empty( $widget['script'] ) ) {
			return array();
		}

		$deps = isset( $widget['script_deps'] ) ? $widget['script_deps'] : array();
		return array_merge( $deps, array( $widget['script'] ) );
	}

	/**
	 * Widget categories for the admin Elements screen.
	 *
	 * @return array<string, string>
	 */
	public static function get_categories() {
		return array(
			'all'     => __( 'All Widgets', 'orbitkit-addons-for-elementor' ),
			'content' => __( 'Content', 'orbitkit-addons-for-elementor' ),
			'media'   => __( 'Media', 'orbitkit-addons-for-elementor' ),
		);
	}
}
