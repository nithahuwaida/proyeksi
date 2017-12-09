wp.customize.controlConstructor['hoo-palette'] = wp.customize.Control.extend({

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

		// Change the value
		this.container.on( 'click', 'input', function() {
			control.setting.set( jQuery( this ).val() );
		});
	}
});
