<?php
/**
 * Generates and caches dynamic CSS from plugin settings.
 *
 * @package WP_Text_Styler
 */

defined( 'ABSPATH' ) || exit;

class WP_Text_Styler_CSS_Generator {

	const OPTION_KEY = 'wp_text_styler_generated_css';

	public static function init() {
		add_action( 'update_option_wp_text_styler_highlights', array( __CLASS__, 'regenerate' ) );
		add_action( 'update_option_wp_text_styler_boxes', array( __CLASS__, 'regenerate' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_css' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_css' ) );
	}

	/**
	 * Build CSS string from current settings.
	 *
	 * @return string
	 */
	public static function build_css() {
		$highlights = get_option( 'wp_text_styler_highlights', WP_Text_Styler_Defaults::highlights() );
		$boxes      = get_option( 'wp_text_styler_boxes', WP_Text_Styler_Defaults::boxes() );

		$css = "/* WP Text Styler – auto-generated, do not edit manually */\n";

		// Highlights
		foreach ( $highlights as $h ) {
			$key   = sanitize_html_class( $h['key'] );
			$color = sanitize_hex_color( $h['color'] );
			if ( ! $key || ! $color ) {
				continue;
			}
			$css .= ".plugin-highlight-{$key} { background-color: {$color}; padding: 0 2px; border-radius: 3px; }\n";
		}

		// Boxes
		foreach ( $boxes as $b ) {
			$key    = sanitize_html_class( $b['key'] );
			$bg     = sanitize_hex_color( $b['bg_color'] );
			$border = sanitize_hex_color( $b['border_color'] );
			$bw     = absint( $b['border_width'] );
			$br     = absint( $b['border_radius'] );
			$pad    = absint( $b['padding'] );
			$mar    = absint( $b['margin'] );

			if ( ! $key || ! $bg ) {
				continue;
			}

			$css .= ".plugin-box-{$key} {\n";
			$css .= "  display: block;\n";
			$css .= "  background-color: {$bg};\n";
			if ( $border && $bw ) {
				$css .= "  border: {$bw}px solid {$border};\n";
			}
			if ( $br ) {
				$css .= "  border-radius: {$br}px;\n";
			}
			if ( $pad ) {
				$css .= "  padding: {$pad}px;\n";
			}
			if ( $mar ) {
				$css .= "  margin: {$mar}px 0;\n";
			}
			$css .= "}\n";
		}

		return $css;
	}

	/**
	 * Regenerate and cache CSS.
	 */
	public static function regenerate() {
		update_option( self::OPTION_KEY, self::build_css() );
	}

	/**
	 * Get cached CSS; generate on first call.
	 *
	 * @return string
	 */
	public static function get_css() {
		$css = get_option( self::OPTION_KEY, '' );
		if ( empty( $css ) ) {
			$css = self::build_css();
			update_option( self::OPTION_KEY, $css );
		}
		return $css;
	}

	/**
	 * Enqueue generated CSS in the admin (for the editor preview).
	 */
	public static function enqueue_admin_css() {
		$screen = get_current_screen();
		if ( $screen && 'post' === $screen->base ) {
			$allowed = get_option( 'wp_text_styler_post_types', array( 'post', 'page' ) );
			if ( in_array( $screen->post_type, (array) $allowed, true ) ) {
				wp_add_inline_style( 'wp-text-styler-editor', self::get_css() );
			}
		}
	}

	/**
	 * Enqueue generated CSS on the frontend.
	 */
	public static function enqueue_frontend_css() {
		wp_add_inline_style( 'wp-text-styler-frontend', self::get_css() );
	}
}
