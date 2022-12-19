<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/public
 * @author     ExpressTech Software Solutions Pvt. Ltd. <contact@expresstechsoftwares.com>
 */
class Connect_Profilepress_And_Discord_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/connect-profilepress-and-discord-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/connect-profilepress-and-discord-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 *
	 * Build the html connect / disconnect button.
	 *
	 * @param NONE
	 * @return SRING
	 */
	public function ets_profilepress_discord_add_connect_button() {

		$user_id = sanitize_text_field( trim( get_current_user_id() ) );

		$access_token                                        = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_profilepress_discord_access_token', true ) ) );
		$_ets_profilepress_discord_username                  = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_profilepress_discord_username', true ) ) );
		$ets_profilepress_discord_connect_button_bg_color    = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_connect_button_bg_color' ) ) );
		$ets_profilepress_discord_disconnect_button_bg_color = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_disconnect_button_bg_color' ) ) );
		$ets_profilepress_discord_disconnect_button_text     = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_disconnect_button_text' ) ) );
		$ets_profilepress_discord_loggedin_button_text       = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_loggedin_button_text' ) ) );
		$default_role                                        = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_default_role_id' ) ) );
		$ets_profilepress_discord_role_mapping               = json_decode( get_option( 'ets_profilepress_discord_role_mapping' ), true );
		$all_roles          = unserialize( get_option( 'ets_profilepress_discord_all_roles' ) );
		$roles_color        = unserialize( get_option( 'ets_profilepress_discord_roles_color' ) );
		$user_subscriptions = ets_profilepress_get_active_subscriptions( $user_id );

		$mapped_role_name = '';
		if ( is_array( $user_subscriptions ) && is_array( $all_roles ) && is_array( $ets_profilepress_discord_role_mapping ) ) {
			foreach ( $user_subscriptions as $user_subscription ) {
				if ( array_key_exists( 'profilepress_plan_id_' . $user_subscription->plan_id, $ets_profilepress_discord_role_mapping ) ) {

					$mapped_role_id = $ets_profilepress_discord_role_mapping[ 'profilepress_plan_id_' . $user_subscription->plan_id ];

					if ( array_key_exists( $mapped_role_id, $all_roles ) ) {
						$mapped_role_name .= '<span> <i style="background-color:#' . dechex( $roles_color[ $mapped_role_id ] ) . '"></i>' . $all_roles[ $mapped_role_id ] . '</span>';
					}
				}
			}
		}

		$default_role_name = '';
		if ( is_array( $all_roles ) ) {
			if ( $default_role != 'none' && array_key_exists( $default_role, $all_roles ) ) {
				$default_role_name = '<span><i style="background-color:#' . dechex( $roles_color[ $default_role ] ) . '"></i> ' . $all_roles[ $default_role ] . '</span>';
			}
		}

		$restrictcontent_discord = '';
		if ( ets_profilepress_discord_check_saved_settings_status() ) {

			if ( $access_token ) {
				$discord_user_id     = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_profilepress_discord_user_id', true ) ) );
				$discord_user_avatar = sanitize_text_field( trim( get_user_meta( $user_id, '_ets_profilepress_discord_avatar', true ) ) );

				$disconnect_btn_bg_color  = 'style="background-color:' . $ets_profilepress_discord_disconnect_button_bg_color . '"';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<h2>' . esc_html__( 'Discord connection', 'connect-profilepress-and-discord' ) . '</h2>';
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<a href="#" class="ets-btn profilepress-discord-btn-disconnect" ' . $disconnect_btn_bg_color . ' id="profilepress-discord-disconnect-discord" data-user-id="' . esc_attr( $user_id ) . '">' . esc_html( $ets_profilepress_discord_disconnect_button_text ) . Connect_Profilepress_And_Discord::get_discord_logo_white() . '</a>';
				$restrictcontent_discord .= '<span class="ets-spinner"></span>';
				$restrictcontent_discord .= '<p>' . esc_html__( sprintf( 'Connected account: %s', $_ets_profilepress_discord_username ), 'connect-profilepress-and-discord' ) . '</p>';
				$restrictcontent_discord  = ets_profilepress_discord_get_user_avatar( $discord_user_id, $discord_user_avatar, $restrictcontent_discord );
				$restrictcontent_discord  = ets_profilepress_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord );
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord .= '</div>';

			} elseif ( ( ets_profilepress_discord_get_active_plans( $user_id ) && $mapped_role_name )
								|| ( ets_profilepress_discord_get_active_plans( $user_id ) && ! $mapped_role_name && $default_role_name )
								) {

				$connect_btn_bg_color     = 'style="background-color:' . $ets_profilepress_discord_connect_button_bg_color . '"';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<h3>' . esc_html__( 'Discord connection', 'connect-profilepress-and-discord' ) . '</h3>';
				$restrictcontent_discord .= '<div>';
				$restrictcontent_discord .= '<a href="?action=ets-profilepress-discord-login" class="profilepress-discord-btn-connect ets-btn" ' . $connect_btn_bg_color . ' >' . esc_html( $ets_profilepress_discord_loggedin_button_text ) . Connect_Profilepress_And_Discord::get_discord_logo_white() . '</a>';
				$restrictcontent_discord .= '</div>';
				$restrictcontent_discord  = ets_profilepress_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord );

				$restrictcontent_discord .= '</div>';

			}
		}
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );

		return $restrictcontent_discord;
	}

	/**
	 * Add button to make connection in between user and discord server.
	 *
	 * The button is displayed in Account Details section.
	 *
	 * @param NONE
	 * @return STRING
	 */
	public function ets_profilepress_discord_connect_button() {
		_e( wp_kses( do_shortcode( '[ets_ppress_discord]' ), ets_profilepress_discord_allowed_html() ) );
	}

	/**
	 * Allow data protocol.
	 *
	 * @param ARRAY $protocols Array of allowed protocols.
	 * @since    1.0.0
	 * @return ARRAY
	 */
	public function ets_profilepress_discord_allow_data_protocol( $protocols ) {

		$protocols[] = 'data';
		return $protocols;
	}

}
