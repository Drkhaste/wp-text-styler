<?php
/**
 * TinyMCE language strings for WP Text Styler.
 *
 * @package WP_Text_Styler
 */

defined( 'ABSPATH' ) || exit;

$strings = 'tinyMCE.addI18n({' .
	'"en":{' .
		'"wp_text_styler":{' .
			'"select_text":"' . esc_js( __( 'Please select some text first.', 'wp-text-styler' ) ) . '"' .
		'}' .
	'}' .
'});';
