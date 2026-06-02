<?php
/**
 * Countdown Elementor widget.
 *
 * @package OrbitKit\Elementor
 */

namespace OrbitKit\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use OrbitKit\Elementor\Includes\Traits\Widget_Base as OrbitKit_Widget_Base;
use OrbitKit\Elementor\Includes\Traits\Widget_Style_Controls;

/**
 * Event countdown timer.
 */
class Widget_Countdown extends Widget_Base {

	use OrbitKit_Widget_Base;
	use Widget_Style_Controls;

	/**
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'countdown';
	}

	public function get_name() {
		return 'orbitkit_countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'orbitkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_categories() {
		return array( 'orbitkit' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Timer', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'   => esc_html__( 'Due date', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::DATE_TIME,
				'default' => gmdate( 'Y-m-d H:i', strtotime( '+30 days' ) ),
			)
		);

		$this->add_control(
			'label_days',
			array(
				'label'   => esc_html__( 'Days label', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Days', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'label_hours',
			array(
				'label'   => esc_html__( 'Hours label', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hours', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'label_minutes',
			array(
				'label'   => esc_html__( 'Minutes label', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Minutes', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'label_seconds',
			array(
				'label'   => esc_html__( 'Seconds label', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Seconds', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'expired_message',
			array(
				'label'   => esc_html__( 'Expired message', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Time is up!', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->register_box_style_controls(
			'style_countdown_unit',
			esc_html__( 'Time unit box', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-countdown-unit'
		);

		$this->register_typography_style_controls(
			'style_countdown_value',
			esc_html__( 'Numbers', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-countdown-value'
		);

		$this->register_typography_style_controls(
			'style_countdown_label',
			esc_html__( 'Labels', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-countdown-label'
		);

		$this->register_typography_style_controls(
			'style_countdown_expired',
			esc_html__( 'Expired message', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-countdown-expired'
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$config   = array(
			'dueDate'  => isset( $settings['due_date'] ) ? $settings['due_date'] : '',
			'labels'   => array(
				'days'    => $settings['label_days'],
				'hours'   => $settings['label_hours'],
				'minutes' => $settings['label_minutes'],
				'seconds' => $settings['label_seconds'],
			),
			'expired'  => $settings['expired_message'],
		);
		?>
		<div class="orbitkit-countdown" data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
			<div class="orbitkit-countdown-grid">
				<div class="orbitkit-countdown-unit"><span class="orbitkit-countdown-value" data-unit="days">0</span><span class="orbitkit-countdown-label"><?php echo esc_html( $settings['label_days'] ); ?></span></div>
				<div class="orbitkit-countdown-unit"><span class="orbitkit-countdown-value" data-unit="hours">0</span><span class="orbitkit-countdown-label"><?php echo esc_html( $settings['label_hours'] ); ?></span></div>
				<div class="orbitkit-countdown-unit"><span class="orbitkit-countdown-value" data-unit="minutes">0</span><span class="orbitkit-countdown-label"><?php echo esc_html( $settings['label_minutes'] ); ?></span></div>
				<div class="orbitkit-countdown-unit"><span class="orbitkit-countdown-value" data-unit="seconds">0</span><span class="orbitkit-countdown-label"><?php echo esc_html( $settings['label_seconds'] ); ?></span></div>
			</div>
			<p class="orbitkit-countdown-expired" hidden><?php echo esc_html( $settings['expired_message'] ); ?></p>
		</div>
		<?php
	}
}
