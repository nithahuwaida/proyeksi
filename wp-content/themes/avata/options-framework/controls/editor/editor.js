wp.customize.controlConstructor['hoo-editor'] = wp.customize.Control.extend({

	// When we're finished loading continue processing
	ready: function() {

		'use strict';

		var control = this;

		// Init the control.
		if ( ! _.isUndefined( window.hooControlLoader ) && _.isFunction( hooControlLoader ) ) {
			hooControlLoader( control );
		} else {
			control.initHooControl();
		}
	},

	initHooControl: function() {

		'use strict';

		var control      = this,
		    element      = control.container.find( 'textarea' ),
		    toggler      = control.container.find( '.toggle-editor' ),
		    wpEditorArea = jQuery( '#hoo_editor_pane textarea.wp-editor-area' ),
		    setChange,
		    content;

		control.container.find( '.hoo-controls-loading-spinner' ).hide();
		jQuery( window ).load( function() {

			var editor  = tinyMCE.get( 'hoo-editor' );

			// Add the button text
			toggler.html( editorHooL10n['open-editor'] );

			toggler.on( 'click', function() {

				// Toggle the editor.
				control.toggleEditor();

				// Change button.
				control.changeButton();

				// Add the content to the editor.
				control.setEditorContent( editor );

				// Modify the preview-area height.
				control.previewHeight();

			});

			// Update the option from the editor contents on change.
			if ( editor ) {

				editor.onChange.add( function( ed ) {

					ed.save();
					content = editor.getContent();
					clearTimeout( setChange );
					setChange = setTimeout( function() {
						element.val( content ).trigger( 'change' );
						wp.customize.instance( control.getEditorWrapperSetting() ).set( content );
					}, 500 );
				});
			}

			// Handle text mode.
			wpEditorArea.on( 'change keyup paste', function() {
				wp.customize.instance( control.getEditorWrapperSetting() ).set( jQuery( this ).val() );
			});
		});
	},

	/**
	 * Modify the button text and classes.
	 */
	changeButton: function() {

		'use strict';

		var control = this;

		// Reset all editor buttons.
		// Necessary if we have multiple editor fields.
		jQuery( '.customize-control-hoo-editor .toggle-editor' ).html( editorHooL10n['switch-editor'] );

		// Change the button text & color.
		if ( false !== control.getEditorWrapperSetting() ) {
			jQuery( '.customize-control-hoo-editor .toggle-editor' ).html( editorHooL10n['switch-editor'] );
			jQuery( '#customize-control-' + control.getEditorWrapperSetting() + ' .toggle-editor' ).html( editorHooL10n['close-editor'] );
		} else {
			jQuery( '.customize-control-hoo-editor .toggle-editor' ).html( editorHooL10n['open-editor'] );
		}
	},

	/**
	 * Toggle the editor.
	 */
	toggleEditor: function() {

		'use strict';

		var control = this,
		    editorWrapper = jQuery( '#hoo_editor_pane' );

		if ( ! control.getEditorWrapperSetting() || control.id !== control.getEditorWrapperSetting() ) {
			editorWrapper.removeClass();
			editorWrapper.addClass( control.id );
		} else {
			editorWrapper.removeClass();
			editorWrapper.addClass( 'hide' );
		}
	},

	/**
	 * Set the content.
	 */
	setEditorContent: function( editor ) {

		'use strict';

		var control = this;

		editor.setContent( control.setting._value );
	},

	/**
	 * Gets the setting from the editor wrapper class.
	 */
	getEditorWrapperSetting: function() {

		'use strict';

		if ( jQuery( '#hoo_editor_pane' ).hasClass( 'hide' ) ) {
			return false;
		}

		if ( jQuery( '#hoo_editor_pane' ).attr( 'class' ) ) {
			return jQuery( '#hoo_editor_pane' ).attr( 'class' );
		} else {
			return false;
		}
	},

	/**
	 * Modifies the height of the preview area.
	 */
	previewHeight: function() {
		if ( jQuery( '#hoo_editor_pane' ).hasClass( 'hide' ) ) {
			if ( jQuery( '#customize-preview' ).hasClass( 'is-hoo-editor-open' ) ) {
				jQuery( '#customize-preview' ).removeClass( 'is-hoo-editor-open' );
			}
		} else {
			if ( ! jQuery( '#customize-preview' ).hasClass( 'is-hoo-editor-open' ) ) {
				jQuery( '#customize-preview' ).addClass( 'is-hoo-editor-open' );
			}
		}
	}
});
