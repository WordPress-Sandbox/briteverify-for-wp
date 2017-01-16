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






