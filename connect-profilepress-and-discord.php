<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.expresstechsoftwares.com
 * @since             1.0.0
 * @package           Connect_Profilepress_And_Discord
 *
 * @wordpress-plugin
 * Plugin Name:       Connect ProfilePress and Discord
 * Plugin URI:        https://www.expresstechsoftwares.com/connect-profilepress-and-discord
 * Description:       Sell private access to your discord server, and assign discord roles as per the membership level of the users.
 * Version:           1.0.2
 * Author:            ExpressTech Software Solutions Pvt. Ltd.
 * Author URI:        https://www.expresstechsoftwares.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connect-profilepress-and-discord
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Discord API URL
 */
define( 'ETS_PROFILEPRESS_DISCORD_API_URL', 'https://discord.com/api/v10/' );

/**
 * Discord BOT Permissions
 */

define( 'ETS_PROFILEPRESS_DISCORD_BOT_PERMISSIONS', 8 );


/**
 * Discord API call scopes
 */
define( 'ETS_PROFILEPRESS_DISCORD_OAUTH_SCOPES', 'identify email guilds guilds.join' );

/**
 * Follwing response codes not cosider for re-try API calls.
 */
define( 'ETS_PROFILEPRESS_DISCORD_DONOT_RETRY_THESE_API_CODES', array( 0, 10003, 50033, 10004, 50025, 10013, 10011 ) );

/**
 * Define plugin directory url
 */
define( 'ETS_PROFILEPRESS_DISCORD_DONOT_RETRY_HTTP_CODES', array( 400, 401, 403, 404, 405, 502 ) );
/**
 * ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH
 */
define( 'ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Define group name for action scheduler actions
 */
define( 'ETS_PROFILEPRESS_DISCORD_AS_GROUP_NAME', 'ets-profilepress-discord' );

/**
 * Define plugin directory URL
 */
define( 'ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CONNECT_PROFILEPRESS_AND_DISCORD_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-connect-profilepress-and-discord-activator.php
 */
function activate_connect_profilepress_and_discord() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-connect-profilepress-and-discord-activator.php';
	Connect_Profilepress_And_Discord_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-connect-profilepress-and-discord-deactivator.php
 */
function deactivate_connect_profilepress_and_discord() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-connect-profilepress-and-discord-deactivator.php';
	Connect_Profilepress_And_Discord_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_connect_profilepress_and_discord' );
register_deactivation_hook( __FILE__, 'deactivate_connect_profilepress_and_discord' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-connect-profilepress-and-discord.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_connect_profilepress_and_discord() {

	$plugin = new Connect_Profilepress_And_Discord();
	$plugin->run();

}
run_connect_profilepress_and_discord();
