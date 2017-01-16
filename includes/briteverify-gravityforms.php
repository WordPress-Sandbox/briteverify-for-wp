<?php
if ( ! defined( 'WPINC' ) ) { die; }
BV4WP_GravityForms_Setup::get_instance();

/**
 * Gravity Forms
 * @since 1.0.0
**/
class BV4WP_GravityForms_Setup{

	/**
	 * Returns the instance.
	 */
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ) $instance = new self;
		return $instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		/* Add Note in Plugin Settings */
		add_action( 'admin_init', array( $this, 'settings_note' ) );

		/* On Gravity Forms Load */
		add_action( 'gform_loaded', array( $this, 'init' ) );
	}

	/**
	 * Settings Note
	 * @since 1.0.0
	 */
	public function settings_note(){
		add_settings_field(
			$field_id          = 'bv4wp_field_gravityforms_note',
			$field_title       = __( 'Gravity Forms', 'briteverify-for-wp' ),
			$callback_function = array( $this, 'settings_note_callback' ),
			$settings_slug     = 'bv4wp',
			$section_id        = 'bv4wp_section_plugins'
		);
	}

	/**
	 * Settings Note Callback
	 * @since 1.0.0
	 */
	public function settings_note_callback(){

		/* GF Plugin is active */
		if ( class_exists( 'GFCommon' ) ) {
			$url = add_query_arg( array(
				'page' => 'gf_settings',
				'subview' => 'briteverify-for-wp',
			), admin_url( 'admin.php' ) );
			echo wpautop( '<a href="' . esc_url( $url ) . '">' . __( 'View Settings', 'briteverify-for-wp' ) . '</a>' );
		}
		/* Not active */
		else{
			echo wpautop( __( 'Gravity Forms is a full featured contact form plugin that features a drag and drop interface.', 'briteverify-for-wp' ) . ' <a href="http://www.gravityforms.com/" target="_blank">' . __( 'Learn more', 'briteverify-for-wp' ) . '.</a>' );
		}
	}

	/**
	 * Init: Gravity Forms Load
	 * @since 1.0.0
	 */
	public function init(){
		/* Check if method exists */
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		/* Load Functions */
		require_once( BV4WP_PATH . 'includes/gravityforms/functions.php' );

		/* Load Class */
		require_once( BV4WP_PATH . 'includes/gravityforms/gravityforms.php' );

		/* Register Add-On */
		GFAddOn::register( 'BV4WP_GravityForms' );
	}

}

