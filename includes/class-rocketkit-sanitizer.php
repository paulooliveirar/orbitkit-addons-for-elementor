<?php
/**
 * Input sanitization helpers.
 *
 * @package RocketKit\Elementor
 */

namespace RocketKit\Elementor\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared sanitizers for widget output.
 */
class RocketKit_Sanitizer {

	/**
	 * Allowed tags for inline SVG markers.
	 *
	 * @return array<string, array<string, bool>>
	 */
	public static function get_allowed_svg_tags() {
		return array(
			'svg'     => array(
				'xmlns'           => true,
				'viewbox'         => true,
				'viewBox'         => true,
				'width'           => true,
				'height'          => true,
				'fill'            => true,
				'stroke'          => true,
				'aria-hidden'     => true,
				'role'            => true,
				'class'           => true,
			),
			'g'       => array(
				'fill'    => true,
				'stroke'  => true,
				'transform' => true,
			),
			'path'    => array(
				'd'       => true,
				'fill'    => true,
				'stroke'  => true,
				'stroke-width' => true,
				'stroke-linejoin' => true,
				'stroke-linecap' => true,
			),
			'circle'  => array(
				'cx'      => true,
				'cy'      => true,
				'r'       => true,
				'fill'    => true,
				'stroke'  => true,
				'stroke-width' => true,
			),
			'rect'    => array(
				'x'       => true,
				'y'       => true,
				'width'   => true,
				'height'  => true,
				'rx'      => true,
				'fill'    => true,
				'stroke'  => true,
				'stroke-width' => true,
			),
			'polygon' => array(
				'points'  => true,
				'fill'    => true,
				'stroke'  => true,
				'stroke-width' => true,
				'stroke-linejoin' => true,
			),
			'title'   => array(),
		);
	}

	/**
	 * Sanitize inline SVG markup for safe frontend output.
	 *
	 * @param string $svg Raw SVG from the editor.
	 * @return string
	 */
	public static function sanitize_inline_svg( $svg ) {
		$svg = trim( (string) $svg );
		if ( '' === $svg ) {
			return '';
		}

		return wp_kses( $svg, self::get_allowed_svg_tags() );
	}
}
