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
