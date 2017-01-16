<?php
if ( ! defined( 'WPINC' ) ) { die; }
BV4WP_Settings::get_instance();

/**
 * Settings
 * @since 1.0.0
 */
class BV4WP_Settings{

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

		/* Vars */
		$this->settings_slug = 'bv4wp';
		$this->hook_suffix   = 'settings_page_bv4wp';
		$this->options_group = 'bv4wp';
		$this->option_name   = 'bv4wp_api_key';

		/* Create Settings Page */
		add_action( 'admin_menu', array( $this, 'create_settings_page' ) );

		/* Register Settings and Fields */
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		/* Settings Scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}


	/**
	 * Create Settings Page
	 * @since 1.0.0
	 */
	public function create_settings_page(){

		/* Create Settings Sub-Menu */
		add_options_page(
			$page_title  = __( 'BriteVerify for WP Settings', 'briteverify-for-wp' ),
			$menu_title  = __( 'BriteVerify', 'briteverify-for-wp' ),
			$capability  = 'manage_options',
			$menu_slug   = $this->settings_slug,
			$function    = array( $this, 'settings_page' )
		);
	}

	/**
	 * Settings Page Output
	 * @since 1.0.0
	 */
	public function settings_page(){
		?>
		<div class="wrap">
			<h1><?php _e( 'BriteVerify for WP Settings', 'briteverify-for-wp' ); ?></h1>
			<form method="post" action="options.php">
				<?php do_settings_sections( $this->settings_slug ); ?>
				<?php settings_fields( $this->options_group ); ?>
				<?php submit_button(); ?>
			</form>
		</div><!-- wrap -->
		<?php
	}


	/**
	 * Register Settings
	 * @since 0.1.0
	 */
	public function register_settings(){

		/* Register settings */
		register_setting(
			$option_group      = $this->options_group,
			$option_name       = $this->option_name,
			$sanitize_callback = array( $this, 'sanitize' )
		);

		/* Create settings section */
		add_settings_section(
			$section_id        = 'bv4wp_section_api',
			$section_title     = false,
			$callback_function = array( $this, 'settings_section_api' ),
			$settings_slug     = $this->settings_slug
		);

		/* Create Setting Field: Boxes, Buttons, Columns */
		add_settings_field(
			$field_id          = 'bv4wp_field_api_key',
			$field_title       = '<label for="bv4wp_api_key">' . __( 'API Key', 'briteverify-for-wp' ) . '</label>',
			$callback_function = array( $this, 'settings_field_api_key' ),
			$settings_slug     = $this->settings_slug,
			$section_id        = 'bv4wp_section_api'
		);

		/* Create settings section */
		add_settings_section(
			$section_id        = 'bv4wp_section_plugins',
			$section_title     = __( 'Supported Plugins', 'briteverify-for-wp' ),
			$callback_function = '__return_false',
			$settings_slug     = $this->settings_slug
		);

	}

	/**
	 * Settings Section: API
	 * @since 1.0.0
	 * 
	 */
	public function settings_section_api(){
		?>
		<p><?php _e( 'BriteVerify is an email verification service to make sure each email field submission is  valid email address. <a href="http://www.briteverify.com/" target="_blank">Read more about BriteVerify</a>.', 'briteverify-for-wp' ); ?></p>
		<?php
	}

	/**
	 * Settings Field: API Key
	 * @since 1.0.0
	 */
	public function settings_field_api_key(){
		?>
		<p>
			<input id="bv4wp_api_key" type="text" name="bv4wp_api_key" class="regular-text" value="<?php echo sanitize_text_field( strip_tags( trim( get_option( $this->option_name ) ) ) ); ?>">
		</p>
		<p class="description">
			<?php _e( 'API Key to connect to BriteVerify Real-Time API.', 'fx-base' ); ?>
		</p>
		<?php
	}


	/**
	 * Sanitize Options
	 * @since 1.0.0
	 */
	public function sanitize( $data ){
		return sanitize_text_field( strip_tags( trim( $data ) ) );
	}


	/**
	 * Settings Scripts
	 * @since 1.0.0
	 */
	public function scripts( $hook_suffix ){

		/* Only load in settings page. */
		if ( $this->hook_suffix == $hook_suffix ){

			/* CSS */
			wp_enqueue_style( "{$this->settings_slug}_settings", BV4WP_URI . 'assets/settings.css', array(), BV4WP_VERSION );

			/* JS */
			wp_enqueue_script( "{$this->settings_slug}_settings", BV4WP_URI . 'assets/settings.js', array( 'jquery' ), BV4WP_VERSION, true );
		}
	}
}

