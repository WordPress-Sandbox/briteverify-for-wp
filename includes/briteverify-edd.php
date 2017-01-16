<?php
if ( ! defined( 'WPINC' ) ) { die; }
BV4WP_EDD_Setup::get_instance();

/**
 * EDD
 * @since 1.0.0
**/
class BV4WP_EDD_Setup{

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

		/* Load if EDD active */
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			
		}
	}

	/**
	 * Settings Note
	 * @since 1.0.0
	 */
	public function settings_note(){
		add_settings_field(
			$field_id          = 'bv4wp_field_edd_note',
			$field_title       = __( 'Easy Digital Downloads', 'briteverify-for-wp' ),
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
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			$url = add_query_arg( array(
				'page' => 'gf_settings',
				'subview' => 'briteverify-for-wp',
			), admin_url( 'admin.php' ) );
			//echo wpautop( '<a href="' . esc_url( $url ) . '">' . __( 'View Settings', 'briteverify-for-wp' ) . '</a>' );
		}
		/* Not active */
		else{
			echo wpautop( __( 'The easiest way to sell digital products with WordPress.', 'briteverify-for-wp' ) . ' <a href="https://easydigitaldownloads.com" target="_blank">' . __( 'Learn more', 'briteverify-for-wp' ) . '.</a>' );
		}
	}

}

