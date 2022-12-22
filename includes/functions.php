<?php

use ProfilePress\Core\Membership\Models\Plan\PlanFactory;
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
 * Check API call response and detect conditions which can cause of action failure and retry should be attemped.
 *
 * @param ARRAY|OBJECT $api_response The API resposne.
 * @param BOOLEAN
 */
function ets_profilepress_discord_check_api_errors( $api_response ) {
	// check if response code is a WordPress error.
	if ( is_wp_error( $api_response ) ) {
		return true;
	}

	// First Check if response contain codes which should not get re-try.
	$body = json_decode( wp_remote_retrieve_body( $api_response ), true );
	if ( isset( $body['code'] ) && in_array( $body['code'], ETS_PROFILEPRESS_DISCORD_DONOT_RETRY_THESE_API_CODES ) ) {
		return false;
	}

	$response_code = strval( $api_response['response']['code'] );
	if ( isset( $api_response['response']['code'] ) && in_array( $response_code, ETS_PROFILEPRESS_DISCORD_DONOT_RETRY_HTTP_CODES ) ) {
		return false;
	}

	// check if response code is in the range of HTTP error.
	if ( ( 400 <= absint( $response_code ) ) && ( absint( $response_code ) <= 599 ) ) {
		return true;
	}
}

/**
 * Get User's active subscriptions
 *
 * Return List of plan ids for user.
 *
 * @param INT $user_id The user's id.
 *
 * @return ARRAY|NULL Array of Plan IDs Or Null.
 */
function ets_profilepress_get_active_subscriptions( $user_id ) {
	global $wpdb;
	$active_subscriptions_sql = "
	SELECT s.plan_id FROM `{$wpdb->prefix}ppress_subscriptions` s 
	INNER JOIN {$wpdb->prefix}ppress_customers c on c.id = s.customer_id 
	where ( s.status = 'active' OR s.status= 'completed' ) 
	and c.user_id =%d;";
	$plan_ids                 = $wpdb->get_results( $wpdb->prepare( $active_subscriptions_sql, $user_id ) );
	if ( is_array( $plan_ids ) && count( $plan_ids ) > 0 ) {
		return $plan_ids;
	} else {
		return null;
	}

}

/**
 * Get Plans name for customer.
 *
 * @param INT $user_id
 */
