jQuery( document ).ready( function( $ ){

	/* Check Email Fields */
	if( bv4wp_gf_emails ){

		/* Loop each email input */
		$.each( bv4wp_gf_emails, function( input_id, value ) {

			/* Only If BriteVerify Validation Enabled */
			if( true == value.enable ){

				/* On Change Event */
				$( "#" + input_id ).change( function(){

					/* Var */
					var field_el = $( this ).parents( '.gfield' );

					/* Reset Error */
					field_el.removeClass( 'gfield_error' );
					field_el.find( '.validation_message' ).remove();

					/* Ajax Request */
					$.ajax({
						type: "POST",
						url: bv4wp_gf_ajax.url,
						data:{
							action           : 'bv4wp_gf_validate_email',
							nonce            : bv4wp_gf_ajax.nonce,
							email            : $( this ).val(),
							allow_disposable : value.allow_disposable,
						},
						dataType: 'json',
						success: function( data ){
							console.log( data );
							if( false == data.is_valid ){
								field_el.addClass( 'gfield_error' );
								if( field_el.hasClass( 'field_description_below' ) ){
									field_el.append( '<div class="gfield_description validation_message">' + data.message + '</div>' );
								}
								else{
									field_el.prepend( '<div class="gfield_description validation_message">' + data.message + '</div>' );
								}
							}
							return;
						},
					});
				});
			}
		});
	}
});