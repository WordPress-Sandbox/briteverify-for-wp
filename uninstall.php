<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

/* Clean up stuff */
delete_option( 'bv4wp_api_key' );
delete_option( 'bv4wp_method' );
delete_option( 'gravityformsaddon_briteverify-for-wp_settings' );
delete_option( 'bv4wp_edd' );