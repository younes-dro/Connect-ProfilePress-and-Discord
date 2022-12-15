<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/admin
 * @author     ExpressTech Software Solutions Pvt. Ltd. <contact@expresstechsoftwares.com>
 */
class Connect_Profilepress_And_Discord_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Profilepress_And_Discord_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Profilepress_And_Discord_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$min_css = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_style( $this->plugin_name . 'skeletabs.css', plugin_dir_url( __FILE__ ) . 'css/skeletabs.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . 'select2.css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/connect-profilepress-and-discord-admin' . $min_css . '.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Connect_Profilepress_And_Discord_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Connect_Profilepress_And_Discord_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$min_js = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? '' : '.min';
		wp_register_script( $this->plugin_name . 'select2.js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name . 'skeletabs.js', plugin_dir_url( __FILE__ ) . 'js/skeletabs.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/connect-profilepress-and-discord-admin' . $min_js . '.js', array( 'jquery' ), $this->version, false );
		$script_params = array(
			'admin_ajax'                     => admin_url( 'admin-ajax.php' ),
			'permissions_const'              => ETS_PROFILEPRESS_DISCORD_BOT_PERMISSIONS,
			'is_admin'                       => is_admin(),
			'ets_profilepress_discord_nonce' => wp_create_nonce( 'ets-profilepress-discord-ajax-nonce' ),
		);
		wp_localize_script( $this->plugin_name, 'ets_profilepress_js_params', $script_params );

	}

	/**
	 * Method to add Discord Setting sub-menu under top level menu of Profile Press.
	 *
	 * @since 1.0.0
	 */
	public function ets_ppress_discord_add_settings_menu() {
		add_submenu_page( PPRESS_DASHBOARD_SETTINGS_SLUG, esc_html__( 'Discord Settings', 'connect-profilepress-and-discord' ), esc_html__( 'Discord Settings', 'connect-profilepress-and-discord' ), 'manage_options', 'connect-profilepress-and-discord', array( $this, 'ets_ppress_discord_setting_page' ) );

	}

	/**
	 * Callback to Display Discord Settings page.
	 *
	 * @since 1.0.0
	 */
	public function ets_ppress_discord_setting_page() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_send_json_error( 'You do not have sufficient rights', 403 );
			exit();
		}
		wp_enqueue_style( $this->plugin_name . 'skeletabs.css' );
		wp_enqueue_style( $this->plugin_name . 'select2.css' );
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-droppable' );
		wp_enqueue_script( $this->plugin_name . 'select2.js' );
		wp_enqueue_script( $this->plugin_name . 'skeletabs.js' );
		wp_enqueue_script( $this->plugin_name );
		require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/connect-profilepress-and-discord-admin-display.php';
	}

}
