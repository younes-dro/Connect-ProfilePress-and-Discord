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

/**
 * Get User's active subscriptions
 *
 * @param INT $user_id The user's id.
 *
 * @return ARRAY|NULL Array of IDs planc Or Null.
 */
function ets_profilepress_get_active_subscriptions( $user_id ) {
	global $wpdb;
	$active_subscriptions_sql = "
	SELECT s.plan_id FROM `{$wpdb->prefix}ppress_subscriptions` s 
	INNER JOIN {$wpdb->prefix}ppress_customers c on c.id = s.customer_id 
	where s.status = 'active' 
	and c.user_id =%d;";
	$plan_ids                 = $wpdb->get_results( $wpdb->prepare( $active_subscriptions_sql, $user_id ) );
	if ( is_array( $plan_ids ) && count( $plan_ids ) > 0 ) {
		return $plan_ids;
	} else {
		return null;
	}

}
/**
 * Return the discord user avatar.
 *
 * @param INT    $discord_user_id The discord usr ID.
 * @param STRING $user_avatar Discord avatar hash value.
 * @param STRING $restrictcontent_discord The html.
 *
 * @return STRING
 */
function ets_profilepress_discord_get_user_avatar( $discord_user_id, $user_avatar, $restrictcontent_discord ) {
	if ( $user_avatar ) {
		$avatar_url               = '<img class="ets-profilepress-user-avatar" src="https://cdn.discordapp.com/avatars/' . $discord_user_id . '/' . $user_avatar . '.png" />';
		$restrictcontent_discord .= $avatar_url;
	}
	return $restrictcontent_discord;
}

/**
 * Get roles assigned messages.
 *
 * @param STRING $mapped_role_name
 * @param STRING $default_role_name
 * @param STRING $restrictcontent_discord
 *
 * @return STRING html.
 */
function ets_profilepress_discord_roles_assigned_message( $mapped_role_name, $default_role_name, $restrictcontent_discord ) {

	if ( $mapped_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';

		$restrictcontent_discord .= esc_html__( 'Following Roles will be assigned to you in Discord: ', 'connect-profilepress-and-discord' );
		$restrictcontent_discord .= $mapped_role_name;
		if ( $default_role_name ) {
			$restrictcontent_discord .= $default_role_name;

		}

		$restrictcontent_discord .= '</p>';
	} elseif ( $default_role_name ) {
		$restrictcontent_discord .= '<p class="ets_assigned_role">';

		$restrictcontent_discord .= esc_html__( 'Following Role will be assigned to you in Discord: ', 'connect-profilepress-and-discord' );
		$restrictcontent_discord .= $default_role_name;

		$restrictcontent_discord .= '</p>';

	}
	return $restrictcontent_discord;
}

/**
 * Get allowed html using WordPress API function wp_kses.
 *
 * @return ARRAY Allowed html.
 */
function ets_profilepress_discord_allowed_html() {
	$allowed_html = array(
		'div'    => array(
			'class' => array(),
		),
		'p'      => array(
			'class' => array(),
		),
		'a'      => array(
			'id'           => array(),
			'data-user-id' => array(),
			'href'         => array(),
			'class'        => array(),
			'style'        => array(),
		),
		'label'  => array(
			'class' => array(),
		),
		'h3'     => array(),
		'span'   => array(
			'class' => array(),
		),
		'i'      => array(
			'style' => array(),
			'class' => array(),
		),
		'button' => array(
			'class'        => array(),
			'data-user-id' => array(),
			'id'           => array(),
		),
		'img'    => array(
			'src'   => array(),
			'class' => array(),
		),
		'h2'     => array(),
	);

	return $allowed_html;
}

/**
 * Get Action data from table `actionscheduler_actions`.
 *
 * @param INT $action_id Action id.
 *
 * @return ARRAY|BOOL
 */
function ets_profilepress_discord_as_get_action_data( $action_id ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.hook, aa.status, aa.args, ag.slug AS as_group FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id=ag.group_id WHERE `action_id`=%d AND ag.slug=%s', $action_id, ETS_PROFILEPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result[0];
	} else {
		return false;
	}
}

/**
 * Get how many times a hook is failed in a particular day.
 *
 * @param STRING $hook
 *
 * @return INT|BOOL
 */
function ets_profilepress_discord_count_of_hooks_failures( $hook ) {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT count(last_attempt_gmt) as hook_failed_count FROM ' . $wpdb->prefix . 'actionscheduler_actions WHERE `hook`=%s AND status="failed" AND DATE(last_attempt_gmt) = %s', $hook, date( 'Y-m-d' ) ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result['0']['hook_failed_count'];
	} else {
		return false;
	}
}

/**
 * Get randon integer between a predefined range.
 *
 * @param INT $add_upon
 *
 * @return INT
 */
function ets_profilepress_discord_get_random_timestamp( $add_upon = '' ) {
	if ( $add_upon != '' && $add_upon !== false ) {
		return $add_upon + random_int( 5, 15 );
	} else {
		return strtotime( 'now' ) + random_int( 5, 15 );
	}
}

/**
 * Get the highest available last attempt schedule time.
 *
 * @return INT|FALSE
 */
function ets_profilepress_discord_get_highest_last_attempt_timestamp() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.last_attempt_gmt FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s ORDER BY aa.last_attempt_gmt DESC limit 1', ETS_PROFILEPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return strtotime( $result['0']['last_attempt_gmt'] );
	} else {
		return false;
	}
}

/**
 * Get pending jobs.
 *
 * @return ARRAY|FALSE
 */
function ets_profilepress_discord_get_all_pending_actions() {
	global $wpdb;
	$result = $wpdb->get_results( $wpdb->prepare( 'SELECT aa.* FROM ' . $wpdb->prefix . 'actionscheduler_actions as aa INNER JOIN ' . $wpdb->prefix . 'actionscheduler_groups as ag ON aa.group_id = ag.group_id WHERE ag.slug = %s AND aa.status="pending" ', ETS_PROFILEPRESS_DISCORD_AS_GROUP_NAME ), ARRAY_A );

	if ( ! empty( $result ) ) {
		return $result['0'];
	} else {
		return false;
	}
}
