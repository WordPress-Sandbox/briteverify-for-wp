<?php
/**
 * GravityForms Add-On Framework
 * @since 1.0.0
 * @link https://www.gravityhelp.com/documentation/article/add-on-framework/
 * @link https://github.com/gravityforms/simpleaddon
 */

/* Include Framework */
GFForms::include_addon_framework();


/**
 * Extend GFAddOn Class
 * @since 1.0.0
 */
class BV4WP_GravityForms extends GFAddOn{

	protected $_version                   = BV4WP_VERSION;
	protected $_min_gravityforms_version  = '2.0';
	protected $_slug                      = 'briteverify-for-wp';
	protected $_path                      = 'briteverify-for-wp/briteverify-for-wp.php';
	protected $_full_path                 = __FILE__;
	protected $_title                     = 'BriteVerify for WP'; // cannot translate this.
	protected $_short_title               = 'BriteVerify'; // cannot translate this.
	private static $_instance             = null;

	/**
	 * Get an instance of this class.
	 * @return BV4WP_GravityForms
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new BV4WP_GravityForms();
		}
		return self::$_instance;
	}


	/* GF INIT HOOK
	------------------------------------------ */

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init() {

		/* Advance Tab */
		add_action( 'gform_field_advanced_settings', array( $this, 'bv4wp_gf_advanced_settings' ), 10, 2 );

		/* Editor JS */
		add_action( 'gform_editor_js', array( $this, 'bv4wp_gf_field_editor_js' ) );

		/* Validation */
		add_filter( 'gform_field_validation', array( $this, 'bv4wp_gf_email_validation' ), 10, 4 );

		/* Scripts */
		add_action( 'gform_enqueue_scripts', array( $this, 'bv4wp_gf_enqueue_scripts' ), 10, 2 );

		parent::init();
	}


	/**
	 * Advanced Settings
	 * @since 1.0.0
	 */
	public function bv4wp_gf_advanced_settings( $position, $form_id ){
		if( 400 === $position ){
			?>
			<li class="bv4wp_gf_enable_field_setting field_setting">
				<label class="section_label"><?php esc_html_e( 'BriteVerify: Enable Verification', 'briteverify-for-wp' ); ?> </label>
				<div>

					<input autocomplete="off" type="radio" name="bv4wp_gf_enable" id="bv4wp_gf_enable_default" size="10" value="" onclick="return SetFieldProperty( 'bv4wp_gf_enable', this.value );" onkeypress="return SetFieldProperty( 'bv4wp_gf_enable', this.value );" />
					<label for="bv4wp_gf_enable_default" class="inline">
						<?php printf( esc_html__( 'Default (%s)', 'briteverify-for-wp' ), bv4wp_gf_option_enable() ? __( 'Enabled', 'briteverify-for-wp' ) : __( 'Disabled', 'briteverify-for-wp' ) ); ?>
					</label>
					&nbsp;&nbsp;

					<input autocomplete="off" type="radio" name="bv4wp_gf_enable" id="bv4wp_gf_enable_yes" size="10" value="yes" onclick="return SetFieldProperty( 'bv4wp_gf_enable', this.value );" onkeypress="return SetFieldProperty( 'bv4wp_gf_enable', this.value );"/>
					<label for="bv4wp_gf_enable_yes" class="inline">
						<?php esc_html_e( 'Enable', 'briteverify-for-wp' ); ?>
					</label>
					&nbsp;&nbsp;

					<input autocomplete="off" type="radio" name="bv4wp_gf_enable" id="bv4wp_gf_enable_no" size="10" value="no" onclick="return SetFieldProperty( 'bv4wp_gf_enable', this.value );" onkeypress="return SetFieldProperty( 'bv4wp_gf_enable', this.value );"/>
					<label for="bv4wp_gf_enable_no" class="inline">
						<?php esc_html_e( 'Disable', 'briteverify-for-wp' ); ?>
					</label>

				</div>
				<br class="clear" />
			</li>
			<li class="bv4wp_gf_allow_disposable_field_setting field_setting">
				<label class="section_label"><?php esc_html_e( 'BriteVerify: Allow Disposable Email', 'briteverify-for-wp' ); ?> </label>
				<div>

					<input autocomplete="off" type="radio" name="bv4wp_gf_allow_disposable" id="bv4wp_gf_allow_disposable_default" size="10" value="" onclick="return SetFieldProperty( 'bv4wp_gf_allow_disposable', this.value );" onkeypress="return SetFieldProperty( 'bv4wp_gf_allow_disposable', this.value );"/>
					<label for="bv4wp_gf_allow_disposable_default" class="inline">
						<?php printf( esc_html__( 'Default (%s)', 'briteverify-for-wp' ), bv4wp_gf_option_allow_disposable() ? __( 'Allow', 'briteverify-for-wp' ) : __( 'Do Not Allow', 'briteverify-for-wp' ) ); ?>
					</label>
					&nbsp;&nbsp;

					<input autocomplete="off" type="radio" name="bv4wp_gf_allow_disposable" id="bv4wp_gf_allow_disposable_yes" size="10" value="yes" onclick="return SetFieldProperty( 'bv4wp_gf_allow_disposable', this.value );" onkeypress="return SetFieldProperty( 'bv4wp_gf_allow_disposable', this.value );"/>
					<label for="bv4wp_gf_allow_disposable_yes" class="inline">
						<?php esc_html_e( 'Allow', 'briteverify-for-wp' ); ?>
					</label>
					&nbsp;&nbsp;

					<input autocomplete="off" type="radio" name="bv4wp_gf_allow_disposable" id="bv4wp_gf_allow_disposable_no" size="10" value="no" onclick="return SetFieldProperty( 'bv4wp_gf_allow_disposable', this.value );" onkeypress="return SetFieldProperty( 'bv4wp_gf_allow_disposable', this.value );"/>
					<label for="bv4wp_gf_allow_disposable_no" class="inline">
						<?php esc_html_e( 'Do Not Allow', 'briteverify-for-wp' ); ?>
					</label>

				</div>
				<br class="clear" />
			</li>
			<?php
		}
	}

