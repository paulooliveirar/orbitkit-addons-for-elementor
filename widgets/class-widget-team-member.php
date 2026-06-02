<?php
/**
 * Team Member Elementor widget.
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
 * Team member card with photo, role, and social links.
 */
class Widget_Team_Member extends Widget_Base {

	use OrbitKit_Widget_Base;
	use Widget_Style_Controls;

	/**
	 * @return string
	 */
	protected function get_widget_slug() {
		return 'team_member';
	}

	public function get_name() {
		return 'orbitkit_team_member';
	}

	public function get_title() {
		return esc_html__( 'Team Member', 'orbitkit-addons-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return array( 'orbitkit' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Member', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Photo', 'orbitkit-addons-for-elementor' ),
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
				'label'   => esc_html__( 'Name', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Alex Morgan',
			)
		);

		$this->add_control(
			'role',
			array(
				'label'   => esc_html__( 'Role', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Developer', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'bio',
			array(
				'label'   => esc_html__( 'Bio', 'orbitkit-addons-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'OrbitKit team member.', 'orbitkit-addons-for-elementor' ),
			)
		);

		$this->add_control(
			'email',
			array(
				'label' => esc_html__( 'Email', 'orbitkit-addons-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'linkedin',
			array(
				'label' => esc_html__( 'LinkedIn URL', 'orbitkit-addons-for-elementor' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->end_controls_section();

		$this->register_box_style_controls(
			'style_team_card',
			esc_html__( 'Card', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-team-member'
		);

		$this->register_box_style_controls(
			'style_team_photo',
			esc_html__( 'Photo', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-team-photo img'
		);

		$this->register_typography_style_controls(
			'style_team_name',
			esc_html__( 'Name', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-team-name'
		);

		$this->register_typography_style_controls(
			'style_team_role',
			esc_html__( 'Role', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-team-role'
		);

		$this->register_typography_style_controls(
			'style_team_bio',
			esc_html__( 'Bio', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-team-bio'
		);

		$this->register_typography_style_controls(
			'style_team_links',
			esc_html__( 'Links', 'orbitkit-addons-for-elementor' ),
			'.orbitkit-team-links a'
		);
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' );
		?>
		<div class="orbitkit-team-member">
			<?php if ( $image_html ) : ?>
				<div class="orbitkit-team-photo"><?php echo $image_html; // phpcs:ignore ?></div>
			<?php endif; ?>
			<h3 class="orbitkit-team-name"><?php echo esc_html( $settings['name'] ); ?></h3>
			<?php if ( ! empty( $settings['role'] ) ) : ?>
				<p class="orbitkit-team-role"><?php echo esc_html( $settings['role'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $settings['bio'] ) ) : ?>
				<p class="orbitkit-team-bio"><?php echo esc_html( $settings['bio'] ); ?></p>
			<?php endif; ?>
			<div class="orbitkit-team-links">
				<?php if ( ! empty( $settings['email'] ) ) : ?>
					<a href="mailto:<?php echo esc_attr( $settings['email'] ); ?>"><?php esc_html_e( 'Email', 'orbitkit-addons-for-elementor' ); ?></a>
				<?php endif; ?>
				<?php if ( ! empty( $settings['linkedin']['url'] ) ) : ?>
					<a href="<?php echo esc_url( $settings['linkedin']['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'LinkedIn', 'orbitkit-addons-for-elementor' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
