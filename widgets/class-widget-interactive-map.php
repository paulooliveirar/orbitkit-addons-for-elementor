<?php
/**
 * Interactive Map Elementor widget.
 *
 * @package RocketKit\Elementor
 */

namespace RocketKit\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use RocketKit\Elementor\Includes\RocketKit_Sanitizer;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use RocketKit\Elementor\Includes\RocketKit_Elementor_Settings;
use RocketKit\Elementor\Includes\Traits\Widget_Base as RocketKit_Widget_Base;
use RocketKit\Elementor\Includes\Traits\Widget_Style_Controls;

/**
 * Map with markers, heatmap, or hover-highlighted regions (Leaflet/OSM or Google Maps).
 */
class Widget_Interactive_Map extends Widget_Base {

	use RocketKit_Widget_Base;
	use Widget_Style_Controls;

	/**
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'interactive_map';
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return 'rocketkit_interactive_map';
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Interactive Map', 'rocketkit-addons-for-elementor' );
	}

	/**
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * @return array<int, string>
	 */
	public function get_categories() {
		return array( 'rocketkit' );
	}

	/**
	 * @return array<int, string>
	 */
	public function get_keywords() {
		return array( 'map', 'heatmap', 'leaflet', 'google', 'region', 'rocketkit', 'rocket kit' );
	}

	/**
	 * Editor script for marker geocoding in the panel.
	 *
	 * @return array<int, string>
	 */
	public function get_editor_script_depends() {
		return array( 'rocketkit-widget-interactive-map-editor' );
	}

