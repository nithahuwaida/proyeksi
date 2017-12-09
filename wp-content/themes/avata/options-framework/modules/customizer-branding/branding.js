jQuery( document ).ready( function() {

	'use strict';

	if ( '' !== hooBranding.logoImage ) {
		jQuery( 'div#customize-info .preview-notice' ).replaceWith( '<img src="' + hooBranding.logoImage + '">' );
	}

	if ( '' !== hooBranding.description ) {
		jQuery( 'div#customize-info > .customize-panel-description' ).replaceWith( '<div class="customize-panel-description">' + hooBranding.description + '</div>' );
	}

});
