(function( $ ) {
	'use strict';

	$(document).ready(function(){
		/*Call-back on disconnect from discord and kick student if the case */
		$('#profilepress-discord-disconnect-discord').on('click', function (e) {
			e.preventDefault();
			var userId = $(this).data('user-id');
			$.ajax({
				type: "POST",
				dataType: "JSON",
				url: etsProfilePressParams.admin_ajax,
				data: { 'action': 'profilepress_disconnect_from_discord', 'user_id': userId, 'ets_profilepress_discord_nonce': etsProfilePressParams.ets_profilepress_discord_nonce },
				beforeSend: function () {
					$(".ets-spinner").addClass("ets-is-active");
				},
				success: function (response) {
					console.log(response);
					if (response.status == 1) {
						window.location = window.location.href.split("?")[0];
					}
				},
				error: function (response ,  textStatus, errorThrown) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				}
			});
		});
	});

})( jQuery );
