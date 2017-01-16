<?php
/**
 * Gravity Forms Functions
 * @since 1.0.0
**/

/**
 * Enable
 * @since 1.0.0
 */
function bv4wp_gf_option_enable(){
	$option = get_option( 'gravityformsaddon_briteverify-for-wp_settings' );
	if( $option && isset( $option['enable'] ) && $option['enable'] ){
		return true;
	}
	return false;
}


/**
 * Allow Disposable
 * @since 1.0.0
 */
function bv4wp_gf_option_allow_disposable(){
	$option = get_option( 'gravityformsaddon_briteverify-for-wp_settings' );
	if( $option && isset( $option['allow_disposable'] ) && $option['allow_disposable'] ){
		return true;
	}
	return false;
}












