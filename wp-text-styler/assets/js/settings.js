/**
 * WP Text Styler – Admin Settings JS
 *
 * Initialises WordPress color pickers and live-updates previews.
 */
/* global jQuery, wpColorPicker */
( function ( $ ) {
	'use strict';

	$( function () {

		// Initialise all color pickers.
		$( '.wpts-color-picker' ).wpColorPicker( {
			change: function ( event, ui ) {
				updatePreview( $( this ) );
			},
			clear: function () {
				// nothing
			},
		} );

		/**
		 * Live-update the nearest preview element when a color changes.
		 *
		 * @param {jQuery} $input The color picker input.
		 */
		function updatePreview( $input ) {
			var color  = $input.val();
			var $row   = $input.closest( 'tr' );

			// Update highlight swatch.
			var $swatch = $row.find( '.wpts-preview-swatch' );
			if ( $swatch.length ) {
				$swatch.css( 'background', color );
				return;
			}

			// Update box preview – find the details block this input belongs to.
			var $details = $input.closest( 'details.wpts-box-section' );
			if ( ! $details.length ) {
				return;
			}
			var $preview = $details.find( '.wpts-box-preview' );
			if ( ! $preview.length ) {
				return;
			}

			// Determine which property to update by looking at the field name.
			var name = $input.attr( 'name' ) || '';
			if ( name.indexOf( 'bg_color' ) !== -1 ) {
				$preview.css( 'background', color );
			} else if ( name.indexOf( 'border_color' ) !== -1 ) {
				// Rebuild border shorthand.
				var bw = $details.find( 'input[name*="border_width"]' ).val() || 2;
				$preview.css( 'border-color', color );
				$preview.css( 'border-width', bw + 'px' );
				$preview.css( 'border-style', 'solid' );
			}
		}

		// Also update box preview on numeric input changes.
		$( 'input[name*="border_width"], input[name*="border_radius"], input[name*="padding"], input[name*="margin"]' )
			.on( 'input change', function () {
				var $input   = $( this );
				var $details = $input.closest( 'details.wpts-box-section' );
				if ( ! $details.length ) return;
				var $preview = $details.find( '.wpts-box-preview' );
				if ( ! $preview.length ) return;

				var name = $input.attr( 'name' ) || '';
				var val  = parseInt( $input.val(), 10 ) || 0;

				if ( name.indexOf( 'border_width' ) !== -1 ) {
					$preview.css( 'border-width', val + 'px' );
					$preview.css( 'border-style', 'solid' );
				} else if ( name.indexOf( 'border_radius' ) !== -1 ) {
					$preview.css( 'border-radius', val + 'px' );
				} else if ( name.indexOf( 'padding' ) !== -1 ) {
					$preview.css( 'padding', val + 'px' );
				} else if ( name.indexOf( '[margin]' ) !== -1 ) {
					$preview.css( 'margin', val + 'px 0' );
				}
			} );

	} );

}( jQuery ) );
