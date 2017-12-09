wp.customize.controlConstructor['hoo-generic'] = wp.customize.Control.extend({

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

		var control = this;

		control.container.find( '.hoo-controls-loading-spinner' ).hide();

		// Save the value
		this.container.on( 'change keyup paste', control.params.choices.element, function() {
			control.setting.set( jQuery( this ).val() );
		});
	}
});
