<?php
/**
 * Team Member Elementor widget.
 *
 * @package RocketKit\Elementor
 */

namespace RocketKit\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;
use RocketKit\Elementor\Includes\Traits\Widget_Base as RocketKit_Widget_Base;
use RocketKit\Elementor\Includes\Traits\Widget_Style_Controls;

/**
 * Team member card with photo, role, and social links.
 */
class Widget_Team_Member extends Widget_Base {

	use RocketKit_Widget_Base;
	use Widget_Style_Controls;

	/**
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'team_member';
	}

	public function get_name() {
		return 'rocketkit_team_member';
	}

	public function get_title() {
		return esc_html__( 'Team Member', 'rocketkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return array( 'rocketkit' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Member', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Photo', 'rocketkit-addons-for-elementor' ),
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
				'default' => 'medium',
			)
		);

		$this->add_control(
			'name',
			array(
				'label'   => esc_html__( 'Name', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Alex Morgan',
			)
		);

		$this->add_control(
			'role',
			array(
				'label'   => esc_html__( 'Role', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Developer', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'bio',
			array(
				'label'   => esc_html__( 'Bio', 'rocketkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'RocketKit team member.', 'rocketkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'email',
			array(
				'label' => esc_html__( 'Email', 'rocketkit-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'linkedin',
			array(
				'label' => esc_html__( 'LinkedIn URL', 'rocketkit-addons-for-elementor' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->end_controls_section();

		$this->register_box_style_controls(
			'style_team_card',
			esc_html__( 'Card', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-team-member'
		);

		$this->register_box_style_controls(
			'style_team_photo',
			esc_html__( 'Photo', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-team-photo img'
		);

		$this->register_typography_style_controls(
			'style_team_name',
			esc_html__( 'Name', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-team-name'
		);

		$this->register_typography_style_controls(
			'style_team_role',
			esc_html__( 'Role', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-team-role'
		);

		$this->register_typography_style_controls(
			'style_team_bio',
			esc_html__( 'Bio', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-team-bio'
		);

		$this->register_typography_style_controls(
			'style_team_links',
			esc_html__( 'Links', 'rocketkit-addons-for-elementor' ),
			'.rocketkit-team-links a'
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' );
		?>
		<div class="rocketkit-team-member">
			<?php if ( $image_html ) : ?>
				<div class="rocketkit-team-photo"><?php echo $image_html; // phpcs:ignore ?></div>
			<?php endif; ?>
			<h3 class="rocketkit-team-name"><?php echo esc_html( $settings['name'] ); ?></h3>
			<?php if ( ! empty( $settings['role'] ) ) : ?>
				<p class="rocketkit-team-role"><?php echo esc_html( $settings['role'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $settings['bio'] ) ) : ?>
				<p class="rocketkit-team-bio"><?php echo esc_html( $settings['bio'] ); ?></p>
			<?php endif; ?>
			<div class="rocketkit-team-links">
				<?php if ( ! empty( $settings['email'] ) ) : ?>
					<a href="mailto:<?php echo esc_attr( $settings['email'] ); ?>"><?php esc_html_e( 'Email', 'rocketkit-addons-for-elementor' ); ?></a>
				<?php endif; ?>
				<?php if ( ! empty( $settings['linkedin']['url'] ) ) : ?>
					<a href="<?php echo esc_url( $settings['linkedin']['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'LinkedIn', 'rocketkit-addons-for-elementor' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
