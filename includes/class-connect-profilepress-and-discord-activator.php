<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/includes
 * @author     ExpressTech Software Solutions Pvt. Ltd. <contact@expresstechsoftwares.com>
 */
class Connect_Profilepress_And_Discord_Activator {

	/**
	 * Save default values on activation the plugin.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option( 'ets_profilepress_discord_send_welcome_dm', true );
		update_option( 'ets_profilepress_discord_welcome_message', 'Hi [PPRESS_USER_NAME] ([PPRESS_USER_EMAIL]), Welcome, Your plans [PPRESS_PLANS] at [SITE_URL] Thanks, Kind Regards, [BLOG_NAME]' );
		update_option( 'ets_profilepress_discord_send_purchase_dm', true );
		update_option( 'ets_profilepress_discord_purchase_message', 'Hi [PPRESS_USER_NAME] ([PPRESS_USER_EMAIL]), Thank You for Your Purchase: [PPRESS_PLAN] at [SITE_URL] Thanks, Kind Regards, [BLOG_NAME]' );
		update_option( 'ets_profilepress_discord_send_cancelled_dm', true );
		update_option( 'ets_profilepress_discord_cancelled_message', 'Hi [PPRESS_USER_NAME] ([PPRESS_USER_EMAIL]), Your subscription for [PPRESS_PLAN] has been cancelled at [SITE_URL] Thanks, Kind Regards, [BLOG_NAME]' );
		update_option( 'ets_profilepress_discord_retry_failed_api', true );
		update_option( 'ets_profilepress_discord_connect_button_bg_color', '#7bbc36' );
		update_option( 'ets_profilepress_discord_disconnect_button_bg_color', '#ff0000' );
		update_option( 'ets_profilepress_discord_loggedin_button_text', 'Connect With Discord' );
		update_option( 'ets_profilepress_discord_non_login_button_text', 'Login With Discord' );
		update_option( 'ets_profilepress_discord_disconnect_button_text', 'Disconnect From Discord' );
		update_option( 'ets_profilepress_discord_kick_upon_disconnect', false );
		update_option( 'ets_profilepress_discord_retry_api_count', 5 );
		update_option( 'ets_profilepress_discord_job_queue_concurrency', 1 );
		update_option( 'ets_profilepress_discord_job_queue_batch_size', 6 );
		update_option( 'ets_profilepress_discord_log_api_response', false );
		update_option( 'ets_profilepress_discord_uuid_file_name', wp_generate_uuid4() );
	}

}
