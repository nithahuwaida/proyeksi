( function() {

	_.each( collapsible, function( label, setting ) {

		setTimeout( function() {
			var control = jQuery( '#customize-control-' + setting ),
			    controlTitleElement;

			// Collapse field.
			control.addClass( 'hoo-collapsible hoo-collapsed-control' );

			// Add the header before the field.
			control.before( '<div class="hoo-collapsed-control-header hoo-collapsible-header-' + setting + '"><span class="customize-control-title"><span class="dashicons dashicons-arrow-down-alt2"></span> ' + label + '</span></div>' );

			// Add an (x) before the field title.
			controlTitleElement = control.find( '.customize-control-title' );
			controlTitleElement.prepend( '<span class="dashicons dashicons-arrow-up-alt2"></span>' );

			// Show/hide the field when the header is clicked.
			jQuery( '.hoo-collapsible-header-' + setting ).click( function() {
				if ( control.hasClass( 'hoo-collapsed-control' ) ) {
					control.removeClass( 'hoo-collapsed-control' );
					control.addClass( 'hoo-expanded-control' );
					control.show();
					jQuery( '.hoo-collapsible-header-' + setting ).hide();
				} else {
					control.addClass( 'hoo-collapsed-control' );
					control.removeClass( 'hoo-expanded-control' );
					control.hide();
					jQuery( '.hoo-collapsible-header-' + setting ).show();
				}
			});

			controlTitleElement.click( function() {
				if ( control.hasClass( 'hoo-collapsed-control' ) ) {
					control.removeClass( 'hoo-collapsed-control' );
					control.addClass( 'hoo-expanded-control' );
					control.show();
					jQuery( '.hoo-collapsible-header-' + setting ).hide();
				} else {
					control.addClass( 'hoo-collapsed-control' );
					control.removeClass( 'hoo-expanded-control' );
					control.hide();
					jQuery( '.hoo-collapsible-header-' + setting ).show();
				}
			});

		}, 300 );

	});

})( jQuery );
