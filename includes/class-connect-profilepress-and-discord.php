<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/includes
 * @author     ExpressTech Software Solutions Pvt. Ltd. <contact@expresstechsoftwares.com>
 */
class Connect_Profilepress_And_Discord {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Connect_Profilepress_And_Discord_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CONNECT_PROFILEPRESS_AND_DISCORD_VERSION' ) ) {
			$this->version = CONNECT_PROFILEPRESS_AND_DISCORD_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'connect-profilepress-and-discord';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Connect_Profilepress_And_Discord_Loader. Orchestrates the hooks of the plugin.
	 * - Connect_Profilepress_And_Discord_i18n. Defines internationalization functionality.
	 * - Connect_Profilepress_And_Discord_Admin. Defines all hooks for the admin area.
	 * - Connect_Profilepress_And_Discord_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for Logs
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-connect-profilepress-and-discord-logs.php';

		/**
		 * Common functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-connect-profilepress-and-discord-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-connect-profilepress-and-discord-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-connect-profilepress-and-discord-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-connect-profilepress-and-discord-public.php';

		$this->loader = new Connect_Profilepress_And_Discord_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Connect_Profilepress_And_Discord_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Connect_Profilepress_And_Discord_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Connect_Profilepress_And_Discord_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ets_ppress_discord_add_settings_menu', 99 );
		$this->loader->add_action( 'admin_post_profilepress_discord_application_settings', $plugin_admin, 'ets_profilepress_discord_application_settings' );
		$this->loader->add_action( 'wp_ajax_ets_profilepress_discord_update_redirect_url', $plugin_admin, 'ets_profilepress_discord_update_redirect_url' );
		$this->loader->add_action( 'wp_ajax_ets_profilepress_discord_load_discord_roles', $plugin_admin, 'ets_profilepress_discord_load_discord_roles' );
		$this->loader->add_action( 'admin_post_profilepress_discord_save_role_mapping', $plugin_admin, 'ets_profilepress_discord_save_role_mapping' );
		$this->loader->add_action( 'admin_post_profilepress_discord_save_advance_settings', $plugin_admin, 'ets_profilepress_discord_save_advance_settings' );
		$this->loader->add_action( 'admin_post_profilepress_discord_save_appearance_settings', $plugin_admin, 'ets_profilepress_discord_save_appearance_settings' );
		$this->loader->add_action( 'admin_post_profilepress_discord_send_support_mail', $plugin_admin, 'ets_profilepress_discord_send_support_mail' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Connect_Profilepress_And_Discord_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Discord Logo
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return STRING
	 */
	public static function get_discord_logo_white() {
		$img  = file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'public/images/discord-logo-white.svg' );
		$data = base64_encode( $img );

		return '<img class="ets-discord-logo-white" src="data:image/svg+xml;base64,' . $data . '" />';
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Connect_Profilepress_And_Discord_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
