/* SE Portfolio — Admin JavaScript
 * Handles the WordPress media uploader for image fields.
 */

( function ( $ ) {
	'use strict';

	$( document ).on( 'click', '.sep-upload-btn', function ( e ) {
		e.preventDefault();

		var targetId = $( this ).data( 'target' );
		var $field   = $( '#' + targetId );
		var $btn     = $( this );

		var frame = wp.media( {
			title:    'Select Image',
			button:   { text: 'Use this image' },
			multiple: false,
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			$field.val( attachment.id );

			// Update preview image if it exists.
			var $preview = $btn.siblings( 'img' );
			if ( $preview.length ) {
				$preview.attr( 'src', attachment.sizes && attachment.sizes.thumbnail
					? attachment.sizes.thumbnail.url
					: attachment.url
				);
			} else {
				$btn.before( '<img src="' + ( attachment.sizes && attachment.sizes.thumbnail
					? attachment.sizes.thumbnail.url
					: attachment.url ) + '" style="max-width:100px;display:block;margin-bottom:8px;">' );
			}
		} );

		frame.open();
	} );

} )( jQuery );
