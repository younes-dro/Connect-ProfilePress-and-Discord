<?php

/**
 * To check settings values saved or not
 *
 * @param NONE
 * @return BOOL $status
 */
function ets_profilepress_discord_check_saved_settings_status() {
	$ets_profilepress_discord_client_id     = get_option( 'ets_profilepress_discord_client_id' );
	$ets_profilepress_discord_client_secret = get_option( 'ets_profilepress_discord_client_secret' );
	$ets_profilepress_discord_bot_token     = get_option( 'ets_profilepress_discord_bot_token' );
	$ets_profilepress_discord_redirect_url  = get_option( 'ets_profilepress_discord_redirect_url' );
	$ets_profilepress_discord_server_id     = get_option( 'ets_profilepress_discord_server_id' );

	if ( $ets_profilepress_discord_client_id && $ets_profilepress_discord_client_secret && $ets_profilepress_discord_bot_token && $ets_profilepress_discord_redirect_url && $ets_profilepress_discord_server_id ) {
			$status = true;
	} else {
		$status = false;
	}

		return $status;
}

/**
 * Get WP pages list.
 *
 * @param INT $ets_profilepress_discord_redirect_page_id The Page ID.
 *
 * @return STRING $options Html select options.
 */
function ets_profilepress_discord_pages_list( $ets_profilepress_discord_redirect_page_id ) {
	$args    = array(
		'sort_order'   => 'asc',
		'sort_column'  => 'post_title',
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'exclude_tree' => '',
		'number'       => '',
		'offset'       => 0,
		'post_type'    => 'page',
		'post_status'  => 'publish',
	);
	$pages   = get_pages( $args );
	$options = '<option value="">-</option>';
	foreach ( $pages as $page ) {
		$selected = ( esc_attr( $page->ID ) === $ets_profilepress_discord_redirect_page_id ) ? ' selected="selected"' : '';
		$options .= '<option data-page-url="' . ets_get_profilepress_discord_formated_discord_redirect_url( $page->ID ) . '" value="' . esc_attr( $page->ID ) . '" ' . $selected . '> ' . sanitize_text_field( $page->post_title ) . ' </option>';
	}

	return $options;
}

/**
 * Get formated redirect url.
 *
 * @param INT $page_id
 *
 * @return STRING $url
 */
function ets_get_profilepress_discord_formated_discord_redirect_url( $page_id ) {
	$url = esc_url( get_permalink( $page_id ) );

	$parsed = parse_url( $url, PHP_URL_QUERY );
	if ( $parsed === null ) {
		return $url .= '?via=connect-profilepress-discord-addon';
	} else {
		if ( stristr( $url, 'via=connect-profilepress-discord-addon' ) !== false ) {
			return $url;
		} else {
			return $url .= '&via=connect-profilepress-discord-addon';
		}
	}
}

/**
 * Get current screen URL
 *
 * @param NONE
 * @return STRING $url
 */
function ets_profilepress_discord_get_current_screen_url() {
	$parts       = parse_url( home_url() );
	$current_uri = "{$parts['scheme']}://{$parts['host']}" . ( isset( $parts['port'] ) ? ':' . $parts['port'] : '' ) . add_query_arg( null, null );

		return $current_uri;
}
/**
 * Save the BOT name in options table.
 */
function ets_profilepress_discord_update_bot_name_option() {

	$guild_id          = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_server_id' ) ) );
	$discord_bot_token = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_bot_token' ) ) );
	if ( $guild_id && $discord_bot_token ) {

		$discod_current_user_api = ETS_PROFILEPRESS_DISCORD_API_URL . 'users/@me';

		$app_args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bot ' . $discord_bot_token,
			),
		);

		$app_response = wp_remote_post( $discod_current_user_api, $app_args );

		$response_arr = json_decode( wp_remote_retrieve_body( $app_response ), true );

		if ( is_array( $response_arr ) && array_key_exists( 'username', $response_arr ) ) {

			update_option( 'ets_profilepress_discord_connected_bot_name', $response_arr ['username'] );
		} else {
			delete_option( 'ets_profilepress_discord_connected_bot_name' );
		}
	}

}

/**
 * Get Active Plans.
 *
 * @return ARRAY List of active plans.
 */
function ets_profilepress_discord_get_active_plans() {
	global $wpdb;
	$plans_table = $wpdb->prefix . 'ppress_plans';
	$sql         = "SELECT * FROM {$plans_table}";
	$sql        .= ' WHERE status=%s';
	$sql        .= ' ORDER BY id DESC';

	$result = $wpdb->get_results( $wpdb->prepare( $sql, 'true' ), 'ARRAY_A' );

	if ( is_array( $result ) && ! empty( $result ) ) {
		return $result;
	}

	return array();
}

/**
 * Log API call response.
 *
 * @param INT          $user_id
 * @param STRING       $api_url
 * @param ARRAY        $api_args
 * @param ARRAY|OBJECT $api_response
 */
function ets_profilepress_discord_log_api_response( $user_id, $api_url = '', $api_args = array(), $api_response = '' ) {
	$log_api_response = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_log_api_response' ) ) );
	if ( $log_api_response == true ) {
		$log_string  = '==>' . $api_url;
		$log_string .= '-::-' . serialize( $api_args );
		$log_string .= '-::-' . serialize( $api_response );

		$logs = new Connect_Profilepress_And_Discord_Logs();
		$logs->write_api_response_logs( $log_string, $user_id );
	}
}
