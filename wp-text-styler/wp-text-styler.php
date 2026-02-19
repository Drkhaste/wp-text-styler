<?php
/**
 * Plugin Name:       WP Text Styler
 * Plugin URI:        https://example.com/wp-text-styler
 * Description:       Add highlight colors and styled boxes to Classic Editor (TinyMCE) with full settings management.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Your Name
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-text-styler
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'WP_TEXT_STYLER_VERSION', '1.0.0' );
define( 'WP_TEXT_STYLER_FILE', __FILE__ );
define( 'WP_TEXT_STYLER_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_TEXT_STYLER_URL', plugin_dir_url( __FILE__ ) );

// Load core classes
require_once WP_TEXT_STYLER_PATH . 'includes/class-defaults.php';
require_once WP_TEXT_STYLER_PATH . 'includes/class-assets.php';
require_once WP_TEXT_STYLER_PATH . 'includes/class-tinymce.php';
require_once WP_TEXT_STYLER_PATH . 'includes/class-css-generator.php';
require_once WP_TEXT_STYLER_PATH . 'admin/class-settings-page.php';

/**
 * Bootstrap the plugin.
 */
function wp_text_styler_init() {
	load_plugin_textdomain( 'wp-text-styler', false, dirname( plugin_basename( WP_TEXT_STYLER_FILE ) ) . '/languages' );

	WP_Text_Styler_Assets::init();
	WP_Text_Styler_TinyMCE::init();
	WP_Text_Styler_CSS_Generator::init();

	if ( is_admin() ) {
		WP_Text_Styler_Settings_Page::init();
	}
}
add_action( 'plugins_loaded', 'wp_text_styler_init' );

/**
 * Activation hook – set default options.
 */
function wp_text_styler_activate() {
	if ( false === get_option( 'wp_text_styler_highlights' ) ) {
		update_option( 'wp_text_styler_highlights', WP_Text_Styler_Defaults::highlights() );
	}
	if ( false === get_option( 'wp_text_styler_boxes' ) ) {
		update_option( 'wp_text_styler_boxes', WP_Text_Styler_Defaults::boxes() );
	}
	if ( false === get_option( 'wp_text_styler_post_types' ) ) {
		update_option( 'wp_text_styler_post_types', array( 'post', 'page' ) );
	}
}
register_activation_hook( WP_TEXT_STYLER_FILE, 'wp_text_styler_activate' );
