<?php
/**
 * Reusable Elementor style controls for OrbitKit widgets.
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Includes\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

/**
 * Style control helpers for widget classes.
 */
trait Widget_Style_Controls {

	/**
	 * Box model styles (background, border, radius, shadow, padding).
	 *
	 * @param string $section_id Section ID.
	 * @param string $label      Section label.
	 * @param string $selector   CSS selector (without wrapper).
	 */
	protected function register_box_style_controls( $section_id, $label, $selector ) {
		$this->start_controls_section(
			$section_id,
			array(
				'label' => $label,
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => $section_id . '_background',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => $section_id . '_border',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->add_responsive_control(
			$section_id . '_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $section_id . '_shadow',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->add_responsive_control(
			$section_id . '_padding',
			array(
				'label'      => esc_html__( 'Padding', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Typography and text color.
	 *
	 * @param string $section_id Section ID.
	 * @param string $label      Section label.
	 * @param string $selector   CSS selector.
	 */
	protected function register_typography_style_controls( $section_id, $label, $selector ) {
		$this->start_controls_section(
			$section_id,
			array(
				'label' => $label,
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $section_id . '_typography',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->add_control(
			$section_id . '_color',
			array(
				'label'     => esc_html__( 'Color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Button / CTA styles with hover tab.
	 *
	 * @param string $section_id Section ID.
	 * @param string $label      Section label.
	 * @param string $selector   CSS selector.
	 */
	protected function register_button_style_controls( $section_id, $label, $selector ) {
		$this->start_controls_section(
			$section_id,
			array(
				'label' => $label,
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => $section_id . '_typography',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->start_controls_tabs( $section_id . '_tabs' );

		$this->start_controls_tab(
			$section_id . '_tab_normal',
			array( 'label' => esc_html__( 'Normal', 'orbitkit-addons-for-elementor' ) )
		);

		$this->add_control(
			$section_id . '_color',
			array(
				'label'     => esc_html__( 'Text color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			$section_id . '_bg',
			array(
				'label'     => esc_html__( 'Background', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$section_id . '_tab_hover',
			array( 'label' => esc_html__( 'Hover', 'orbitkit-addons-for-elementor' ) )
		);

		$this->add_control(
			$section_id . '_color_hover',
			array(
				'label'     => esc_html__( 'Text color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector . ':hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			$section_id . '_bg_hover',
			array(
				'label'     => esc_html__( 'Background', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector . ':hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => $section_id . '_border',
				'selector'  => '{{WRAPPER}} ' . $selector,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			$section_id . '_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$section_id . '_padding',
			array(
				'label'      => esc_html__( 'Padding', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Marker popup styles (markers display mode).
	 */
	protected function register_marker_popup_style_controls() {
		$popup_sel    = '.orbitkit-marker-popup';
		$title_sel    = '.orbitkit-marker-popup__title';
		$desc_sel     = '.orbitkit-marker-popup__description';
		$wrapper_sel  = '.leaflet-popup-content-wrapper';
		$tip_sel      = '.leaflet-popup-tip';

		$this->start_controls_section(
			'style_marker_popup',
			array(
				'label'     => esc_html__( 'Marker popup', 'orbitkit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display_mode' => 'markers' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'style_marker_popup_background',
				'selector' => '{{WRAPPER}} ' . $popup_sel,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'style_marker_popup_border',
				'selector' => '{{WRAPPER}} ' . $popup_sel,
			)
		);

		$this->add_responsive_control(
			'style_marker_popup_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $popup_sel => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'style_marker_popup_shadow',
				'selector' => '{{WRAPPER}} ' . $popup_sel,
			)
		);

		$this->add_responsive_control(
			'style_marker_popup_padding',
			array(
				'label'      => esc_html__( 'Padding', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $popup_sel => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'style_marker_popup_tip_color',
			array(
				'label'     => esc_html__( 'Popup arrow color (Leaflet)', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $tip_sel => 'background: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'style_marker_leaflet_reset_heading',
			array(
				'label'     => esc_html__( 'Leaflet popup shell', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'style_marker_leaflet_wrapper_bg',
			array(
				'label'     => esc_html__( 'Wrapper background', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => array(
					'{{WRAPPER}} ' . $wrapper_sel => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_marker_title',
			array(
				'label'     => esc_html__( 'Marker title', 'orbitkit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display_mode' => 'markers' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'style_marker_title_typography',
				'selector' => '{{WRAPPER}} ' . $title_sel,
			)
		);

		$this->add_control(
			'style_marker_title_color',
			array(
				'label'     => esc_html__( 'Color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $title_sel => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_marker_description',
			array(
				'label'     => esc_html__( 'Marker description', 'orbitkit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display_mode' => 'markers' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'style_marker_description_typography',
				'selector' => '{{WRAPPER}} ' . $desc_sel,
			)
		);

		$this->add_control(
			'style_marker_description_color',
			array(
				'label'     => esc_html__( 'Color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $desc_sel => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'style_marker_description_spacing',
			array(
				'label'      => esc_html__( 'Spacing from title', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $desc_sel => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Heatmap gradient and blur (heatmap display mode).
	 */
	protected function register_heatmap_style_controls() {
		$this->start_controls_section(
			'style_heatmap',
			array(
				'label'     => esc_html__( 'Heatmap', 'orbitkit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display_mode' => 'heatmap' ),
			)
		);

		$this->add_control(
			'heat_color_low',
			array(
				'label'   => esc_html__( 'Low intensity color', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#2b83ba',
			)
		);

		$this->add_control(
			'heat_color_mid',
			array(
				'label'   => esc_html__( 'Mid intensity color', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#abdda4',
			)
		);

		$this->add_control(
			'heat_color_high',
			array(
				'label'   => esc_html__( 'High intensity color', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#d7191c',
			)
		);

		$this->add_control(
			'heat_blur',
			array(
				'label'      => esc_html__( 'Blur', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 40,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Region hover tooltip (regions display mode).
	 */
	protected function register_region_tooltip_style_controls() {
		$tooltip_card_sel = '.orbitkit-region-tooltip';
		$title_sel   = '.orbitkit-region-tooltip__title';
		$desc_sel    = '.orbitkit-region-tooltip__description';

		$this->start_controls_section(
			'style_region_tooltip',
			array(
				'label'     => esc_html__( 'Region tooltip', 'orbitkit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display_mode' => 'regions' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'style_region_tooltip_background',
				'selector' => '{{WRAPPER}} ' . $tooltip_card_sel,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'style_region_tooltip_border',
				'selector' => '{{WRAPPER}} ' . $tooltip_card_sel,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'style_region_tooltip_shadow',
				'selector' => '{{WRAPPER}} ' . $tooltip_card_sel,
			)
		);

		$this->add_responsive_control(
			'style_region_tooltip_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $tooltip_card_sel => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'style_region_tooltip_padding',
			array(
				'label'      => esc_html__( 'Padding', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $tooltip_card_sel => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'style_region_tooltip_caret_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'The pointer uses the same fill as the card background so there is no gap between the tooltip and the arrow.', 'orbitkit-addons-for-elementor' ),
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_region_tooltip_title',
			array(
				'label'     => esc_html__( 'Region label', 'orbitkit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display_mode' => 'regions' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'style_region_tooltip_title_typography',
				'selector' => '{{WRAPPER}} ' . $title_sel,
			)
		);

		$this->add_control(
			'style_region_tooltip_title_color',
			array(
				'label'     => esc_html__( 'Color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $title_sel => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_region_tooltip_description',
			array(
				'label'     => esc_html__( 'Region description', 'orbitkit-addons-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'display_mode' => 'regions' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'style_region_tooltip_description_typography',
				'selector' => '{{WRAPPER}} ' . $desc_sel,
			)
		);

		$this->add_control(
			'style_region_tooltip_description_color',
			array(
				'label'     => esc_html__( 'Color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $desc_sel => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'style_region_tooltip_description_spacing',
			array(
				'label'      => esc_html__( 'Spacing from label', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 24,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $desc_sel => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
}
