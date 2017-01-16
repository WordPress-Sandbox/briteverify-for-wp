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
		//$option = get_option( 'gravityformsaddon_briteverify-for-wp_settings' );
		//ccdd($option);
		parent::init();
	}


	/* ADMIN FUNCTIONS
	------------------------------------------ */

	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 * @return array
	 */
	public function plugin_settings_fields() {
		return array(
			array(
				'title'  => esc_html__( 'Simple Add-On Settings', 'simpleaddon' ),
				'fields' => array(
					array(
						'name'              => 'mytextbox',
						'tooltip'           => esc_html__( 'This is the tooltip', 'simpleaddon' ),
						'label'             => esc_html__( 'This is the label', 'simpleaddon' ),
						'type'              => 'text',
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
					)
				)
			)
		);
	}


	/* HELPERS FUNCTIONS
	------------------------------------------ */

	/**
	 * The feedback callback for the plugin settings page
	 * @param string $value The setting value.
	 * @return bool
	 */
	public function is_valid_setting( $value ) {
		return $value;
	}

}