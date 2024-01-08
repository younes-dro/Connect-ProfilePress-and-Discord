
<div class="contact-form ">

<div class="ets-container">
		<div class="top-logo-title">
		  <img src="<?php echo esc_url( ETS_PROFILEPRESS_DISCORD_PLUGIN_DIR_URL . 'admin/images/ets-logo.png' ); ?>" class="img-fluid company-logo" alt="">
		  <h1><?php esc_html_e( 'ExpressTech Softwares Solutions Pvt. Ltd.', 'connect-profilepress-and-discord' ); ?></h1>
		  <p><?php esc_html_e( 'ExpressTech Software Solution Pvt. Ltd. is the leading Enterprise WordPress development company.', 'connect-profilepress-and-discord' ); ?><br>
		  <?php esc_html_e( 'Contact us for any WordPress Related development projects.', 'connect-profilepress-and-discord' ); ?></p>
		</div>

		<ul style="text-align: left;">
			<li class="mp-icon mp-icon-right-big"><?php esc_html_e( 'If you encounter any issues or errors, please report them on our support forum for Connect profilepress to Discord plugin. Our community will be happy to help you troubleshoot and resolve the issue.', 'connect-profilepress-and-discord' ); ?></li>
			<li class="mp-icon mp-icon-right-big">
			<?php
			echo wp_kses(
				'<a target="_blank" href="https://wordpress.org/support/plugin/connect-profilepress-and-discord/">Support » Plugin: Connect profilepress to Discord</a>',
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
					),
				)
			);
			?>
 </li>
		</ul>


	  </div>

</div>
