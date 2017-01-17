<?php
/**
 * Utility Functions
 * @since 1.0.0
**/

/**
 * Get API Key
 * @since 1.0.0
 */
function bv4wp_api_key(){
	return sanitize_text_field( strip_tags( trim( get_option( 'bv4wp_api_key' ) ) ) );
}


/**
 * Validate Email
 * return value: valid, invalid, disposable, error
 * @return string
 * @since 1.0.0
 */
function bv4wp_validate_email( $email, $api_key = '' ){

	/* Bail if no API key */
	if( '' == $api_key ){
		$api_key = bv4wp_api_key();
		if( ! $api_key ){
			return 'error';
		}
	}

	/* Bail if no email/email is not valid */
	if( ! $email || ! is_email( $email ) ){
		return 'invalid';
	}

	/* API URL */
	$url = add_query_arg( array(
		'address' => urlencode( trim( $email ) ),
		'apikey'  => urlencode( trim( $api_key ) ),
	), 'https://bpi.briteverify.com/emails.json' );

	/* Request Check */
	$raw_response = wp_remote_get( esc_url_raw( $url ) );

	/* Request to BriteVerify fail */
	if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
		return 'error';
	}

	/* JSON Data Result */
	$data = json_decode( trim( wp_remote_retrieve_body( $raw_response ) ), true );

	/* Status */
	return $data['status'];
	if( isset( $data['status'] ) ){
		if( 'valid' == $data['status'] ){
			if( isset( $data['disposable'] ) && true == $data['disposable'] ){
				return 'disposable';
			}
			return 'valid';
		}
		else{
			return 'invalid';
		}
	}
	else{
		return 'error';
	}
}



















