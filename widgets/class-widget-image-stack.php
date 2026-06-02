<?php
/**
 * Image Stack Group Elementor widget.
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use OrbitKit\Elementor\Includes\Traits\Widget_Base as OrbitKit_Widget_Base;
use OrbitKit\Elementor\Includes\Traits\Widget_Style_Controls;

/**
 * Overlapping image stack with optional tooltips.
 */
class Widget_Image_Stack extends Widget_Base {

	use OrbitKit_Widget_Base;
	use Widget_Style_Controls;

	/**
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'image_stack';
	}

	public function get_name() {
		return 'orbitkit_image_stack';
	}

	public function get_title() {
		return esc_html__( 'Image Stack Group', 'orbitkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( 'orbitkit' );
	}

	public function get_keywords() {
		return array( 'stack', 'images', 'group', 'avatar', 'overlap', 'orbitkit' );
	}

	protected function register_controls() {
		$repeater = new Repeater();

		$repeater->add_control(
			'stack_image',
			array(
				'label'   => esc_html__( 'Image', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'stack_tooltip',
			array(
				'label'       => esc_html__( 'Tooltip', 'orbitkit-addons-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'stack_link',
			array(
				'label' => esc_html__( 'Link', 'orbitkit-addons-for-elementor' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->start_controls_section(
			'section_stack',
			array(
				'label' => esc_html__( 'Stack', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'stack_items',
			array(
				'label'       => esc_html__( 'Images', 'orbitkit-addons-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'stack_tooltip' => esc_html__( 'Member 1', 'orbitkit-addons-for-elementor' ),
					),
					array(
						'stack_tooltip' => esc_html__( 'Member 2', 'orbitkit-addons-for-elementor' ),
					),
					array(
						'stack_tooltip' => esc_html__( 'Member 3', 'orbitkit-addons-for-elementor' ),
					),
				),
				'title_field' => '{{{ stack_tooltip || "Image" }}}',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'stack_thumb',
				'default' => 'thumbnail',
			)
		);

		$this->add_control(
			'stack_size',
			array(
				'label'   => esc_html__( 'Size', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'medium',
				'options' => array(
					'small'  => esc_html__( 'Small', 'orbitkit-addons-for-elementor' ),
					'medium' => esc_html__( 'Medium', 'orbitkit-addons-for-elementor' ),
					'large'  => esc_html__( 'Large', 'orbitkit-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'stack_shape',
			array(
				'label'   => esc_html__( 'Shape', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => array(
					'circle'  => esc_html__( 'Circle', 'orbitkit-addons-for-elementor' ),
					'rounded' => esc_html__( 'Rounded', 'orbitkit-addons-for-elementor' ),
					'square'  => esc_html__( 'Square', 'orbitkit-addons-for-elementor' ),
					'drop'    => esc_html__( 'Drop', 'orbitkit-addons-for-elementor' ),
					'corner'  => esc_html__( 'Corner', 'orbitkit-addons-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'stack_overlap',
			array(
				'label'      => esc_html__( 'Overlap', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 48,
					),
				),
				'default'    => array(
					'size' => 22,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .orbitkit-image-stack__item + .orbitkit-image-stack__item' => 'margin-left: calc(-1 * {{SIZE}}{{UNIT}});',
				),
			)
		);

		$this->add_control(
			'spread_on_hover',
			array(
				'label'        => esc_html__( 'Spread on hover', 'orbitkit-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_overflow_count',
			array(
				'label'        => esc_html__( 'Show +N badge', 'orbitkit-addons-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Shows a +N badge for images beyond the max visible limit.', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'max_visible',
			array(
				'label'       => esc_html__( 'Max visible images', 'orbitkit-addons-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 12,
				'step'        => 1,
				'default'     => 0,
				'description' => esc_html__( '0 shows all images. Use with +N badge to display remaining count.', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->register_box_style_controls(
			'style_stack_wrapper',
			esc_html__( 'Wrapper', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-image-stack'
		);

		$this->start_controls_section(
			'style_stack_item',
			array(
				'label' => esc_html__( 'Stack item', 'orbitkit-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_border_color',
			array(
				'label'     => esc_html__( 'Border color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .orbitkit-image-stack__thumb' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .orbitkit-image-stack__overflow' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'item_border_width',
			array(
				'label'      => esc_html__( 'Border width', 'orbitkit-addons-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'size' => 3,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .orbitkit-image-stack__thumb' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .orbitkit-image-stack__overflow' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_shadow',
				'selector' => '{{WRAPPER}} .orbitkit-image-stack__thumb, {{WRAPPER}} .orbitkit-image-stack__overflow',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_stack_tooltip',
			array(
				'label' => esc_html__( 'Tooltip', 'orbitkit-addons-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tooltip_bg',
			array(
				'label'     => esc_html__( 'Background', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e293b',
				'selectors' => array(
					'{{WRAPPER}} .orbitkit-image-stack__tooltip' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .orbitkit-image-stack__tooltip::after' => 'border-top-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_color',
			array(
				'label'     => esc_html__( 'Text color', 'orbitkit-addons-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .orbitkit-image-stack__tooltip' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * @param array<string, mixed> $item     Repeater item.
	 * @param array<string, mixed> $settings Widget settings.
	 * @return string
	 */
	private function get_item_image_html( $item, $settings ) {
		if ( empty( $item['stack_image']['id'] ) && empty( $item['stack_image']['url'] ) ) {
			return '';
		}

		$image_settings                = $settings;
		$image_settings['stack_image'] = $item['stack_image'];

		return Group_Control_Image_Size::get_attachment_image_html( $image_settings, 'stack_thumb', 'stack_image' );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = isset( $settings['stack_items'] ) && is_array( $settings['stack_items'] ) ? $settings['stack_items'] : array();

		if ( empty( $items ) ) {
			return;
		}

		$size          = isset( $settings['stack_size'] ) ? $settings['stack_size'] : 'medium';
		$shape         = isset( $settings['stack_shape'] ) ? $settings['stack_shape'] : 'circle';
		$spread        = ! isset( $settings['spread_on_hover'] ) || 'yes' === $settings['spread_on_hover'];
		$show_overflow = ! empty( $settings['show_overflow_count'] ) && 'yes' === $settings['show_overflow_count'];
		$max_visible   = ! empty( $settings['max_visible'] ) ? max( 0, (int) $settings['max_visible'] ) : 0;

		$total   = count( $items );
		$visible = $max_visible > 0 ? min( $max_visible, $total ) : $total;
		$hidden  = $max_visible > 0 && $total > $max_visible ? $total - $max_visible : 0;

		$classes = array(
			'orbitkit-image-stack',
			'orbitkit-image-stack--size-' . esc_attr( $size ),
			'orbitkit-image-stack--shape-' . esc_attr( $shape ),
		);

		if ( $spread ) {
			$classes[] = 'orbitkit-image-stack--spread-hover';
		}

		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" role="group">
			<div class="orbitkit-image-stack__track">
				<?php
				$index = 0;
				foreach ( $items as $item ) {
					if ( $index >= $visible ) {
						break;
					}

					$img_html = $this->get_item_image_html( $item, $settings );
					if ( '' === $img_html ) {
						continue;
					}

					$tooltip = isset( $item['stack_tooltip'] ) ? $item['stack_tooltip'] : '';
					$link    = isset( $item['stack_link']['url'] ) ? $item['stack_link']['url'] : '';

					$item_classes = 'orbitkit-image-stack__item';
					$item_classes .= ' orbitkit-image-stack__item--index-' . (int) $index;
					?>
					<div class="<?php echo esc_attr( $item_classes ); ?>" style="--rk-stack-index: <?php echo (int) $index; ?>;">
						<?php if ( $link ) : ?>
							<?php
							$this->add_link_attributes( 'stack_link_' . $index, $item['stack_link'] );
							$this->add_render_attribute( 'stack_link_' . $index, 'class', 'orbitkit-image-stack__link' );
							?>
							<a <?php $this->print_render_attribute_string( 'stack_link_' . $index ); ?>>
						<?php endif; ?>
							<span class="orbitkit-image-stack__thumb">
								<?php echo $img_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
							<?php if ( $tooltip ) : ?>
								<span class="orbitkit-image-stack__tooltip" role="tooltip"><?php echo esc_html( $tooltip ); ?></span>
							<?php endif; ?>
						<?php if ( $link ) : ?>
							</a>
						<?php endif; ?>
					</div>
					<?php
					++$index;
				}

				if ( $show_overflow && $hidden > 0 ) :
					?>
					<div class="orbitkit-image-stack__item orbitkit-image-stack__item--overflow" style="--rk-stack-index: <?php echo (int) $visible; ?>;">
						<span class="orbitkit-image-stack__overflow" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: extra image count */ esc_html__( '%d more images', 'orbitkit-addons-for-elementor' ), $hidden ) ); ?>">+<?php echo (int) $hidden; ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
