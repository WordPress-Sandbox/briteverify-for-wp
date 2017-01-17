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

		/* API Key */
		$api_key = bv4wp_api_key();

		/* Add Note in Plugin Settings */
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		/* Load if EDD active */
		if ( class_exists( 'Easy_Digital_Downloads' ) && $api_key ) {

			/* Validate register email */
			add_action( 'edd_process_register_form', array( $this, 'validate_register_email' ) );
		}
	}

	/**
	 * Settings Note
	 * @since 1.0.0
	 */
	public function register_settings(){

		/* Register settings */
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			register_setting(
				$option_group      = 'bv4wp',
				$option_name       = 'bv4wp_edd',
				$sanitize_callback = array( $this, 'sanitize' )
			);
		}

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
			$option = get_option( 'bv4wp_edd' );
			$enable_register = isset( $option['enable_register'] ) && $option['enable_register'] ?  1 : 0
			?>
			<label>
				<input name="bv4wp_edd[enable_register]" id="bv4wp_edd_enable" value="1" type="checkbox" <?php checked( $enable_register, 1 ); ?>>
				<?php _e( 'Enable in EDD registration form.', 'briteverify-for-wp' ); ?>
			</label>
			<?php
		}
		/* Not active */
		else{
			echo wpautop( __( 'The easiest way to sell digital products with WordPress.', 'briteverify-for-wp' ) . ' <a href="https://easydigitaldownloads.com" target="_blank">' . __( 'Learn more', 'briteverify-for-wp' ) . '.</a>' );
		}
	}

	/**
	 * Validate Register Email
	 * @since 1.0.0
	 */
	public function validate_register_email(){
		if( isset( $_POST['edd_user_email'] ) && is_email( $_POST['edd_user_email'] ) ) {
			$valid_status = bv4wp_validate_email( $_POST['edd_user_email'] );
			if( 'error' == $valid_status ){
				edd_set_error( 'email_invalid', __( 'Unable to validate email. Email validation request error. Please try again or contact administrator.', 'briteverify-for-wp' ) );
			}
			elseif( 'invalid' == $valid_status ){
				edd_set_error( 'email_invalid', __( 'Email is incorrect.', 'briteverify-for-wp' ) );
			}
			elseif( 'disposable' == $valid_status && ! $allow_dp ){
				edd_set_error( 'email_invalid', __( 'Please use your real email address. You are not allowed to use disposable email in this form.', 'briteverify-for-wp' ) );
			}
		}
	}

}

