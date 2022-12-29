<div class="error-log">
<?php
	$uuid     = sanitize_text_field( trim( get_option( 'ets_profilepress_discord_uuid_file_name' ) ) );
	$filename = $uuid . Connect_Profilepress_And_Discord_Logs::$log_file_name;
	$handle   = fopen( WP_CONTENT_DIR . '/' . $filename, 'a+' );
if ( $handle ) {
	while ( ! feof( $handle ) ) {
		echo esc_html( fgets( $handle ) ) . '<br />';
	}
	fclose( $handle );
}
?>
</div>
<div class="profilepress-clrbtndiv">
	<div class="form-group">
		<input type="button" class="ets-profilepress-clrbtn ets-submit ets-bg-red" id="ets-profilepress-clrbtn" name="profilepress_clrbtn" value="Clear Logs !">
		<span class="clr-log spinner" ></span>
	</div>
	<div class="form-group">
		<input type="button" class="ets-submit ets-bg-green" value="Refresh" onClick="window.location.reload()">
	</div>
	<div class="form-group">
		<a href="<?php echo esc_url( content_url( '/' ) . $filename ); ?>" class="ets-submit ets-profilepress-bg-download" download><?php esc_html_e( 'Download', 'connect-profilepress-and-discord' ); ?></a>
	</div>
	<div class="form-group">
		<a href="<?php echo esc_url( get_admin_url( '', 'tools.php' ) ) . '?page=action-scheduler&status=pending&s=profilepress'; ?>" class="ets-submit ets-profilepress-bg-scheduled-actions"><?php esc_html_e( 'Scheduled Actions', 'connect-profilepress-and-discord' ); ?></a>
	</div>    
</div>
