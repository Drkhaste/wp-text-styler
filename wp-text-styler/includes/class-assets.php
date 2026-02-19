<?php
/**
 * Handles script and style registration / enqueueing.
 *
 * @package WP_Text_Styler
 */

defined( 'ABSPATH' ) || exit;

class WP_Text_Styler_Assets {

	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend_enqueue' ) );
	}

	/**
	 * Admin assets (settings page + editor base CSS).
	 */
	public static function admin_enqueue( $hook ) {
		// Register editor CSS so inline style can be attached in CSS Generator.
		wp_register_style(
			'wp-text-styler-editor',
			WP_TEXT_STYLER_URL . 'assets/css/editor.css',
			array(),
			WP_TEXT_STYLER_VERSION
		);

		$screen = get_current_screen();
		if ( $screen && 'post' === $screen->base ) {
			$allowed = get_option( 'wp_text_styler_post_types', array( 'post', 'page' ) );
			if ( in_array( $screen->post_type, (array) $allowed, true ) ) {
				wp_enqueue_style( 'wp-text-styler-editor' );
			}
		}

		// Settings page assets.
		if ( 'settings_page_wp-text-styler' === $hook ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script(
				'wp-text-styler-settings',
				WP_TEXT_STYLER_URL . 'assets/js/settings.js',
				array( 'jquery', 'wp-color-picker' ),
				WP_TEXT_STYLER_VERSION,
				true
			);
			wp_localize_script(
				'wp-text-styler-settings',
				'wpTextStylerSettings',
				array(
					'nonce' => wp_create_nonce( 'wp_text_styler_settings' ),
				)
			);
		}
	}

	/**
	 * Frontend stylesheet.
	 */
	public static function frontend_enqueue() {
		wp_register_style(
			'wp-text-styler-frontend',
			WP_TEXT_STYLER_URL . 'assets/css/frontend.css',
			array(),
			WP_TEXT_STYLER_VERSION
		);
		wp_enqueue_style( 'wp-text-styler-frontend' );
	}
}