	/**
	 * Editor styles for panel tools.
	 *
	 * @return array<int, string>
	 */
	public function get_editor_style_depends() {
		return array(
			'rocketkit-widget-interactive-map-editor',
		);
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_map',
			array(
				'label' => esc_html__( 'Map', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'map_provider',
			array(
				'label'   => esc_html__( 'Provider', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'leaflet',
				'options' => array(
					'leaflet' => esc_html__( 'OpenStreetMap (free)', 'rocketkit-addons-for-elementor' ),
					'google'  => esc_html__( 'Google Maps', 'rocketkit-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'google_maps_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					'<p class="elementor-panel-alert elementor-panel-alert-info">%s</p>',
					sprintf(
						/* translators: %s: settings page link */
						esc_html__( 'Configure the Google Maps API key under %s → Integrations.', 'rocketkit-addons-for-elementor' ),
						'<a href="' . esc_url( admin_url( 'admin.php?page=rocketkit-elementor' ) ) . '" target="_blank">RocketKit Addons</a>'
					)
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array( 'map_provider' => 'google' ),
			)
		);

		$this->add_control(
			'display_mode',
			array(
				'label'   => esc_html__( 'Display mode', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'regions',
				'options' => array(
					'markers'  => esc_html__( 'Markers', 'rocketkit-addons-for-elementor' ),
					'heatmap'  => esc_html__( 'Heatmap', 'rocketkit-addons-for-elementor' ),
					'regions'  => esc_html__( 'Regions (hover highlight)', 'rocketkit-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'center_lat',
			array(
				'label'   => esc_html__( 'Center latitude', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => -14.235,
				'step'    => 0.0001,
			)
		);

		$this->add_control(
			'center_lng',
			array(
				'label'   => esc_html__( 'Center longitude', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => -51.9253,
				'step'    => 0.0001,
			)
		);

		$this->add_control(
			'zoom',
			array(
				'label'   => esc_html__( 'Zoom', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min'  => 1,
						'max'  => 18,
						'step' => 1,
					),
				),
				'default' => array( 'size' => 4 ),
			)
		);

		$this->add_control(
			'scroll_wheel_zoom',
			array(
				'label'        => esc_html__( 'Mouse wheel zoom', 'rocketkit-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rocketkit-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'rocketkit-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Scroll over the map to zoom in or out.', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'map_height',
			array(
				'label'   => esc_html__( 'Height (px)', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min'  => 200,
						'max'  => 900,
						'step' => 10,
					),
				),
				'default' => array( 'size' => 420 ),
			)
		);

		$this->add_control(
			'tile_style',
			array(
				'label'     => esc_html__( 'Map style (Leaflet)', 'rocketkit-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'osm',
				'options'   => array(
					'osm'   => esc_html__( 'OpenStreetMap', 'rocketkit-addons-for-elementor' ),
					'light' => esc_html__( 'Carto Light', 'rocketkit-addons-for-elementor' ),
					'dark'  => esc_html__( 'Carto Dark', 'rocketkit-addons-for-elementor' ),
				),
				'condition' => array(
					'map_provider'  => 'leaflet',
					'display_mode!' => 'heatmap',
				),
			)
		);

		$this->add_control(
			'tile_style_heatmap',
			array(
				'label'     => esc_html__( 'Map style (Leaflet)', 'rocketkit-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'light',
				'options'   => array(
					'light' => esc_html__( 'Carto Light', 'rocketkit-addons-for-elementor' ),
					'dark'  => esc_html__( 'Carto Dark', 'rocketkit-addons-for-elementor' ),
				),
				'condition' => array(
					'map_provider' => 'leaflet',
					'display_mode' => 'heatmap',
				),
			)
		);

		$this->end_controls_section();

		$marker_repeater = new Repeater();
		$marker_repeater->add_control(
			'marker_place_search',
			array(
				'label'       => esc_html__( 'Search location', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'City, address or place…', 'rocketkit-addons-for-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'After 5 characters, choose a suggestion to set coordinates automatically.', 'rocketkit-addons-for-elementor' ),
				'condition'   => array( 'map_provider' => 'google' ),
			)
		);
		$marker_repeater->add_control(
			'marker_geocode_status',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<p class="elementor-control-field-description rocketkit-marker-geocode-status" aria-live="polite"></p>',
				'content_classes' => 'elementor-descriptor',
				'condition'       => array( 'map_provider' => 'google' ),
			)
		);
		$marker_repeater->add_control(
			'marker_coords_heading_google',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => '<p class="elementor-control-field-description" style="margin:12px 0 4px;font-weight:600;">' . esc_html__( 'Coordinates (manual)', 'rocketkit-addons-for-elementor' ) . '</p>',
				'condition' => array( 'map_provider' => 'google' ),
			)
		);
		$marker_repeater->add_control(
			'marker_coords_heading_leaflet',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => '<p class="elementor-control-field-description" style="margin:0 0 4px;font-weight:600;">' . esc_html__( 'Coordinates', 'rocketkit-addons-for-elementor' ) . '</p>',
				'condition' => array( 'map_provider' => 'leaflet' ),
			)
		);
		$marker_repeater->add_control(
			'marker_lat',
			array(
				'label'   => esc_html__( 'Latitude', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => -23.5505,
				'step'    => 0.0001,
			)
		);
		$marker_repeater->add_control(
			'marker_lng',
			array(
				'label'   => esc_html__( 'Longitude', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => -46.6333,
				'step'    => 0.0001,
			)
		);
		$marker_repeater->add_control(
			'marker_title',
			array(
				'label'   => esc_html__( 'Title', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Location', 'rocketkit-addons-for-elementor' ),
			)
		);
		$marker_repeater->add_control(
			'marker_description',
			array(
				'label'       => esc_html__( 'Description', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'description' => esc_html__( 'Line breaks are shown in the map popup.', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->start_controls_section(
			'section_marker_settings',
			array(
				'label'     => esc_html__( 'Marker settings', 'rocketkit-addons-for-elementor' ),
				'condition' => array( 'display_mode' => 'markers' ),
			)
		);

		$this->add_control(
			'marker_icon_type',
			array(
				'label'   => esc_html__( 'Marker type', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Map default', 'rocketkit-addons-for-elementor' ),
					'preset'  => esc_html__( 'Preset shapes', 'rocketkit-addons-for-elementor' ),
					'custom'  => esc_html__( 'Custom icon / SVG', 'rocketkit-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'marker_color',
			array(
				'label'     => esc_html__( 'Marker color', 'rocketkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#03a84e',
				'condition' => array( 'marker_icon_type' => 'preset' ),
			)
		);

		$this->add_control(
			'marker_shape',
			array(
				'label'     => esc_html__( 'Marker shape', 'rocketkit-addons-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default' => 'pin',
				'options'   => array(
					'pin'     => esc_html__( 'Pin', 'rocketkit-addons-for-elementor' ),
					'circle'  => esc_html__( 'Circle', 'rocketkit-addons-for-elementor' ),
					'square'  => esc_html__( 'Square', 'rocketkit-addons-for-elementor' ),
					'diamond' => esc_html__( 'Diamond', 'rocketkit-addons-for-elementor' ),
				),
				'condition' => array( 'marker_icon_type' => 'preset' ),
			)
		);

		$this->add_control(
			'marker_size',
			array(
				'label'      => esc_html__( 'Marker size', 'rocketkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 20,
						'max'  => 56,
						'step' => 2,
					),
				),
				'default'    => array(
					'size' => 36,
					'unit' => 'px',
				),
				'condition'  => array( 'marker_icon_type' => 'preset' ),
			)
		);

		$this->add_control(
			'marker_custom_image',
			array(
				'label'       => esc_html__( 'Icon image', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => esc_html__( 'PNG, JPG, GIF or SVG from the media library.', 'rocketkit-addons-for-elementor' ),
				'condition'   => array( 'marker_icon_type' => 'custom' ),
			)
		);

		$this->add_control(
			'marker_custom_svg',
			array(
				'label'       => esc_html__( 'Inline SVG code', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'description' => esc_html__( 'Optional. Paste SVG markup (overrides the image above when filled).', 'rocketkit-addons-for-elementor' ),
				'condition'   => array( 'marker_icon_type' => 'custom' ),
			)
		);

		$this->add_control(
			'marker_custom_width',
			array(
				'label'      => esc_html__( 'Icon width', 'rocketkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 16,
						'max'  => 128,
						'step' => 2,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'condition'  => array( 'marker_icon_type' => 'custom' ),
			)
		);

		$this->add_control(
			'marker_custom_height',
			array(
				'label'      => esc_html__( 'Icon height', 'rocketkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 16,
						'max'  => 128,
						'step' => 2,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'condition'  => array( 'marker_icon_type' => 'custom' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_markers',
			array(
				'label'     => esc_html__( 'Markers', 'rocketkit-addons-for-elementor' ),
				'condition' => array( 'display_mode' => 'markers' ),
			)
		);

		$this->add_control(
			'markers',
			array(
				'label'       => esc_html__( 'Marker list', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $marker_repeater->get_controls(),
				'default'     => array(
					array(
						'marker_lat' => -23.5505,
						'marker_lng' => -46.6333,
						'marker_title' => 'São Paulo',
					),
				),
				'title_field' => '{{{ marker_title }}}',
			)
		);

		$this->end_controls_section();

		$heat_repeater = new Repeater();
		$heat_repeater->add_control(
			'heat_label',
			array(
				'label'       => esc_html__( 'Point name', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Point', 'rocketkit-addons-for-elementor' ),
				'label_block' => true,
			)
		);
		$heat_repeater->add_control(
			'heat_lat',
			array(
				'label'   => esc_html__( 'Latitude', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => -23.55,
				'step'    => 0.0001,
			)
		);
		$heat_repeater->add_control(
			'heat_lng',
			array(
				'label'   => esc_html__( 'Longitude', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => -46.63,
				'step'    => 0.0001,
			)
		);
		$heat_repeater->add_control(
			'heat_intensity',
			array(
				'label'   => esc_html__( 'Intensity', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default' => array( 'size' => 0.6 ),
			)
		);

		$this->start_controls_section(
			'section_heatmap',
			array(
				'label'     => esc_html__( 'Heatmap points', 'rocketkit-addons-for-elementor' ),
				'condition' => array( 'display_mode' => 'heatmap' ),
			)
		);

		$this->add_control(
			'heat_radius',
			array(
				'label'   => esc_html__( 'Point radius', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'min'  => 10,
						'max'  => 80,
						'step' => 5,
					),
				),
				'default' => array( 'size' => 25 ),
			)
		);

		$this->add_control(
			'heatmap_points',
			array(
				'label'       => esc_html__( 'Points', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $heat_repeater->get_controls(),
				'default'     => array(
					array(
						'heat_label' => 'São Paulo',
						'heat_lat'   => -23.55,
						'heat_lng'   => -46.63,
					),
					array(
						'heat_label' => 'Rio de Janeiro',
						'heat_lat'   => -22.9,
						'heat_lng'   => -43.2,
					),
					array(
						'heat_label' => 'Belo Horizonte',
						'heat_lat'   => -19.9,
						'heat_lng'   => -43.9,
					),
				),
				'title_field' => '{{{ heat_label }}}',
			)
		);

		$this->end_controls_section();

		$region_repeater = new Repeater();
		$region_repeater->add_control(
			'region_label',
			array(
				'label'       => esc_html__( 'Label', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Region', 'rocketkit-addons-for-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Shown in the list and as the region title on hover.', 'rocketkit-addons-for-elementor' ),
			)
		);
		$region_repeater->add_control(
			'region_description',
			array(
				'label'       => esc_html__( 'Description', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'description' => esc_html__( 'Shown on hover over the highlighted area. Line breaks are preserved.', 'rocketkit-addons-for-elementor' ),
			)
		);
		$region_repeater->add_control(
			'region_geojson_io',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					'<div class="rocketkit-region-geojson-io"><p class="elementor-control-field-description">%s</p><a href="https://geojson.io/" target="_blank" rel="noopener noreferrer" class="elementor-button elementor-button-default">%s</a></div>',
					esc_html__( 'Draw your area on GeoJSON.io, then copy the JSON and paste it in the field below.', 'rocketkit-addons-for-elementor' ),
					esc_html__( 'Open GeoJSON.io', 'rocketkit-addons-for-elementor' )
				),
				'content_classes' => 'elementor-descriptor',
			)
		);
		$region_repeater->add_control(
			'region_custom_geojson',
			array(
				'label'       => esc_html__( 'Area (GeoJSON)', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'description' => esc_html__( 'Paste a GeoJSON Feature, FeatureCollection, or Polygon from GeoJSON.io.', 'rocketkit-addons-for-elementor' ),
			)
		);
		$region_repeater->add_control(
			'region_color',
			array(
				'label'   => esc_html__( 'Fill color', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#3388ff',
			)
		);
		$region_repeater->add_control(
			'region_fill_opacity',
			array(
				'label'      => esc_html__( 'Fill opacity', 'rocketkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'    => array(
					'size' => 45,
					'unit' => '%',
				),
			)
		);
		$region_repeater->add_control(
			'region_hover_border_color',
			array(
				'label'   => esc_html__( 'Hover border color', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ff6b35',
			)
		);
		$region_repeater->add_control(
			'region_hover_fill',
			array(
				'label'        => esc_html__( 'Fill on hover', 'rocketkit-addons-for-elementor' ),
				'description'  => esc_html__( 'Solid fill on hover covers thin gaps between polygon borders.', 'rocketkit-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'rocketkit-addons-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'rocketkit-addons-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$region_repeater->add_control(
			'region_hover_fill_color',
			array(
				'label'     => esc_html__( 'Hover fill color', 'rocketkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff6b35',
				'condition' => array( 'region_hover_fill' => 'yes' ),
			)
		);
		$region_repeater->add_control(
			'region_hover_fill_opacity',
			array(
				'label'      => esc_html__( 'Hover fill opacity', 'rocketkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'condition'  => array( 'region_hover_fill' => 'yes' ),
			)
		);

		$this->start_controls_section(
			'section_regions',
			array(
				'label'     => esc_html__( 'Regions', 'rocketkit-addons-for-elementor' ),
				'condition' => array( 'display_mode' => 'regions' ),
			)
		);

		$this->add_control(
			'regions',
			array(
				'label'       => esc_html__( 'Region list', 'rocketkit-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $region_repeater->get_controls(),
				'default'     => array(
					array(
						'region_label'            => esc_html__( 'Region 1', 'rocketkit-addons-for-elementor' ),
						'region_description'      => '',
						'region_color'            => '#4a90d9',
						'region_fill_opacity'     => array( 'size' => 45, 'unit' => '%' ),
						'region_hover_border_color' => '#ff6b35',
						'region_hover_fill'       => 'yes',
						'region_hover_fill_color' => '#ff6b35',
						'region_hover_fill_opacity' => array( 'size' => 100, 'unit' => '%' ),
					),
				),
				'title_field' => '{{{ region_label }}}',
			)
		);

		$this->end_controls_section();

		$this->register_box_style_controls(
			'style_map_wrapper',
			esc_html__( 'Map container', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-map-widget'
		);

		$this->register_box_style_controls(
			'style_map_canvas',
			esc_html__( 'Map canvas', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-map-canvas'
		);

		$this->register_marker_popup_style_controls();
		$this->register_heatmap_style_controls();
		$this->register_region_tooltip_style_controls();
	}

	/**
	 * Normalize user or API GeoJSON into a single Feature.
	 *
	 * @param mixed $data Decoded JSON.
	 * @return array<string, mixed>|null
	 */
	private function parse_region_geojson( $data ) {
		if ( ! is_array( $data ) ) {
			return null;
		}

		if ( isset( $data['type'] ) && 'Feature' === $data['type'] && ! empty( $data['geometry'] ) ) {
			return $data;
		}

		if ( isset( $data['type'] ) && 'FeatureCollection' === $data['type'] && ! empty( $data['features'][0] ) ) {
			return $this->parse_region_geojson( $data['features'][0] );
		}

		if ( isset( $data['type'] ) && in_array( $data['type'], array( 'Polygon', 'MultiPolygon' ), true ) ) {
			return array(
				'type'       => 'Feature',
				'properties' => array(),
				'geometry'   => $data,
			);
		}

		return null;
	}

	/**
	 * Build map config for JS.
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @return array<string, mixed>
	 */
	private function build_map_config( array $settings ) {
		$zoom = isset( $settings['zoom']['size'] ) ? (int) $settings['zoom']['size'] : 4;
		$height = isset( $settings['map_height']['size'] ) ? (int) $settings['map_height']['size'] : 420;
		$heat_radius = isset( $settings['heat_radius']['size'] ) ? (int) $settings['heat_radius']['size'] : 25;

		$markers = array();
		if ( ! empty( $settings['markers'] ) && is_array( $settings['markers'] ) ) {
			foreach ( $settings['markers'] as $marker ) {
				$markers[] = array(
					'lat'         => (float) $marker['marker_lat'],
					'lng'         => (float) $marker['marker_lng'],
					'title'       => isset( $marker['marker_title'] ) ? $marker['marker_title'] : '',
					'description' => isset( $marker['marker_description'] ) ? $marker['marker_description'] : '',
				);
			}
		}

		$heatmap = array();
		if ( ! empty( $settings['heatmap_points'] ) && is_array( $settings['heatmap_points'] ) ) {
			foreach ( $settings['heatmap_points'] as $point ) {
				$intensity = isset( $point['heat_intensity']['size'] ) ? (float) $point['heat_intensity']['size'] : 0.5;
				$heatmap[] = array(
					'lat'       => (float) $point['heat_lat'],
					'lng'       => (float) $point['heat_lng'],
					'intensity' => $intensity,
					'label'     => isset( $point['heat_label'] ) ? $point['heat_label'] : '',
				);
			}
		}

		$regions = array();
		if ( ! empty( $settings['regions'] ) && is_array( $settings['regions'] ) ) {
			foreach ( $settings['regions'] as $region ) {
				$raw = isset( $region['region_custom_geojson'] ) ? trim( $region['region_custom_geojson'] ) : '';
				if ( '' === $raw ) {
					continue;
				}

				$decoded = json_decode( $raw, true );
				$feature = $this->parse_region_geojson( $decoded );
				if ( ! $feature ) {
					continue;
				}

				$fill_opacity = 0.45;
				if ( isset( $region['region_fill_opacity']['size'] ) ) {
					$fill_opacity = (float) $region['region_fill_opacity']['size'] / 100;
				}

				$hover_fill_opacity = 0.75;
				if ( isset( $region['region_hover_fill_opacity']['size'] ) ) {
					$hover_fill_opacity = (float) $region['region_hover_fill_opacity']['size'] / 100;
				}

				$hover_border = '#ff6b35';
				if ( ! empty( $region['region_hover_border_color'] ) ) {
					$hover_border = $region['region_hover_border_color'];
				} elseif ( ! empty( $region['region_hover_color'] ) ) {
					$hover_border = $region['region_hover_color'];
				}

				$hover_fill_color = ! empty( $region['region_hover_fill_color'] )
					? $region['region_hover_fill_color']
					: $hover_border;

				$regions[] = array(
					'label'              => ! empty( $region['region_label'] ) ? $region['region_label'] : esc_html__( 'Region', 'rocketkit-addons-for-elementor' ),
					'description'        => isset( $region['region_description'] ) ? $region['region_description'] : '',
					'geojson'            => $feature,
					'color'              => isset( $region['region_color'] ) ? $region['region_color'] : '#3388ff',
					'fill_opacity'       => $fill_opacity,
					'hover_border_color' => $hover_border,
					'hover_fill'         => ! isset( $region['region_hover_fill'] ) || 'yes' === $region['region_hover_fill'],
					'hover_fill_color'   => $hover_fill_color,
					'hover_fill_opacity' => $hover_fill_opacity,
				);
			}
		}

		$display_mode = isset( $settings['display_mode'] ) ? $settings['display_mode'] : 'regions';
		$tile_style   = 'osm';

		if ( 'heatmap' === $display_mode ) {
			$tile_style = ! empty( $settings['tile_style_heatmap'] ) ? $settings['tile_style_heatmap'] : 'light';
		} elseif ( ! empty( $settings['tile_style'] ) ) {
			$tile_style = $settings['tile_style'];
		}

		$heat_blur = isset( $settings['heat_blur']['size'] ) ? (int) $settings['heat_blur']['size'] : 15;

		return array(
			'provider'     => isset( $settings['map_provider'] ) ? $settings['map_provider'] : 'leaflet',
			'displayMode'  => $display_mode,
			'center'       => array(
				'lat' => (float) ( $settings['center_lat'] ?? -14.235 ),
				'lng' => (float) ( $settings['center_lng'] ?? -51.9253 ),
			),
			'zoom'            => $zoom,
			'scrollWheelZoom' => ( ! isset( $settings['scroll_wheel_zoom'] ) || 'yes' === $settings['scroll_wheel_zoom'] ),
			'height'          => $height,
			'tileStyle'       => $tile_style,
			'heatRadius'      => $heat_radius,
			'heatBlur'        => $heat_blur,
			'heatColors'      => array(
				'low'  => ! empty( $settings['heat_color_low'] ) ? $settings['heat_color_low'] : '#2b83ba',
				'mid'  => ! empty( $settings['heat_color_mid'] ) ? $settings['heat_color_mid'] : '#abdda4',
				'high' => ! empty( $settings['heat_color_high'] ) ? $settings['heat_color_high'] : '#d7191c',
			),
			'markers'      => $markers,
			'markerIcon'   => $this->build_marker_icon_config( $settings ),
			'heatmap'      => $heatmap,
			'regions'      => $regions,
		);
	}

	/**
	 * Marker icon options for the frontend map.
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @return array<string, mixed>
	 */
	private function build_marker_icon_config( array $settings ) {
		$type = ! empty( $settings['marker_icon_type'] ) ? $settings['marker_icon_type'] : 'default';

		if ( 'preset' === $type ) {
			$marker_size = isset( $settings['marker_size']['size'] ) ? (int) $settings['marker_size']['size'] : 36;

			return array(
				'type'  => 'preset',
				'color' => ! empty( $settings['marker_color'] ) ? $settings['marker_color'] : '#03a84e',
				'shape' => ! empty( $settings['marker_shape'] ) ? $settings['marker_shape'] : 'pin',
				'size'  => $marker_size,
			);
		}

		if ( 'custom' === $type ) {
			$custom_width  = isset( $settings['marker_custom_width']['size'] ) ? (int) $settings['marker_custom_width']['size'] : 40;
			$custom_height = isset( $settings['marker_custom_height']['size'] ) ? (int) $settings['marker_custom_height']['size'] : 40;
			$image_url     = '';

			if ( ! empty( $settings['marker_custom_image']['url'] ) ) {
				$image_url = $settings['marker_custom_image']['url'];
			}

			$svg_markup = isset( $settings['marker_custom_svg'] )
				? RocketKit_Sanitizer::sanitize_inline_svg( $settings['marker_custom_svg'] )
				: '';

			return array(
				'type'   => 'custom',
				'url'    => esc_url_raw( $image_url ),
				'svg'    => $svg_markup,
				'width'  => $custom_width,
				'height' => $custom_height,
			);
		}

		return array(
			'type' => 'default',
		);
	}

	/**
	 * Render widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$config   = $this->build_map_config( $settings );

		$google_api_key = RocketKit_Elementor_Settings::get_google_maps_api_key();
		if ( 'google' === $config['provider'] && '' !== $google_api_key ) {
			$key = rawurlencode( $google_api_key );
			if ( ! wp_script_is( 'google-maps-rocketkit', 'registered' ) ) {
				wp_register_script(
					'google-maps-rocketkit',
					'https://maps.googleapis.com/maps/api/js?key=' . $key . '&libraries=visualization',
					array(),
					ROCKETKIT_ELEMENTOR_VERSION,
					true
				);
			}
			wp_enqueue_script( 'google-maps-rocketkit' );
		}

		$id          = 'rocketkit-map-' . $this->get_id();
		$config_json = wp_json_encode(
			$config,
			JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
		);
		?>
		<div class="rocketkit-map-widget" id="<?php echo esc_attr( $id ); ?>">
			<div
				class="rocketkit-map-canvas"
				data-config="<?php echo esc_attr( $config_json ); ?>"
				style="height: <?php echo esc_attr( (string) $config['height'] ); ?>px;"
				role="region"
				aria-label="<?php esc_attr_e( 'Interactive map', 'rocketkit-addons-for-elementor' ); ?>"
			></div>
		</div>
		<?php
	}
}
