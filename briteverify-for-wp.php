<?php
/**
 * Plugin Name: BriteVerify for WP
 * Plugin URI: http://astoundify.com/downloads/briteverify-for-wp/
 * Description: BriteVerify email verification for WordPress.
 * Version: 1.0.0
 * Author: Astoundify
 * Author URI: http://astoundify.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: briteverify-for-wp
 * Domain Path: /languages/
**/
if ( ! defined( 'WPINC' ) ) { die; }

/* Constants
------------------------------------------ */

define( 'BV4WP_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'BV4WP_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'BV4WP_VERSION', '1.0.0' );


/* Plugin Init
------------------------------------------ */

/* Load plugin in "plugins_loaded" hook */
add_action( 'plugins_loaded', 'bv4wp_plugins_loaded', 9 );


/**
 * Plugins Loaded Hook
 * @since 1.0.0
 */
function bv4wp_plugins_loaded(){

	/* Load translation files */
	load_plugin_textdomain( dirname( plugin_basename( __FILE__ ) ), false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/* Load Functions */
	require_once( BV4WP_PATH . 'includes/functions.php' );

	/* Load Settings */
	if( is_admin() ){
		require_once( BV4WP_PATH . 'includes/settings.php' );
	}

	/* == IMPLEMENTATION == */

	/* GravityForms */
	require_once( BV4WP_PATH . 'includes/briteverify-gravityforms.php' );

	/* Easy Digital Downloads */
	require_once( BV4WP_PATH . 'includes/briteverify-edd.php' );

}
