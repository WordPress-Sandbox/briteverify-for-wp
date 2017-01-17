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
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		/* Load if EDD active */
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			
		}
	}

	/**
	 * Settings Note
	 * @since 1.0.0
	 */
	public function register_settings(){

		/* Register settings */
		register_setting(
			$option_group      = 'bv4wp',
			$option_name       = 'bv4wp_edd',
			$sanitize_callback = array( $this, 'sanitize' )
		);

		/* EDD Field */
		add_settings_field(
			$field_id          = 'bv4wp_field_edd_note',
			$field_title       = __( 'Easy Digital Downloads', 'briteverify-for-wp' ),
			$callback_function = array( $this, 'settings_field' ),
			$settings_slug     = 'bv4wp',
			$section_id        = 'bv4wp_section_plugins'
		);
	}

	/**
	 * Sanitize
	 * @since 1.0.0
	 */
	public function sanitize( $data ){
		$reg = isset( $data['enable_register'] ) && 1 == $data['enable_register'] ? true : false;
		$out = array(
			'enable_register' => $reg,
		);
		return $out;
	}


	/**
	 * Settings Note Callback
	 * @since 1.0.0
	 */
	public function settings_field(){

		/* GF Plugin is active */
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			?>
			<label>
				<input name="bv4wp_edd[enable_register]" id="bv4wp_edd_enable" value="1" type="checkbox"> 
				<?php _e( 'Enable in EDD registration form.', 'briteverify-for-wp' ); ?>
			</label>
			<?php
		}
		/* Not active */
		else{
			echo wpautop( __( 'The easiest way to sell digital products with WordPress.', 'briteverify-for-wp' ) . ' <a href="https://easydigitaldownloads.com" target="_blank">' . __( 'Learn more', 'briteverify-for-wp' ) . '.</a>' );
		}
	}

}

