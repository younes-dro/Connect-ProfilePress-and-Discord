<?php

$ets_profilepress_discord_send_welcome_dm = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_send_welcome_dm' ) ) );
$ets_profilepress_discord_welcome_message = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_welcome_message' ) ) );

$ets_profilepress_discord_send_purchase_dm = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_send_purchase_dm' ) ) );
$ets_profilepress_discord_purchase_message = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_purchase_message' ) ) );

$ets_profilepress_discord_send_cancelled_dm = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_send_cancelled_dm' ) ) );
$ets_profilepress_discord_cancelled_message = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_cancelled_message' ) ) );

$retry_failed_api     = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_retry_failed_api' ) ) );
$kick_upon_disconnect = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_kick_upon_disconnect' ) ) );
$retry_api_count      = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_retry_api_count' ) ) );
$set_job_cnrc         = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_job_queue_concurrency' ) ) );
$set_job_q_batch_size = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_job_queue_batch_size' ) ) );
$log_api_res          = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_log_api_response' ) ) );

?>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
 <input type="hidden" name="action" value="profilepress_discord_save_advance_settings">
 <input type="hidden" name="current_url" value="<?php echo esc_url( ets_profilepress_discord_get_current_screen_url() ); ?>">   
<?php wp_nonce_field( 'profilepress_discord_advance_settings_nonce', 'ets_profilepress_discord_advance_settings_nonce' ); ?>
  <table class="form-table" role="presentation">
	<tbody>
	<tr>
		<th scope="row"><?php esc_html_e( 'Shortcode:', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		[ets_ppress_discord]
		<br/>
		<small><?php esc_html_e( 'Use this shortcode [ets_ppress_discord] to display connect to discord button on any page.', 'connect-profilepress-and-discord' ); ?></small>
		</fieldset></td>
	</tr>         
	<tr>
		<th scope="row"><?php esc_html_e( 'Send welcome message', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<input name="ets_profilepress_discord_send_welcome_dm" type="checkbox" id="ets_profilepress_discord_send_welcome_dm" 
		<?php
		if ( $ets_profilepress_discord_send_welcome_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Welcome message', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
			<?php $ets_profilepress_discord_welcome_message_value = ( isset( $ets_profilepress_discord_welcome_message ) ) ? $ets_profilepress_discord_welcome_message : ''; ?>
		<textarea class="ets_profilepress_discord_dm_textarea" name="ets_profilepress_discord_welcome_message" id="ets_profilepress_discord_welcome_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( $ets_profilepress_discord_welcome_message_value ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [PPRESS_USER_NAME], [PPRESS_USER_EMAIL], [PPRESS_PLANS], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Send Purchase message', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<input name="ets_profilepress_discord_send_purchase_dm" type="checkbox" id="ets_profilepress_discord_send_purchase_dm" 
		<?php
		if ( $ets_profilepress_discord_send_purchase_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Purchase message', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
			<?php $ets_profilepress_discord_purchase_message_value = ( isset( $ets_profilepress_discord_purchase_message ) ) ? $ets_profilepress_discord_purchase_message : ''; ?>
		<textarea class="ets_profilepress_discord_purchase_message_textarea" name="ets_profilepress_discord_purchase_message" id="ets_profilepress_discord_purchase_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( $ets_profilepress_discord_purchase_message_value ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [PPRESS_USER_NAME], [PPRESS_USER_EMAIL], [PPRESS_PLANS], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>	
	<tr>
		<th scope="row"><?php esc_html_e( 'Send Cancelled subscription Message', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<input name="ets_profilepress_discord_send_cancelled_dm" type="checkbox" id="ets_profilepress_discord_send_cancelled_dm" 
		<?php
		if ( $ets_profilepress_discord_send_cancelled_dm == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Cancelled message', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
			<?php $ets_profilepress_discord_cancelled_message_value = ( isset( $ets_profilepress_discord_cancelled_message ) ) ? $ets_profilepress_discord_cancelled_message : ''; ?>
		<textarea class="ets_profilepress_discord_cancelled_message_textarea" name="ets_profilepress_discord_cancelled_message" id="ets_profilepress_discord_cancelled_message" row="25" cols="50"><?php echo esc_textarea( wp_unslash( $ets_profilepress_discord_cancelled_message_value ) ); ?></textarea> 
	<br/>
	<small>Merge fields: [PPRESS_USER_NAME], [PPRESS_USER_EMAIL], [PPRESS_PLANS], [SITE_URL], [BLOG_NAME]</small>
		</fieldset></td>
	</tr>		
	<tr>
		<th scope="row"><?php esc_html_e( 'Retry Failed API calls', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<input name="ets_profilepress_retry_failed_api" type="checkbox" id="ets_profilepress_retry_failed_api" 
		<?php
		if ( $retry_failed_api == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr> 
	  <tr>
		<th scope="row"><?php esc_html_e( 'Don\'t kick users upon disconnect', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<input name="ets_profilepress_kick_upon_disconnect" type="checkbox" id="ets_profilepress_kick_upon_disconnect" 
		<?php
		if ( $kick_upon_disconnect == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>	  
	<tr>
		<th scope="row"><?php esc_html_e( 'How many times a failed API call should get re-try', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<?php $retry_api_count_value = ( isset( $retry_api_count ) ) ? $retry_api_count : 1; ?>			
		<input name="ets_profilepress_retry_api_count" type="number" min="1" id="ets_profilepress_retry_api_count" value="<?php echo esc_attr( intval( $retry_api_count_value ) ); ?>">
		</fieldset></td>
	  </tr> 
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue concurrency', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<?php $set_job_cnrc_value = ( isset( $set_job_cnrc ) ) ? $set_job_cnrc : 1; ?>			
		<input name="set_job_cnrc" type="number" min="1" id="set_job_cnrc" value="<?php echo esc_attr( intval( $set_job_cnrc ) ); ?>">
		</fieldset></td>
	  </tr>
	  <tr>
		<th scope="row"><?php esc_html_e( 'Set job queue batch size', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<?php $set_job_q_batch_size_value = ( isset( $set_job_q_batch_size ) ) ? $set_job_q_batch_size : 10; ?>			
		<input name="set_job_q_batch_size" type="number" min="1" id="set_job_q_batch_size" value="<?php echo esc_attr( intval( $set_job_q_batch_size_value ) ); ?>">
		</fieldset></td>
	  </tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Log API calls response (For debugging purpose)', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<input name="log_api_res" type="checkbox" id="log_api_res" 
		<?php
		if ( $log_api_res == true ) {
			echo esc_attr( 'checked="checked"' ); }
		?>
		 value="1">
		</fieldset></td>
	  </tr>
			
	</tbody>
  </table>
  <div class="bottom-btn">
	<button type="submit" name="adv_submit" value="ets_submit" class="ets-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'connect-profilepress-and-discord' ); ?>
	</button>
  </div>
</form>
