jQuery( document ).ready( function( $ ){

	/* On Email Field Change Event */
	$( "#edd-user-email" ).change( function(){

		/* Var */
		var form_el = $( '#edd_register_form' );

		/* Ajax Request */
		$.ajax({
			type: "POST",
			url: bv4wp_edd_ajax.url,
			data:{
				action           : 'bv4wp_edd_validate_email',
				nonce            : bv4wp_edd_ajax.nonce,
				email            : $( this ).val(),
			},
			dataType: 'json',
			success: function( data ){
				if( false == data.is_valid ){
					alert( data.message );
				}
			},
		});
	});
});