(function( $ ) {
	'use strict';
	if (etsProfilePressParams.is_admin) {
		$(document).ready(function(){
		if(jQuery().select2) {

			$('#ets_profilepress_discord_redirect_url').select2({ width: 'resolve' });
                $('#ets_profilepress_discord_redirect_url').on('change', function(){
				$.ajax({
					url: etsProfilePressParams.admin_ajax,
					type: "POST",
					context: this,
					data: { 'action': 'ets_profilepress_discord_update_redirect_url', 'ets_profilepress_page_id': $(this).val() , 'ets_profilepress_discord_nonce': etsProfilePressParams.ets_profilepress_discord_nonce },
					beforeSend: function () {
						$('p.redirect-url').find('b').html("");
                        $('p.ets-discord-update-message').css('display','none');                                               
						$(this).siblings('p.description').find('span.spinner').addClass("ets-is-active").show();
					},
					success: function (data) {
						
						$('p.redirect-url').find('b').html(data.formated_discord_redirect_url);
						$('p.ets-discord-update-message').css('display','block');                                               
					},
					error: function (response, textStatus, errorThrown ) {
						console.log( textStatus + " :  " + response.status + " : " + errorThrown );
					},
					complete: function () {
						$(this).siblings('p.description').find('span.spinner').removeClass("ets-is-active").hide();
					}
				});

			});                        
		}
	}); // Document Ready

}// Is Admin

			/*Tab options*/
			if ($.skeletabs ) {
				$.skeletabs.setDefaults({
					keyboard: false,
				});
			}
})( jQuery );