function ets_profilepress_discord_get_plans_name( $user_id ) {

	global $wpdb;

	$plans_name_sql = " SELECT p.name FROM {$wpdb->prefix}ppress_subscriptions s 
	INNER JOIN {$wpdb->prefix}ppress_customers c on c.id = s.customer_id 
    INNER JOIN {$wpdb->prefix}ppress_plans p on p.id = s.plan_id
	where ( s.status = 'active' OR s.status= 'completed' ) 
	and c.user_id =%d;";
	$plan_names     = $wpdb->get_results( $wpdb->prepare( $plans_name_sql, $user_id ) );
	if ( is_array( $plan_names ) && count( $plan_names ) > 0 ) {
		return $plan_names;
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
		'strong' => array(),
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

/**
 * Get customer's roles ids
 *
 * @param INT $user_id
 * @return ARRAY|NULL $roles
 */
function ets_profilepress_discord_get_user_roles( $user_id ) {
	global $wpdb;

	$usermeta_table     = $wpdb->prefix . 'usermeta';
	$user_roles_sql     = 'SELECT * FROM ' . $usermeta_table . " WHERE `user_id` = %d AND ( `meta_key` like '_ets_profilepress_discord_role_id_for_%' OR `meta_key` = 'ets_profilepress_discord_default_role_id' OR `meta_key` = '_ets_profilepress_discord_last_default_role' ); ";
	$user_roles_prepare = $wpdb->prepare( $user_roles_sql, $user_id );

	$user_roles = $wpdb->get_results( $user_roles_prepare, ARRAY_A );

	if ( is_array( $user_roles ) && count( $user_roles ) ) {
		$roles = array();
		foreach ( $user_roles as  $role ) {

			array_push( $roles, $role['meta_value'] );
		}

		return $roles;

	} else {
		return null;
	}
}

/**
 * Remove all usermeta created by this plugin.
 *
 * @param INT $user_id The User's id.
 */
function ets_profilepress_discord_remove_usermeta( $user_id ) {

	global $wpdb;

	$usermeta_table      = $wpdb->prefix . 'usermeta';
	$usermeta_sql        = 'DELETE FROM ' . $usermeta_table . " WHERE `user_id` = %d AND  `meta_key` LIKE '_ets_profilepress_discord%'; ";
	$delete_usermeta_sql = $wpdb->prepare( $usermeta_sql, $user_id );
	$wpdb->query( $delete_usermeta_sql );
}

/**
 * Get the user's Id form customer id.
 *
 * @param INT $customer_id The customer id.
 * @return INT|NULL
 */
function ets_profilepress_discord_get_user_id( $customer_id ) {

	global $wpdb;

	$customer_table  = $wpdb->prefix . 'ppress_customers';
	$user_id_sql     = 'SELECT `user_id` FROM ' . $customer_table . ' WHERE `id` = %d';
	$user_id_prepare = $wpdb->prepare( $user_id_sql, $customer_id );
	$user_id_result  = $wpdb->get_results( $user_id_prepare, ARRAY_A );

	if ( is_array( $user_id_result ) && count( $user_id_result ) > 0 ) {
		return (int) $user_id_result[0]['user_id'];
	} else {
		return null;
	}
}

/**
 * Get formatted message to send in DM.
 *
 * @param INT    $user_id The user ID.
 * @param STRING $message The formatted message to send to discord.
 * Merge fields: [PPRESS_USER_NAME], [PPRESS_USER_EMAIL], [PPRESS_PLANS], [SITE_URL], [BLOG_NAME].
 */
function ets_profilepress_discord_get_formatted_welcome_dm( $user_id, $message ) {

	$user_obj   = get_user_by( 'id', $user_id );
	$USERNAME   = sanitize_text_field( $user_obj->user_login );
	$USER_EMAIL = sanitize_email( $user_obj->user_email );
	$SITE_URL   = esc_url( get_bloginfo( 'url' ) );
	$BLOG_NAME  = sanitize_text_field( get_bloginfo( 'name' ) );

	$plans = ets_profilepress_discord_get_plans_name( $user_id );
	if ( is_array( $plans ) && count( $plans ) > 0 ) {
		$PLANS_NAMES = '';
		$lastKey    = array_key_last( $plans );
		$commas     = ', ';
		foreach( $plans as $key=> $plan ) {
			if ( $lastKey === $key ) {
				$commas = ' ';
			}
			$PLANS_NAMES .= sanitize_text_field( $plan->name ) . $commas;
		}
	}

	$find    = array(
			'[PPRESS_PLANS]',
			'[PPRESS_USER_NAME]',
			'[PPRESS_USER_EMAIL]',
			'[SITE_URL]',
			'[BLOG_NAME]',
		);
	$replace = array(
			$PLANS_NAMES,
			$USERNAME,
			$USER_EMAIL,
			$SITE_URL,
			$BLOG_NAME,
	);

	return str_replace( $find, $replace, $message );

}

/**
 * Get formatted purchase message to send in DM.
 *
 * @param INT    $user_id The user ID.
 * @param INT $plan_purchased_id The plan's ID.
 * @param STRING $message The formatted message to send to discord.
 * Merge fields: [PPRESS_USER_NAME], [PPRESS_USER_EMAIL], [PPRESS_PLAN], [SITE_URL], [BLOG_NAME].
 */
function ets_profilepress_discord_get_formatted_purchase_dm( $user_id, $plan_purchased_id, $message ) {


	$user_obj   = get_user_by( 'id', $user_id );
	$USERNAME   = sanitize_text_field( $user_obj->user_login );
	$USER_EMAIL = sanitize_email( $user_obj->user_email );
	$SITE_URL   = esc_url( get_bloginfo( 'url' ) );
	$BLOG_NAME  = sanitize_text_field( get_bloginfo( 'name' ) );

	$PLAN_NAME = PlanFactory::fromId( $plan_purchased_id)->get_name();

	$find    = array(
			'[PPRESS_PLAN]',
			'[PPRESS_USER_NAME]',
			'[PPRESS_USER_EMAIL]',
			'[SITE_URL]',
			'[BLOG_NAME]',
		);
	$replace = array(
			$PLAN_NAME,
			$USERNAME,
			$USER_EMAIL,
			$SITE_URL,
			$BLOG_NAME,
	);

	return str_replace( $find, $replace, $message );

}

/**
 * Get formatted cancelled subsription message to send in DM.
 *
 * @param INT    $user_id The user ID.
 * @param INT $subscription_id The subcription id.
 * @param STRING $message The formatted message to send to discord.
 * Merge fields: [PPRESS_USER_NAME], [PPRESS_USER_EMAIL], [PPRESS_PLAN], [SITE_URL], [BLOG_NAME].
 */
function ets_profilepress_discord_get_formatted_cancelled_dm( $user_id, $subscription_id, $message ) {


	$user_obj   = get_user_by( 'id', $user_id );
	$USERNAME   = sanitize_text_field( $user_obj->user_login );
	$USER_EMAIL = sanitize_email( $user_obj->user_email );
	$SITE_URL   = esc_url( get_bloginfo( 'url' ) );
	$BLOG_NAME  = sanitize_text_field( get_bloginfo( 'name' ) );

	$PLAN_NAME = PlanFactory::fromId($subscription_id)->get_name();
	//$PLAN_NAME = $subscription_id;

	$find    = array(
			'[PPRESS_PLAN]',
			'[PPRESS_USER_NAME]',
			'[PPRESS_USER_EMAIL]',
			'[SITE_URL]',
			'[BLOG_NAME]',
		);
	$replace = array(
			$PLAN_NAME,
			$USERNAME,
			$USER_EMAIL,
			$SITE_URL,
			$BLOG_NAME,
	);

	return str_replace( $find, $replace, $message );

}
