<?php



 defined( 'ABSPATH' ) || exit;

 /**
  * Connect_Profilepress_And_DiscordAdmin_Notices
  *
  * @since 1.0.10
  */
class Connect_Profilepress_And_Discord_Admin_Notices {

	/**
	 * Static constructor
	 *
	 * @return void
	 */
	public static function init() {

		add_action( 'admin_notices', array( __CLASS__, 'ets_profilepress_discord_display_notification' ) );
	}

	/**
	 * Display the review notification
	 *
	 * @return void
	 */
	public static function ets_profilepress_discord_display_notification() {

		$screen = get_current_screen();

		if ( $screen && $screen->id === 'profilepress_page_connect-profilepress-and-discord' ) {

			$dismissed = get_user_meta( get_current_user_id(), '_ets_profilepress_discord_dismissed_notification', true );
			if ( ! $dismissed ) {
				ob_start();
				require_once ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_PATH . 'includes/template/notification/review/review.php';
				$notification_content = ob_get_clean();
				echo wp_kses( $notification_content, self::ets_profilepress_discord_allowed_html() );
			}
		}
	}

	/**
	 * Get allowed_html
	 *
	 * @return ARRAY
	 */
	public static function ets_profilepress_discord_allowed_html() {
		$allowed_html = array(
			'div' => array(
				'class' => array(),
			),
			'p'   => array(
				'class' => array(),
			),
			'a'   => array(
				'id'           => array(),
				'data-user-id' => array(),
				'href'         => array(),
				'class'        => array(),
				'style'        => array(),
			),

			'img' => array(
				'src'   => array(),
				'class' => array(),
			),
		);

		return $allowed_html;
	}

}

Connect_Profilepress_And_Discord_Admin_Notices::init();
