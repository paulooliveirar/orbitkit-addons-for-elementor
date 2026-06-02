<?php
/**
 * Countdown Elementor widget.
 *
 * @package RocketKit\Elementor
 */

namespace RocketKit\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use RocketKit\Elementor\Includes\Traits\Widget_Base as RocketKit_Widget_Base;
use RocketKit\Elementor\Includes\Traits\Widget_Style_Controls;

/**
 * Event countdown timer.
 */
class Widget_Countdown extends Widget_Base {

	use RocketKit_Widget_Base;
	use Widget_Style_Controls;

	/**
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'countdown';
	}

	public function get_name() {
		return 'rocketkit_countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'rocketkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

	public function get_categories() {
		return array( 'rocketkit' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Timer', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'   => esc_html__( 'Due date', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::DATE_TIME,
				'default' => gmdate( 'Y-m-d H:i', strtotime( '+30 days' ) ),
			)
		);

		$this->add_control(
			'label_days',
			array(
				'label'   => esc_html__( 'Days label', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Days', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'label_hours',
			array(
				'label'   => esc_html__( 'Hours label', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hours', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'label_minutes',
			array(
				'label'   => esc_html__( 'Minutes label', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Minutes', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'label_seconds',
			array(
				'label'   => esc_html__( 'Seconds label', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Seconds', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'expired_message',
			array(
				'label'   => esc_html__( 'Expired message', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Time is up!', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->register_box_style_controls(
			'style_countdown_unit',
			esc_html__( 'Time unit box', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-countdown-unit'
		);

		$this->register_typography_style_controls(
			'style_countdown_value',
			esc_html__( 'Numbers', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-countdown-value'
		);

		$this->register_typography_style_controls(
			'style_countdown_label',
			esc_html__( 'Labels', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-countdown-label'
		);

		$this->register_typography_style_controls(
			'style_countdown_expired',
			esc_html__( 'Expired message', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-countdown-expired'
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
		<div class="rocketkit-countdown" data-config="<?php echo esc_attr( wp_json_encode( $config ) ); ?>">
			<div class="rocketkit-countdown-grid">
				<div class="rocketkit-countdown-unit"><span class="rocketkit-countdown-value" data-unit="days">0</span><span class="rocketkit-countdown-label"><?php echo esc_html( $settings['label_days'] ); ?></span></div>
				<div class="rocketkit-countdown-unit"><span class="rocketkit-countdown-value" data-unit="hours">0</span><span class="rocketkit-countdown-label"><?php echo esc_html( $settings['label_hours'] ); ?></span></div>
				<div class="rocketkit-countdown-unit"><span class="rocketkit-countdown-value" data-unit="minutes">0</span><span class="rocketkit-countdown-label"><?php echo esc_html( $settings['label_minutes'] ); ?></span></div>
				<div class="rocketkit-countdown-unit"><span class="rocketkit-countdown-value" data-unit="seconds">0</span><span class="rocketkit-countdown-label"><?php echo esc_html( $settings['label_seconds'] ); ?></span></div>
			</div>
			<p class="rocketkit-countdown-expired" hidden><?php echo esc_html( $settings['expired_message'] ); ?></p>
		</div>
		<?php
	}
}
