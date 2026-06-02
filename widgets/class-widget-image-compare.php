<?php
/**
 * Image Compare Elementor widget.
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;
use OrbitKit\Elementor\Includes\Traits\Widget_Base as OrbitKit_Widget_Base;
use OrbitKit\Elementor\Includes\Traits\Widget_Style_Controls;

/**
 * Before / after image comparison slider.
 */
class Widget_Image_Compare extends Widget_Base {

	use OrbitKit_Widget_Base;
	use Widget_Style_Controls;

	/**
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'image_compare';
	}

	public function get_name() {
		return 'orbitkit_image_compare';
	}

	public function get_title() {
		return esc_html__( 'Image Compare', 'orbitkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-exchange';
	}

	public function get_categories() {
		return array( 'orbitkit' );
	}

	public function get_keywords() {
		return array( 'before', 'after', 'compare', 'slider', 'image', 'orbitkit' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_images',
			array(
				'label' => esc_html__( 'Images', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'image_before',
			array(
				'label'   => esc_html__( 'Before image', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_control(
			'image_after',
			array(
				'label'   => esc_html__( 'After image', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'default' => 'large',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_compare',
			array(
				'label' => esc_html__( 'Compare', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'orientation',
			array(
				'label'   => esc_html__( 'Orientation', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'orbitkit-addons-for-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'orbitkit-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'start_position',
			array(
				'label'      => esc_html__( 'Starting position', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 50,
					'unit' => '%',
				),
			)
		);

		$this->add_control(
			'handler_style',
			array(
				'label'   => esc_html__( 'Handler style', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Circle', 'orbitkit-addons-for-elementor' ),
					'thin'    => esc_html__( 'Thin line', 'orbitkit-addons-for-elementor' ),
					'capsule' => esc_html__( 'Capsule', 'orbitkit-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'move_on_hover',
			array(
				'label'        => esc_html__( 'Move on hover', 'orbitkit-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'        => esc_html__( 'Show labels', 'orbitkit-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_before',
			array(
				'label'     => esc_html__( 'Before label', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Before', 'orbitkit-addons-for-elementor' ),
				'condition' => array( 'show_labels' => 'yes' ),
			)
		);

		$this->add_control(
			'label_after',
			array(
				'label'     => esc_html__( 'After label', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'After', 'orbitkit-addons-for-elementor' ),
				'condition' => array( 'show_labels' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->register_box_style_controls(
			'style_compare_wrapper',
			esc_html__( 'Wrapper', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-image-compare'
		);

		$this->start_controls_section(
			'style_compare_handler',
			array(
				'label' => esc_html__( 'Handler', 'orbitkit-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'handler_color',
			array(
				'label'     => esc_html__( 'Color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .orbitkit-image-compare__handle' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}} .orbitkit-image-compare--handler-thin .orbitkit-image-compare__handle-line' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .orbitkit-image-compare__handle-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'handler_accent',
			array(
				'label'     => esc_html__( 'Accent / icon', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#03a84e',
				'selectors' => array(
					'{{WRAPPER}} .orbitkit-image-compare--handler-default .orbitkit-image-compare__handle' => 'color: {{VALUE}};',
					'{{WRAPPER}} .orbitkit-image-compare--handler-capsule .orbitkit-image-compare__handle' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->register_typography_style_controls(
			'style_compare_labels',
			esc_html__( 'Labels', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-image-compare__label'
		);

		$this->start_controls_section(
			'style_compare_label_box',
			array(
				'label' => esc_html__( 'Label box', 'orbitkit-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_bg',
			array(
				'label'     => esc_html__( 'Background', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.55)',
				'selectors' => array(
					'{{WRAPPER}} .orbitkit-image-compare__label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .orbitkit-image-compare__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * @param array<string, mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_image_html( $settings, $key ) {
		if ( empty( $settings[ $key ]['id'] ) && empty( $settings[ $key ]['url'] ) ) {
			return '';
		}

		$image_settings           = $settings;
		$image_settings['image']  = $settings[ $key ];

		return Group_Control_Image_Size::get_attachment_image_html( $image_settings, 'image', 'image' );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$before_html = $this->get_image_html( $settings, 'image_before' );
		$after_html  = $this->get_image_html( $settings, 'image_after' );

		if ( '' === $before_html && '' === $after_html ) {
			return;
		}

		$orientation = isset( $settings['orientation'] ) ? $settings['orientation'] : 'horizontal';
		$start       = isset( $settings['start_position']['size'] ) ? (float) $settings['start_position']['size'] : 50;
		$handler     = isset( $settings['handler_style'] ) ? $settings['handler_style'] : 'default';
		$move_hover  = ! empty( $settings['move_on_hover'] ) && 'yes' === $settings['move_on_hover'];
		$show_labels = ! isset( $settings['show_labels'] ) || 'yes' === $settings['show_labels'];

		$config = array(
			'orientation' => $orientation,
			'start'       => $start,
			'moveHover'   => $move_hover,
		);

		$classes = array(
			'orbitkit-image-compare',
			'orbitkit-image-compare--' . esc_attr( $orientation ),
			'orbitkit-image-compare--handler-' . esc_attr( $handler ),
		);

		if ( $move_hover ) {
			$classes[] = 'orbitkit-image-compare--move-hover';
		}

		?>
		<div
			class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>"
			style="--rk-compare-pos: <?php echo esc_attr( $start ); ?>%;"
		>
			<div class="orbitkit-image-compare__stage" tabindex="0" role="slider" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr( (string) $start ); ?>" aria-label="<?php esc_attr_e( 'Image comparison slider', 'orbitkit-addons-for-elementor' ); ?>">
				<div class="orbitkit-image-compare__layer orbitkit-image-compare__layer--after">
					<?php echo $after_html ? $after_html : $before_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="orbitkit-image-compare__layer orbitkit-image-compare__layer--before">
					<?php echo $before_html ? $before_html : $after_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="orbitkit-image-compare__handle" aria-hidden="true">
					<span class="orbitkit-image-compare__handle-line"></span>
					<span class="orbitkit-image-compare__handle-knob">
						<span class="orbitkit-image-compare__handle-icon" aria-hidden="true">&#8644;</span>
					</span>
				</div>
			</div>
			<?php if ( $show_labels ) : ?>
				<?php if ( ! empty( $settings['label_before'] ) ) : ?>
					<span class="orbitkit-image-compare__label orbitkit-image-compare__label--before"><?php echo esc_html( $settings['label_before'] ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $settings['label_after'] ) ) : ?>
					<span class="orbitkit-image-compare__label orbitkit-image-compare__label--after"><?php echo esc_html( $settings['label_after'] ); ?></span>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}
}
