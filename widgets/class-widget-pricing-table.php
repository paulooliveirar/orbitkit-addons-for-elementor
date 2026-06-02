<?php
/**
 * Pricing Table Elementor widget.
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use OrbitKit\Elementor\Includes\Traits\Widget_Base as OrbitKit_Widget_Base;
use OrbitKit\Elementor\Includes\Traits\Widget_Style_Controls;

/**
 * Multi-column pricing table.
 */
class Widget_Pricing_Table extends Widget_Base {

	use OrbitKit_Widget_Base;
	use Widget_Style_Controls;

	/**
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'pricing_table';
	}

	public function get_name() {
		return 'orbitkit_pricing_table';
	}

	public function get_title() {
		return esc_html__( 'Pricing Table', 'orbitkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_categories() {
		return array( 'orbitkit' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Plans', 'orbitkit-addons-for-elementor' ),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control( 'plan_name', array(
			'label'   => esc_html__( 'Plan name', 'orbitkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'Basic', 'orbitkit-addons-for-elementor' ),
		) );
		$repeater->add_control( 'plan_price', array(
			'label'   => esc_html__( 'Price', 'orbitkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'R$ 49',
		) );
		$repeater->add_control( 'plan_period', array(
			'label'   => esc_html__( 'Period', 'orbitkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '/mês',
		) );
		$repeater->add_control( 'plan_features', array(
			'label'       => esc_html__( 'Features (one per line)', 'orbitkit-addons-for-elementor' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => "Recurso 1\nRecurso 2\nRecurso 3",
			'rows'        => 5,
		) );
		$repeater->add_control( 'plan_button_text', array(
			'label'   => esc_html__( 'Button text', 'orbitkit-addons-for-elementor' ),
			'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Subscribe', 'orbitkit-addons-for-elementor' ),
		) );
		$repeater->add_control( 'plan_button_url', array(
			'label' => esc_html__( 'Button URL', 'orbitkit-addons-for-elementor' ),
			'type'  => Controls_Manager::URL,
		) );
		$repeater->add_control( 'plan_featured', array(
			'label'        => esc_html__( 'Featured', 'orbitkit-addons-for-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
		) );

		$this->add_control(
			'plans',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'plan_name' => 'Starter', 'plan_price' => 'R$ 29' ),
					array( 'plan_name' => 'Pro', 'plan_price' => 'R$ 79', 'plan_featured' => 'yes' ),
					array( 'plan_name' => 'Enterprise', 'plan_price' => 'R$ 199' ),
				),
				'title_field' => '{{{ plan_name }}}',
			)
		);

		$this->end_controls_section();

		$this->register_box_style_controls(
			'style_pricing_table',
			esc_html__( 'Table layout', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-pricing-table'
		);

		$this->register_box_style_controls(
			'style_pricing_card',
			esc_html__( 'Plan card', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-pricing-card'
		);

		$this->register_box_style_controls(
			'style_pricing_featured',
			esc_html__( 'Featured plan', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-pricing-card.is-featured'
		);

		$this->register_typography_style_controls(
			'style_pricing_title',
			esc_html__( 'Plan title', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-pricing-name'
		);

		$this->register_typography_style_controls(
			'style_pricing_price',
			esc_html__( 'Price', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-pricing-amount'
		);

		$this->register_typography_style_controls(
			'style_pricing_period',
			esc_html__( 'Period', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-pricing-period'
		);

		$this->register_typography_style_controls(
			'style_pricing_features',
			esc_html__( 'Features list', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-pricing-features li'
		);

		$this->register_button_style_controls(
			'style_pricing_button',
			esc_html__( 'Button', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-pricing-button'
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="orbitkit-pricing-table">
			<?php
			if ( ! empty( $settings['plans'] ) ) :
				foreach ( $settings['plans'] as $plan ) :
					$featured = ( isset( $plan['plan_featured'] ) && 'yes' === $plan['plan_featured'] );
					$url      = ! empty( $plan['plan_button_url']['url'] ) ? $plan['plan_button_url']['url'] : '#';
					$target   = ! empty( $plan['plan_button_url']['is_external'] ) ? ' target="_blank"' : '';
					$nofollow = ! empty( $plan['plan_button_url']['nofollow'] ) ? ' rel="nofollow"' : '';
					$features = ! empty( $plan['plan_features'] ) ? preg_split( '/\r\n|\r|\n/', $plan['plan_features'] ) : array();
					?>
					<div class="orbitkit-pricing-card<?php echo $featured ? ' is-featured' : ''; ?>">
						<h3 class="orbitkit-pricing-name"><?php echo esc_html( $plan['plan_name'] ); ?></h3>
						<div class="orbitkit-pricing-price">
							<span class="orbitkit-pricing-amount"><?php echo esc_html( $plan['plan_price'] ); ?></span>
							<?php if ( ! empty( $plan['plan_period'] ) ) : ?>
								<span class="orbitkit-pricing-period"><?php echo esc_html( $plan['plan_period'] ); ?></span>
							<?php endif; ?>
						</div>
						<?php if ( $features ) : ?>
							<ul class="orbitkit-pricing-features">
								<?php foreach ( $features as $feature ) : ?>
									<?php if ( '' !== trim( $feature ) ) : ?>
										<li><?php echo esc_html( trim( $feature ) ); ?></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<a class="orbitkit-pricing-button" href="<?php echo esc_url( $url ); ?>"<?php echo $target . $nofollow; // phpcs:ignore ?>>
							<?php echo esc_html( $plan['plan_button_text'] ); ?>
						</a>
					</div>
					<?php
				endforeach;
			endif;
			?>
		</div>
		<?php
	}
}
