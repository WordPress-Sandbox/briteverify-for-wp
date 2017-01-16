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

}