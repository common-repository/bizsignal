jQuery( function( $ ) {
	$( '#kumapiyo_bzsgnl_option' ).change( function() {
		let key = $( this ).val().split( ',' );
		let after = _bzsignal_signals[key[0]].image_filename;
		if ( after ) {
			let img = $( "#after" ).find( ".bizsigimg" );
			img.attr( "src", img.attr( "src" ).replace( /^(.*)\/.*\.gif/, "$1/" + after ) );
		}
	} );
} );