	/**
	 * Display Options in Email Field and bind the value to the options.
	 * @since 1.0.0
	 */
	public function bv4wp_gf_field_editor_js(){
		?>
		<script type='text/javascript'>
			/* Display setting only on "email" field type */
			fieldSettings["email"] += ", .bv4wp_gf_enable_field_setting, .bv4wp_gf_allow_disposable_field_setting";

			/* Bind on "load field" to display saved data */
			jQuery(document).bind("gform_load_field_settings", function( event, field, form ){
				if( 'email' != field.type ){
					return;
				}
				/* Enable */
				if( 'yes' == field.bv4wp_gf_enable ){
					jQuery( '#bv4wp_gf_enable_yes' ).prop( 'checked', true );
				}
				else if( 'no' ==  field.bv4wp_gf_enable ){
					jQuery( '#bv4wp_gf_enable_no' ).prop( 'checked', true );
				}
				else{
					jQuery( '#bv4wp_gf_enable_default' ).prop( 'checked', true );
				}
				/* Allow Disposable */
				if( 'yes' == field.bv4wp_gf_allow_disposable ){
					jQuery( '#bv4wp_gf_allow_disposable_yes' ).prop( 'checked', true );
				}
				else if( 'no' ==  field.bv4wp_gf_allow_disposable ){
					jQuery( '#bv4wp_gf_allow_disposable_no' ).prop( 'checked', true );
				}
				else{
					jQuery( '#bv4wp_gf_allow_disposable_default' ).prop( 'checked', true );
				}
			});
		</script>
		<?php
	}


	/**
	 * Email Validation
	 * @since 1.0.0
	 */
	public function bv4wp_gf_email_validation( $result, $value, $form, $field ){

		/* Bail if not email field. */
		if( 'email' != $field->type ){
			return $result;
		}

		/* Bail if no API Key */
		$api_key = bv4wp_api_key();
		if( ! $api_key ){
			return $result;
		}

		/* Allow Disposable Email Option */
		$allow_dp = bv4wp_gf_option_allow_disposable();
		if( 'yes' == $field->bv4wp_gf_allow_disposable ){
			$allow_dp = true;
		}
		elseif( 'no' == $field->bv4wp_gf_allow_disposable ){
			$allow_dp = false;
		}

		/* Check if validation enabled */
		$validate = bv4wp_gf_option_enable();
		if( 'yes' == $field->bv4wp_gf_enable ){
			$validate = true;
		}
		elseif( 'no' == $field->bv4wp_gf_enable ){
			$validate = false;
		}

		/* Validate */
		if( $validate ){
			return $this->validate_email( $result, $value, $allow_dp );
		}

		return $result;
	}


	/* ADMIN FUNCTIONS
	------------------------------------------ */

	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 * @return array
	 */
	public function plugin_settings_fields() {
		$fields = array(
			array(
				'title'  => false,
				'fields' => array(
					array(
						'name'                 => 'enable',
						'tooltip'              => esc_html__( 'This is the default option. You can still disable/enable each feature in email field using advanced field settings.', 'briteverify-for-wp' ),
						'label'                => esc_html__( 'Default options', 'briteverify-for-wp' ),
						'type'                 => 'checkbox',
						'class'                => 'small',
						'choices'              => array(
							'enable'           => array(
								'id'    => 'bv4wp-gf-enable',
								'name'  => 'enable',
								'label' => __( 'Enable in all email fields.', 'briteverify-for-wp' ),
							),
							'allow_disposable' => array(
								'id'    => 'bv4wp-gf-allow_disposable',
								'name'  => 'allow_disposable',
								'label' => __( 'Allow disposable email.', 'briteverify-for-wp' ),
							),
						),
						'feedback_callback' => array( $this, 'validate_bool' ),
					),
				),
			),
		);
		return $fields;
	}


