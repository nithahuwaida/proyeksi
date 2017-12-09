wp.customize.controlConstructor['hoo-toggle'] = wp.customize.Control.extend({

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

		var control = this,
		    checkboxValue = control.setting._value;

		control.container.find( '.hoo-controls-loading-spinner' ).hide();

		// Save the value
		this.container.on( 'change', 'input', function() {
			checkboxValue = ( jQuery( this ).is( ':checked' ) ) ? true : false;
			control.setting.set( checkboxValue );
		});
	}
});
