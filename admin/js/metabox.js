/**
 * Handle upload media
 * field image
 */
( function ( $ ) {
	$( '.tc-file-input-select' ).click( function ( e ) {
		e.preventDefault();
		var image = wp
			.media( {
				title: tcStrings.upload_message,
				multiple: false,
			} )
			.open()
			.on( 'select', function ( e ) {
				var uploaded_image = image.state().get( 'selection' ).first();
				var image_url = uploaded_image.toJSON().url;
				$( '.tc-file-input' ).val( image_url );
			} );
	} );

	$( '.tc-file-input-reset' ).click( function ( e ) {
		e.preventDefault();
		$( '.tc-file-input' ).attr( 'value', '' );
	} );

	/**
	 * Hide/show options
	 * according to card type
	 */
	$( '#twitterCardType' )
		.on( 'change', function () {
			'player' === this.value
				? $( '*[class^="cardPlayer"]' ).show()
				: $( '*[class^="cardPlayer"]' ).hide();
		} )
		.change();
} )( jQuery );
