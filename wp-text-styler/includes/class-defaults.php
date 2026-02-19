<?php
/**
 * Default values for highlights and boxes.
 *
 * @package WP_Text_Styler
 */

defined( 'ABSPATH' ) || exit;

class WP_Text_Styler_Defaults {

	/**
	 * Default highlight colors.
	 *
	 * @return array[]
	 */
	public static function highlights() {
		return array(
			array(
				'key'   => 'yellow',
				'label' => __( 'Yellow', 'wp-text-styler' ),
				'color' => '#fff176',
			),
			array(
				'key'   => 'green',
				'label' => __( 'Green', 'wp-text-styler' ),
				'color' => '#b9f6ca',
			),
			array(
				'key'   => 'blue',
				'label' => __( 'Blue', 'wp-text-styler' ),
				'color' => '#b3e5fc',
			),
			array(
				'key'   => 'red',
				'label' => __( 'Red', 'wp-text-styler' ),
				'color' => '#ffcdd2',
			),
		);
	}

	/**
	 * Default box presets.
	 *
	 * @return array[]
	 */
	public static function boxes() {
		return array(
			array(
				'key'            => 'info',
				'label'          => __( 'Info Box', 'wp-text-styler' ),
				'bg_color'       => '#e3f2fd',
				'border_color'   => '#1e88e5',
				'border_width'   => '2',
				'border_radius'  => '6',
				'padding'        => '14',
				'margin'         => '16',
			),
			array(
				'key'            => 'warning',
				'label'          => __( 'Warning Box', 'wp-text-styler' ),
				'bg_color'       => '#fff8e1',
				'border_color'   => '#f9a825',
				'border_width'   => '2',
				'border_radius'  => '6',
				'padding'        => '14',
				'margin'         => '16',
			),
			array(
				'key'            => 'success',
				'label'          => __( 'Success Box', 'wp-text-styler' ),
				'bg_color'       => '#e8f5e9',
				'border_color'   => '#2e7d32',
				'border_width'   => '2',
				'border_radius'  => '6',
				'padding'        => '14',
				'margin'         => '16',
			),
			array(
				'key'            => 'custom',
				'label'          => __( 'Custom Box', 'wp-text-styler' ),
				'bg_color'       => '#f3e5f5',
				'border_color'   => '#7b1fa2',
				'border_width'   => '2',
				'border_radius'  => '6',
				'padding'        => '14',
				'margin'         => '16',
			),
		);
	}
}
