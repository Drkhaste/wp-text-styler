<?php
/**
 * Admin settings page.
 *
 * @package WP_Text_Styler
 */

defined( 'ABSPATH' ) || exit;

class WP_Text_Styler_Settings_Page {

	const MENU_SLUG = 'wp-text-styler';

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	public static function add_menu() {
		add_options_page(
			esc_html__( 'Text Styler Settings', 'wp-text-styler' ),
			esc_html__( 'Text Styler', 'wp-text-styler' ),
			'manage_options',
			self::MENU_SLUG,
			array( __CLASS__, 'render_page' )
		);
	}

	public static function register_settings() {
		register_setting(
			'wp_text_styler_group',
			'wp_text_styler_highlights',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_highlights' ),
				'default'           => WP_Text_Styler_Defaults::highlights(),
			)
		);

		register_setting(
			'wp_text_styler_group',
			'wp_text_styler_boxes',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_boxes' ),
				'default'           => WP_Text_Styler_Defaults::boxes(),
			)
		);

		register_setting(
			'wp_text_styler_group',
			'wp_text_styler_post_types',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_post_types' ),
				'default'           => array( 'post', 'page' ),
			)
		);
	}

	/* ------------------------------------------------------------------
	 * Sanitize callbacks
	 * ------------------------------------------------------------------ */

	public static function sanitize_highlights( $raw ) {
		if ( ! is_array( $raw ) ) {
			return WP_Text_Styler_Defaults::highlights();
		}
		$clean = array();
		foreach ( $raw as $item ) {
			$clean[] = array(
				'key'   => sanitize_html_class( $item['key'] ?? '' ),
				'label' => sanitize_text_field( $item['label'] ?? '' ),
				'color' => sanitize_hex_color( $item['color'] ?? '#ffffff' ),
			);
		}
		// Regenerate CSS after save.
		add_action( 'shutdown', array( 'WP_Text_Styler_CSS_Generator', 'regenerate' ) );
		return $clean;
	}

	public static function sanitize_boxes( $raw ) {
		if ( ! is_array( $raw ) ) {
			return WP_Text_Styler_Defaults::boxes();
		}
		$clean = array();
		foreach ( $raw as $item ) {
			$clean[] = array(
				'key'           => sanitize_html_class( $item['key'] ?? '' ),
				'label'         => sanitize_text_field( $item['label'] ?? '' ),
				'bg_color'      => sanitize_hex_color( $item['bg_color'] ?? '#ffffff' ),
				'border_color'  => sanitize_hex_color( $item['border_color'] ?? '#000000' ),
				'border_width'  => absint( $item['border_width'] ?? 2 ),
				'border_radius' => absint( $item['border_radius'] ?? 6 ),
				'padding'       => absint( $item['padding'] ?? 14 ),
				'margin'        => absint( $item['margin'] ?? 16 ),
			);
		}
		add_action( 'shutdown', array( 'WP_Text_Styler_CSS_Generator', 'regenerate' ) );
		return $clean;
	}

	public static function sanitize_post_types( $raw ) {
		if ( ! is_array( $raw ) ) {
			return array();
		}
		return array_map( 'sanitize_key', $raw );
	}

	/* ------------------------------------------------------------------
	 * Render
	 * ------------------------------------------------------------------ */

	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'wp-text-styler' ) );
		}

		$highlights  = get_option( 'wp_text_styler_highlights', WP_Text_Styler_Defaults::highlights() );
		$boxes       = get_option( 'wp_text_styler_boxes', WP_Text_Styler_Defaults::boxes() );
		$post_types  = get_option( 'wp_text_styler_post_types', array( 'post', 'page' ) );
		$all_types   = get_post_types( array( 'public' => true ), 'objects' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'WP Text Styler – Settings', 'wp-text-styler' ); ?></h1>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'wp_text_styler_group' );
				wp_nonce_field( 'wp_text_styler_save', 'wp_text_styler_nonce' );
				?>

				<!-- ===================== HIGHLIGHTS ===================== -->
				<h2><?php esc_html_e( 'Highlight Colors', 'wp-text-styler' ); ?></h2>
				<p><?php esc_html_e( 'Define the highlight colors available in the editor. Changing a color affects all existing highlighted content automatically.', 'wp-text-styler' ); ?></p>

				<table class="wp-list-table widefat fixed striped" id="wpts-highlights-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Key (CSS class suffix)', 'wp-text-styler' ); ?></th>
							<th><?php esc_html_e( 'Name', 'wp-text-styler' ); ?></th>
							<th><?php esc_html_e( 'Color', 'wp-text-styler' ); ?></th>
							<th><?php esc_html_e( 'Preview', 'wp-text-styler' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ( $highlights as $i => $h ) : ?>
						<tr>
							<td>
								<input type="text"
									name="wp_text_styler_highlights[<?php echo esc_attr( $i ); ?>][key]"
									value="<?php echo esc_attr( $h['key'] ); ?>"
									class="regular-text"
									pattern="[a-z0-9\-]+"
									title="<?php esc_attr_e( 'Lowercase letters, numbers and hyphens only', 'wp-text-styler' ); ?>"
									required />
							</td>
							<td>
								<input type="text"
									name="wp_text_styler_highlights[<?php echo esc_attr( $i ); ?>][label]"
									value="<?php echo esc_attr( $h['label'] ); ?>"
									class="regular-text" />
							</td>
							<td>
								<input type="text"
									name="wp_text_styler_highlights[<?php echo esc_attr( $i ); ?>][color]"
									value="<?php echo esc_attr( $h['color'] ); ?>"
									class="wpts-color-picker" />
							</td>
							<td>
								<span class="wpts-preview-swatch"
									style="background:<?php echo esc_attr( $h['color'] ); ?>; padding:2px 10px; border-radius:3px;">
									<?php esc_html_e( 'Sample text', 'wp-text-styler' ); ?>
								</span>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<!-- ===================== BOXES ===================== -->
				<h2 style="margin-top:2em;"><?php esc_html_e( 'Box Presets', 'wp-text-styler' ); ?></h2>
				<p><?php esc_html_e( 'Configure the styled box presets. All sizes are in pixels.', 'wp-text-styler' ); ?></p>

				<?php foreach ( $boxes as $i => $b ) : ?>
				<details class="wpts-box-section" open>
					<summary style="cursor:pointer; font-weight:600; font-size:1.05em; margin:1em 0 .5em;">
						<?php echo esc_html( $b['label'] ); ?>
					</summary>
					<table class="form-table">
						<tr>
							<th><?php esc_html_e( 'Key', 'wp-text-styler' ); ?></th>
							<td>
								<input type="text"
									name="wp_text_styler_boxes[<?php echo esc_attr( $i ); ?>][key]"
									value="<?php echo esc_attr( $b['key'] ); ?>"
									class="regular-text"
									pattern="[a-z0-9\-]+"
									required />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Label', 'wp-text-styler' ); ?></th>
							<td>
								<input type="text"
									name="wp_text_styler_boxes[<?php echo esc_attr( $i ); ?>][label]"
									value="<?php echo esc_attr( $b['label'] ); ?>"
									class="regular-text" />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Background Color', 'wp-text-styler' ); ?></th>
							<td>
								<input type="text"
									name="wp_text_styler_boxes[<?php echo esc_attr( $i ); ?>][bg_color]"
									value="<?php echo esc_attr( $b['bg_color'] ); ?>"
									class="wpts-color-picker" />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Border Color', 'wp-text-styler' ); ?></th>
							<td>
								<input type="text"
									name="wp_text_styler_boxes[<?php echo esc_attr( $i ); ?>][border_color]"
									value="<?php echo esc_attr( $b['border_color'] ); ?>"
									class="wpts-color-picker" />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Border Width (px)', 'wp-text-styler' ); ?></th>
							<td>
								<input type="number" min="0" max="20"
									name="wp_text_styler_boxes[<?php echo esc_attr( $i ); ?>][border_width]"
									value="<?php echo esc_attr( $b['border_width'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Border Radius (px)', 'wp-text-styler' ); ?></th>
							<td>
								<input type="number" min="0" max="50"
									name="wp_text_styler_boxes[<?php echo esc_attr( $i ); ?>][border_radius]"
									value="<?php echo esc_attr( $b['border_radius'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Padding (px)', 'wp-text-styler' ); ?></th>
							<td>
								<input type="number" min="0" max="100"
									name="wp_text_styler_boxes[<?php echo esc_attr( $i ); ?>][padding]"
									value="<?php echo esc_attr( $b['padding'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Margin top/bottom (px)', 'wp-text-styler' ); ?></th>
							<td>
								<input type="number" min="0" max="100"
									name="wp_text_styler_boxes[<?php echo esc_attr( $i ); ?>][margin]"
									value="<?php echo esc_attr( $b['margin'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Live Preview', 'wp-text-styler' ); ?></th>
							<td>
								<div class="wpts-box-preview"
									style="
										background:<?php echo esc_attr( $b['bg_color'] ); ?>;
										border:<?php echo esc_attr( $b['border_width'] ); ?>px solid <?php echo esc_attr( $b['border_color'] ); ?>;
										border-radius:<?php echo esc_attr( $b['border_radius'] ); ?>px;
										padding:<?php echo esc_attr( $b['padding'] ); ?>px;
										margin:<?php echo esc_attr( $b['margin'] ); ?>px 0;
										max-width:400px;">
									<?php esc_html_e( 'This is a sample box preview.', 'wp-text-styler' ); ?>
								</div>
							</td>
						</tr>
					</table>
				</details>
				<?php endforeach; ?>

				<!-- ===================== POST TYPES ===================== -->
				<h2 style="margin-top:2em;"><?php esc_html_e( 'Enable for Post Types', 'wp-text-styler' ); ?></h2>
				<p><?php esc_html_e( 'The Text Styles toolbar button will appear only in selected post types.', 'wp-text-styler' ); ?></p>
				<fieldset>
					<?php foreach ( $all_types as $type ) : ?>
					<label style="display:block; margin:.3em 0;">
						<input type="checkbox"
							name="wp_text_styler_post_types[]"
							value="<?php echo esc_attr( $type->name ); ?>"
							<?php checked( in_array( $type->name, (array) $post_types, true ) ); ?> />
						<?php echo esc_html( $type->label ); ?>
						<code style="margin-left:.5em; font-size:.85em;">(<?php echo esc_html( $type->name ); ?>)</code>
					</label>
					<?php endforeach; ?>
				</fieldset>

				<!-- ===================== THEME INTEGRATION ===================== -->
				<hr style="margin-top:3em;">
				<h2 style="margin-top:2em;"><?php esc_html_e( 'Theme Integration Guide', 'wp-text-styler' ); ?></h2>
				<p><?php esc_html_e( 'If you are using custom templates or displaying content outside the standard WordPress loop (like Custom Post Types or custom fields), follow these steps to ensure styles are applied:', 'wp-text-styler' ); ?></p>
				<ol>
					<li>
						<strong><?php esc_html_e( 'Use the_content filter:', 'wp-text-styler' ); ?></strong><br>
						<?php esc_html_e( 'Ensure your content is passed through the filter so that WordPress and other plugins can process it correctly. This is often required for custom templates to render styled content.', 'wp-text-styler' ); ?>
						<pre style="background:#f0f0f0; padding:10px; border-radius:4px; margin:10px 0;"><code>echo apply_filters( 'the_content', $my_custom_content );</code></pre>
					</li>
					<li>
						<strong><?php esc_html_e( 'Check for wp_head() and wp_footer():', 'wp-text-styler' ); ?></strong><br>
						<?php esc_html_e( 'Your theme must include these calls in header.php and footer.php respectively for the plugin styles to be enqueued.', 'wp-text-styler' ); ?>
					</li>
				</ol>

				<?php submit_button( esc_html__( 'Save Settings', 'wp-text-styler' ) ); ?>
			</form>
		</div>
		<?php
	}
}
