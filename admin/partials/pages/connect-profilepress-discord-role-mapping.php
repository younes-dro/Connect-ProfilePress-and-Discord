<?php

$ets_active_plans = ets_profilepress_discord_get_active_plans();

$connect_profilepress_default_role = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_default_role_id' ) ) );
?>
<div class="notice notice-warning ets-notice">
	<p><i class='fas fa-info'></i> <?php esc_html_e( 'Drag and Drop the Discord Roles over to the ProfilePress Plans', 'connect-profilepress-and-discord' ); ?></p>
</div>
<div class="notice notice-warning ets-notice">
  <p><i class='fas fa-info'></i> <?php esc_html_e( 'Note: only Plans with status active are displayed', 'connect-profilepress-and-discord' ); ?></p>
</div>

<div class="row-container">
  <div class="ets-column profilepress-discord-roles-col">
	<h2><?php esc_html_e( 'Discord Roles', 'connect-profilepress-and-discord' ); ?></h2>
	<hr>
	<div class="profilepress-discord-roles">
	  <span class="spinner"></span>
	</div>
  </div>
  <div class="ets-column">
	<h2><?php esc_html_e( 'Plans', 'connect-profilepress-and-discord' ); ?></h2>
	<hr>
	<div class="profilepress-discord-plans">
	<?php
	if ( $ets_active_plans ) {
		foreach ( $ets_active_plans as  $active_plan ) {

			?>
		  <div class="makeMeDroppable" data-profilepress_panl_id="<?php echo esc_attr( $active_plan['id'] ); ?>" ><span><?php echo esc_html( $active_plan['name'] ); ?></span></div>
			<?php

		}
	}
	?>
	</div>
  </div>
</div>
<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
 <input type="hidden" name="action" value="profilepress_discord_save_role_mapping">
 <input type="hidden" name="current_url" value="<?php echo esc_url( ets_profilepress_discord_get_current_screen_url() ); ?>">   
  <table class="form-table" role="presentation">
	<tbody>
	  <tr>
		<th scope="row"><label for="profilepress-defaultRole"><?php esc_html_e( 'Default Role', 'connect-profilepress-and-discord' ); ?></label></th>
		<td>
		  <?php wp_nonce_field( 'profilepress_discord_role_mappings_nonce', 'ets_profilepress_discord_role_mappings_nonce' ); ?>
		  <input type="hidden" id="selected_default_role" value="<?php echo esc_attr( $connect_profilepress_default_role ); ?>">
		  <select id="profilepress-defaultRole" name="profilepress_defaultRole">
			<option value="none"><?php esc_html_e( '-None-', 'connect-profilepress-and-discord' ); ?></option>
		  </select>
		  <p class="description"><?php esc_html_e( 'This Role will be assigned to all Plans', 'connect-profilepress-and-discord' ); ?></p>
		</td>
	  </tr>        

	</tbody>
  </table>
	<br>
  <div class="mapping-json">
	<textarea id="ets_profilepress_mapping_json_val" name="ets_profilepress_discord_role_mapping">
	<?php
	if ( isset( $ets_discord_roles ) ) {
		echo stripslashes( esc_html( $ets_discord_roles ) );}
	?>
	</textarea>
  </div>
  <div class="bottom-btn">
	<button type="submit" name="submit" value="ets_submit" class="ets-submit ets-btn-submit ets-bg-green">
	  <?php esc_html_e( 'Save Settings', 'connect-profilepress-and-discord' ); ?>
	</button>
	<button id="revertMapping" name="flush" class="ets-submit ets-btn-submit ets-bg-red">
	  <?php esc_html_e( 'Flush Mappings', 'connect-profilepress-and-discord' ); ?>
	</button>
  </div>
</form>
