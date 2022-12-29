<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.expresstechsoftwares.com
 * @since      1.0.0
 *
 * @package    Connect_Profilepress_And_Discord
 * @subpackage Connect_Profilepress_And_Discord/admin/partials
 */
?>
<!-- Save Setting Massage -->
<?php

if ( isset( $_GET['save_settings_msg'] ) ) {

	?>
	<div class="notice notice-success is-dismissible support-success-msg">
		<p><?php echo esc_html( $_GET['save_settings_msg'] ); ?></p>
	</div>
	<?php
}
?>
<!-- This is Main Page ProfilePress-Discord-Addon --->

<h1><?php esc_html_e( 'ProfilePress Discord Add On Settings', 'connect-profilepress-and-discord' ); ?></h1>
		<div id="outer" class="skltbs-theme-light" data-skeletabs='{ "startIndex": 0 }'>
		<ul class="skltbs-tab-group">

				<li class="skltbs-tab-item">
					<button class="skltbs-tab" data-identity="profilepress_application" ><?php esc_html_e( 'Application Details', 'connect-profilepress-and-discord' ); ?><span class="initialtab spinner"></span></button>
				</li>	
				<li class="skltbs-tab-item">
					<?php if ( ets_profilepress_discord_check_saved_settings_status() ) : ?>
						<button class="skltbs-tab" data-identity="level-mapping" ><?php esc_html_e( 'Role Mapping', 'connect-profilepress-and-discord' ); ?></button>
						<?php endif; ?>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="advanced" ><?php esc_html_e( 'Advanced', 'connect-profilepress-and-discord' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="appearance" ><?php esc_html_e( 'Appearance', 'connect-profilepress-and-discord' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="logs" ><?php esc_html_e( 'Logs', 'connect-profilepress-and-discord' ); ?>	
				</button>
				</li>
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="documentation" ><?php esc_html_e( 'Documentation', 'connect-profilepress-and-discord' ); ?>	
				</button>				
				<li class="skltbs-tab-item">
				<button class="skltbs-tab" data-identity="support" ><?php esc_html_e( 'Support', 'connect-profilepress-and-discord' ); ?>	
				</button>								                                 
		</ul>
<!--Creating Tabs-->
			<div class="skltbs-panel-group">
				<div id='ets_profilepress_general_settings' class="skltbs-panel">
					<?php require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-profilepress-discord-application-details.php'; ?>
				</div>
				<?php if ( ets_profilepress_discord_check_saved_settings_status() ) : ?>  
				<div id='ets_profilepress_role_level' class="skltbs-panel">
					<?php require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-profilepress-discord-role-mapping.php'; ?>
				</div>
				<?php endif; ?>
				<div id='ets_profilepress_discord_advanced' class="skltbs-panel">
				<?php require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-profilepress-discord-advanced.php'; ?>
				</div>
				<div id='ets_profilepress_discord_appearance' class="skltbs-panel">
				<?php require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-profilepress-discord-appearance.php'; ?>
				</div> 
				<div id='ets_profilepress_discord_logs' class="skltbs-panel">
				<?php require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-profilepress-discord-error-log.php'; ?>
				</div>
				<div id='ets_profilepress_discord_documentation' class="skltbs-panel">
				<?php require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-profilepress-discord-documentation.php'; ?>
				</div>				
				<div id='ets_profilepress_discord_support' class="skltbs-panel">
				<?php require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'admin/partials/pages/connect-profilepress-discord-support.php'; ?>
				</div>								                
			</div>
	</div>
