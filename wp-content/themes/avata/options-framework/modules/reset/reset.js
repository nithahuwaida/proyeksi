jQuery( document ).ready( function() {

	'use strict';

	wp.customize.section.each( function( section ) {

		var link = '<a href="#" class="hoo-reset-section" data-reset-section-id="' + section.id + '">' + hooResetButtonLabel['reset-with-icon'] + '</a>';

		jQuery( link ).appendTo( '#sub-accordion-section-' + section.id + ' .customize-section-title > h3' );

	});

	jQuery( 'a.hoo-reset-section' ).on( 'click', function() {

		var id       = jQuery( this ).data( 'reset-section-id' ),
		    controls = wp.customize.section( id ).controls();

		// Loop controls
		_.each( controls, function( control, i ) {

			// Set value to default
			hooSetSettingValue.set( controls[ i ].id, control.params['default'] );

		});

	});

});
