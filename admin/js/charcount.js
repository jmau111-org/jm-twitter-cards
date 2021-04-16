/* eslint-disable no-undef */
( function ( $ ) {
	'use strict';
	var textArea = $( '.textarea' );
	textArea.each( function ( i ) {
		$( this ).after( '<div id="tc-charcount-' + i + '"></div>' );
		$( this ).keyup( function () {
			var max = $( this ).data( 'count' ),
				len = $( this ).val().length,
				div = $( '#tc-charcount-' + i );
			if ( len >= max ) {
				div.text( max - len );
				div.css( 'color', 'red' );
			} else {
				div.text( max - len + ' ' + _tcStrings.message );
			}
		} );
	} );
} )( jQuery );
