<?php
$ets_profilepress_discord_connect_button_bg_color    = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_connect_button_bg_color' ) ) );
$ets_profilepress_discord_disconnect_button_bg_color = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_disconnect_button_bg_color' ) ) );
$btn_text                                     = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_non_login_button_text' ) ) );
$loggedin_btn_text                            = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_loggedin_button_text' ) ) );
$ets_profilepress_discord_disconnect_btn_text = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_disconnect_button_text' ) ) );
$ets_current_screen                           = ets_profilepress_discord_get_current_screen_url();
?>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
 <input type="hidden" name="action" value="profilepress_discord_save_appearance_settings">
 <input type="hidden" name="current_url" value="<?php echo esc_url( $ets_current_screen ); ?>" />
<?php wp_nonce_field( 'save_ets_profilepress_discord_appearance_settings', 'ets_profilepress_discord_save_appearance_settings' ); ?>
  <table class="form-table" role="presentation">
	<tbody>
	 <tr>
		<th scope="row"><?php esc_html_e( 'Connect/Login Button color', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
			<?php $ets_profilepress_discord_connect_button_bg_color_value = ( isset( $ets_profilepress_discord_connect_button_bg_color ) ) ? $ets_profilepress_discord_connect_button_bg_color : '#77a02e'; ?>
		<input name="ets_profilepress_discord_connect_button_bg_color" type="text" id="ets_profilepress_discord_connect_button_bg_color" value="<?php echo esc_attr( $ets_profilepress_discord_connect_button_bg_color_value ); ?>" data-default-color="#77a02e">
		</fieldset></td> 
	</tr>
  <tr>
		<th scope="row"><?php esc_html_e( 'Disconnect Button color', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<?php $ets_profilepress_discord_disconnect_button_bg_color_value = ( isset( $ets_profilepress_discord_disconnect_button_bg_color ) ) ? $ets_profilepress_discord_disconnect_button_bg_color : '#ff0000'; ?>
		<input name="ets_profilepress_discord_disconnect_button_bg_color" type="text" id="ets_profilepress_discord_disconnect_button_bg_color" value="<?php echo esc_attr( $ets_profilepress_discord_disconnect_button_bg_color_value ); ?>" data-default-color="#ff0000">
		</fieldset></td> 
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Text on the Button for logged-in users', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
			<?php $loggedin_btn_text_value = ( isset( $loggedin_btn_text ) ) ? $loggedin_btn_text : ''; ?>
		<input name="ets_profilepress_loggedin_btn_text" type="text" id="ets_profilepress_loggedin_btn_text" value="<?php echo esc_attr( $loggedin_btn_text_value ); ?>">
		</fieldset></td> 
	</tr>
	<tr>
		<th scope="row"><?php esc_html_e( 'Text on the Button for non-login users', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<?php $btn_text_value = ( isset( $btn_text ) ) ? $btn_text : ''; ?>
		<input name="ets_profilepress_loggedout_btn_text" type="text" id="ets_profilepress_loggedout_btn_text" value="<?php echo esc_attr( $btn_text_value ); ?>">
		</fieldset></td> 
	</tr>	
  <tr>
		<th scope="row"><?php esc_html_e( 'Text on the Disconnect Button', 'connect-profilepress-and-discord' ); ?></th>
		<td> <fieldset>
		<?php $ets_profilepress_discord_disconnect_btn_text_value = ( isset( $ets_profilepress_discord_disconnect_btn_text ) ) ? $ets_profilepress_discord_disconnect_btn_text : ''; ?>
		<input name="ets_profilepress_discord_disconnect_btn_text" type="text" id="ets_profilepress_discord_disconnect_btn_text" value="<?php echo esc_attr( $ets_profilepress_discord_disconnect_btn_text_value ); ?>">
		</fieldset></td> 
	</tr>	
	</tbody>
  </table>
  <div class="bottom-btn">
	<button type="submit" name="appearance_submit" value="ets_submit" class="ets-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'connect-profilepress-and-discord' ); ?>
	</button>
  </div>
</form>
