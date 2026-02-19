/**
 * WP Text Styler – TinyMCE Plugin
 * Minimal, debug-friendly version
 */
( function () {
	'use strict';

	tinymce.PluginManager.add( 'wp_text_styler', function ( editor ) {

		// Simple hardcoded test button - no config dependency
		editor.addButton( 'wpts_hl_yellow', {
			title  : 'Yellow Highlight',
			text   : 'HL',
			icon   : false,
			onclick: function () {
				var sel = editor.selection.getContent();
				if ( ! sel ) { return; }
				editor.insertContent( '<span class="plugin-highlight-yellow">' + sel + '</span>' );
			},
		} );

		editor.addButton( 'wpts_hl_green', {
			title  : 'Green Highlight',
			text   : 'GR',
			icon   : false,
			onclick: function () {
				var sel = editor.selection.getContent();
				if ( ! sel ) { return; }
				editor.insertContent( '<span class="plugin-highlight-green">' + sel + '</span>' );
			},
		} );

		editor.addButton( 'wpts_hl_blue', {
			title  : 'Blue Highlight',
			text   : 'BL',
			icon   : false,
			onclick: function () {
				var sel = editor.selection.getContent();
				if ( ! sel ) { return; }
				editor.insertContent( '<span class="plugin-highlight-blue">' + sel + '</span>' );
			},
		} );

		editor.addButton( 'wpts_hl_red', {
			title  : 'Red Highlight',
			text   : 'RD',
			icon   : false,
			onclick: function () {
				var sel = editor.selection.getContent();
				if ( ! sel ) { return; }
				editor.insertContent( '<span class="plugin-highlight-red">' + sel + '</span>' );
			},
		} );

		editor.addButton( 'wpts_box_info', {
			title  : 'Info Box',
			text   : 'INF',
			icon   : false,
			onclick: function () {
				var sel = editor.selection.getContent();
				if ( ! sel ) { return; }
				editor.insertContent( '<div class="plugin-box-info">' + sel + '</div>' );
			},
		} );

		editor.addButton( 'wpts_box_warning', {
			title  : 'Warning Box',
			text   : 'WAR',
			icon   : false,
			onclick: function () {
				var sel = editor.selection.getContent();
				if ( ! sel ) { return; }
				editor.insertContent( '<div class="plugin-box-warning">' + sel + '</div>' );
			},
		} );

		editor.addButton( 'wpts_box_success', {
			title  : 'Success Box',
			text   : 'SUC',
			icon   : false,
			onclick: function () {
				var sel = editor.selection.getContent();
				if ( ! sel ) { return; }
				editor.insertContent( '<div class="plugin-box-success">' + sel + '</div>' );
			},
		} );

		editor.addButton( 'wpts_box_custom', {
			title  : 'Custom Box',
			text   : 'CUS',
			icon   : false,
			onclick: function () {
				var sel = editor.selection.getContent();
				if ( ! sel ) { return; }
				editor.insertContent( '<div class="plugin-box-custom">' + sel + '</div>' );
			},
		} );

		editor.addButton( 'wpts_remove', {
			title  : 'Remove Style',
			text   : '✕',
			icon   : false,
			onclick: function () {
				var node = editor.selection.getNode();
				if ( node && node.className && node.className.indexOf('plugin-') !== -1 ) {
					editor.dom.remove( node, true );
				}
			},
		} );

	} );
}() );