	/* FRONT END
	------------------------------------------ */

	/**
	 * Return the scripts which should be enqueued.
	 * @return array
	 */
	public function bv4wp_gf_enqueue_scripts( $form = '', $is_ajax = false ) {

		/* Global var to store all input fields */
		global $bv4wp_gf_email_fields, $bv4gf_localize_load;
		if( !isset( $bv4wp_gf_email_fields ) ){
			$bv4wp_gf_email_fields = array();
		}

		/* Load Scripts */
		wp_enqueue_script( 'bv4wp-gf', BV4WP_URI . 'assets/gravityforms.js', array( 'jquery' ), BV4WP_VERSION, true );

		/* Add input */
		if( isset( $form['fields'] ) && $form['fields'] && is_array( $form['fields'] ) ){
			foreach( $form['fields'] as $field ){
				if( 'email' == $field['type'] ){

					/* Allow Disposable Email Option */
					$allow_dp = bv4wp_gf_option_allow_disposable();
					if( 'yes' == $field['bv4wp_gf_allow_disposable'] ){
						$allow_dp = true;
						ccdd($field['bv4wp_gf_allow_disposable']);
					}
					elseif( 'no' == $field['bv4wp_gf_allow_disposable'] ){
						$allow_dp = false;
					}

					/* Check if validation enabled */
					$validate = bv4wp_gf_option_enable();
					if( 'yes' == $field['bv4wp_gf_enable'] ){
						$validate = true;
					}
					elseif( 'no' == $field['bv4wp_gf_enable'] ){
						$validate = false;
					}

					/* Input ID */
					$input_id = 'input_' . $field['formId'] . '_' . $field['id'];

					/* Add data */
					if( !isset( $bv4wp_gf_email_fields[$input_id] ) ){
						$bv4wp_gf_email_fields[$input_id] = array(
							'bv4wp_gf_enable'           => $validate,
							'bv4wp_gf_allow_disposable' => $allow_dp,
						);
					}
				}
			}
		}
		/* Do not load twice (?) */
		if( ! isset( $bv4gf_localize_load ) && ! $bv4gf_localize_load ){
			$bv4gf_localize_load = true;
			wp_localize_script( 'bv4wp-gf', 'bv4wp_gf_emails', $bv4wp_gf_email_fields );
		}
	}

	/* HELPERS FUNCTIONS
	------------------------------------------ */

	/**
	 * The feedback callback for the plugin settings page
	 * @param string $value The setting value.
	 * @return bool
	 */
	public function validate_bool( $value ) {
		return $value ? true : false;
	}

	/**
	 * Validate Email
	 * Only use this function after making sure all requirements complete.
	 * @since 1.0.0
	 */
	public function validate_email( $result, $value, $allow_dp ){

		/* Bail if no value */
		if( ! $value ){
			return $result;
		}

		/* Bail if no API Key */
		$api_key = bv4wp_api_key();
		if( ! $api_key ){
			return $result;
		}

		/* Bail if wp is_email() check fail. */
		if( ! is_email( $value ) ){
			return $result;
		}

		/* API URL */
		$url = add_query_arg( array(
			'address' => urlencode( trim( $value ) ),
			'apikey'  => urlencode( trim( $api_key ) ),
		), 'https://bpi.briteverify.com/emails.json' );

		/* Request Check */
		$raw_response = wp_remote_get( esc_url_raw( $url ) );

		/* Request to BriteVerify fail */
		if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
			$result['is_valid'] = false;
			$result['message'] = __( 'Unable to validate email. Email validation request error. Please try again or contact administrator.', 'briteverify-for-wp' );
			return $result;
		}

		/* JSON Data Result */
		$data = json_decode( trim( wp_remote_retrieve_body( $raw_response ) ), true );

		/* Check status */
		if( isset( $data['status'] ) ){

			/* Email is valid */
			if( 'valid' == $data['status'] ){
				$result['is_valid'] = true;
				$result['message'] = '';

				/* If do not allow disposable and email is disposable, return error */
				if( ! $allow_dp && isset( $data['disposable'] ) && true == $data['disposable'] ){
					$result['is_valid'] = false;
					$result['message'] = __( 'Please use your real email address. You are not allowed to use disposable email in this form.', 'briteverify-for-wp' );
				}
			}
			/* Email not valid */
			else{
				$result['is_valid'] = false;
				$result['message'] = __( 'Email is incorrect.', 'briteverify-for-wp' );
			}
		}

		/* No status data found (invalid return value) */
		else{
			$result['is_valid'] = false;
			$result['message'] = __( 'Unable to validate email. Invalid validation API results. Please try again or contact administrator.', 'briteverify-for-wp' );
		}
		return $result;
	}

}