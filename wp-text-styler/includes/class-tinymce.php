<?php
/**
 * Registers TinyMCE plugin and buttons.
 *
 * @package WP_Text_Styler
 */

defined( 'ABSPATH' ) || exit;

class WP_Text_Styler_TinyMCE {

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'setup' ) );
	}

	public static function setup() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// NOTE: get_user_option returns string '0' or '1' or boolean - check carefully
		$rich = get_user_option( 'rich_editing' );
		if ( $rich === '0' || $rich === false ) {
			return;
		}

		// Register our external JS plugin
		add_filter( 'mce_external_plugins', array( __CLASS__, 'register_plugin' ) );

		// Add buttons to toolbar row 1
		add_filter( 'mce_buttons', array( __CLASS__, 'register_buttons' ) );

		// Allow our HTML tags/classes through TinyMCE sanitizer
		add_filter( 'tiny_mce_before_init', array( __CLASS__, 'extend_valid_elements' ) );

		// Inject CSS into editor iframe
		add_filter( 'mce_css', array( __CLASS__, 'editor_css' ) );

		// Print global JS config var
		add_action( 'admin_print_footer_scripts', array( __CLASS__, 'print_js_config' ), 1 );
	}

	/**
	 * Register the external TinyMCE plugin JS file.
	 */
	public static function register_plugin( $plugins ) {
		$plugins['wp_text_styler'] = WP_TEXT_STYLER_URL . 'assets/js/tinymce-plugin.js?ver=' . WP_TEXT_STYLER_VERSION;
		return $plugins;
	}

	/**
	 * Add buttons to toolbar.
	 * Always adds them - post type check happens at render time via print_js_config.
	 */
	public static function register_buttons( $buttons ) {
		$buttons[] = 'separator';
		$buttons[] = 'wpts_hl_yellow';
		$buttons[] = 'wpts_hl_green';
		$buttons[] = 'wpts_hl_blue';
		$buttons[] = 'wpts_hl_red';
		$buttons[] = 'separator';
		$buttons[] = 'wpts_box_info';
		$buttons[] = 'wpts_box_warning';
		$buttons[] = 'wpts_box_success';
		$buttons[] = 'wpts_box_custom';
		$buttons[] = 'separator';
		$buttons[] = 'wpts_remove';
		return $buttons;
	}

	/**
	 * Prevent TinyMCE from stripping our span/div with classes.
	 */
	public static function extend_valid_elements( $init ) {
		$add = 'span[*],div[*]';
		if ( ! empty( $init['extended_valid_elements'] ) ) {
			$init['extended_valid_elements'] .= ',' . $add;
		} else {
			$init['extended_valid_elements'] = $add;
		}
		return $init;
	}

	/**
	 * Inject our dynamic CSS into TinyMCE iframe via AJAX endpoint.
	 */
	public static function editor_css( $mce_css ) {
		$url = add_query_arg(
			array(
				'action' => 'wp_text_styler_editor_css',
				'ver'    => WP_TEXT_STYLER_VERSION,
			),
			admin_url( 'admin-ajax.php' )
		);
		$mce_css .= ( $mce_css ? ',' : '' ) . esc_url_raw( $url );
		return $mce_css;
	}

	/**
	 * Print global JS config variable in footer (before TinyMCE initializes).
	 */
	public static function print_js_config() {
		// Only on post-editing screens.
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( $screen && ! in_array( $screen->base, array( 'post', 'page' ), true ) ) {
			return;
		}

		// Seed defaults if missing.
		$highlights = get_option( 'wp_text_styler_highlights' );
		if ( empty( $highlights ) || ! is_array( $highlights ) ) {
			$highlights = WP_Text_Styler_Defaults::highlights();
			update_option( 'wp_text_styler_highlights', $highlights );
		}
		$boxes = get_option( 'wp_text_styler_boxes' );
		if ( empty( $boxes ) || ! is_array( $boxes ) ) {
			$boxes = WP_Text_Styler_Defaults::boxes();
			update_option( 'wp_text_styler_boxes', $boxes );
		}

		echo '<script>window.wpTextStylerConfig = ' . wp_json_encode( array(
			'highlights' => $highlights,
			'boxes'      => $boxes,
		) ) . ';</script>' . "\n";
	}
}

/* ── AJAX: serve dynamic CSS into the TinyMCE iframe ─────────────────────── */
add_action( 'wp_ajax_wp_text_styler_editor_css',        'wp_text_styler_serve_editor_css' );
add_action( 'wp_ajax_nopriv_wp_text_styler_editor_css', 'wp_text_styler_serve_editor_css' );

function wp_text_styler_serve_editor_css() {
	header( 'Content-Type: text/css; charset=UTF-8' );
	header( 'Cache-Control: public, max-age=3600' );
	echo WP_Text_Styler_CSS_Generator::get_css(); // phpcs:ignore
	exit;
}
