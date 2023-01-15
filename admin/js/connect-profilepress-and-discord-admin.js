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
		/*Load all roles from discord server*/
		$.ajax({
			type: "POST",
			dataType: "JSON",
			url: etsProfilePressParams.admin_ajax,
			data: { 'action': 'ets_profilepress_discord_load_discord_roles', 'ets_profilepress_discord_nonce': etsProfilePressParams.ets_profilepress_discord_nonce },
			beforeSend: function () {
				$(".profilepress-discord-roles .spinner").addClass("is-active");
				$(".initialtab.spinner").addClass("is-active");
			},
			success: function (response) {
				if (response != null && response.hasOwnProperty('code') && response.code == 50001 && response.message == 'Missing Access') {
					$(".profilepress-btn-connect-to-bot").show();
				} else if ( response.code === 10004 && response.message == 'Unknown Guild' ) {
					$(".profilepress-btn-connect-to-bot").show().after('<p><b>The server ID is wrong or you did not connect the Bot.</b></p>');
				}else if( response.code === 0 && response.message == '401: Unauthorized' ) {
					$(".profilepress-btn-connect-to-bot").show().html("Error: Unauthorized - The Bot Token is wrong").addClass('error-bk');					
				} else if (response == null || response.message == '401: Unauthorized' || response.hasOwnProperty('code') || response == 0) {
					$("#profilepress-connect-discord-bot").show().html("Error: Please check all details are correct").addClass('error-bk');
				} else {
					if ($('.ets-tabs button[data-identity="level-mapping"]').length) {
						$('.ets-tabs button[data-identity="level-mapping"]').show();
					}
					if (response.bot_connected === 'yes') {
						$("#profilepress-connect-discord-bot").show().html("Bot Connected <i class='fab fa-discord'></i>").addClass('not-active');                                            
					}else{
						$("#profilepress-connect-discord-bot").show().html("Error: Please check the Client ID is correct").addClass('error-bk');
                                        }                                        
					

					var activeTab = localStorage.getItem('activeTab');
					if ($('.ets-tabs button[data-identity="level-mapping"]').length == 0 && activeTab == 'level-mapping') {
						$('.ets-tabs button[data-identity="settings"]').trigger('click');
					}
					$.each(response, function (key, val) {
						var isbot = false;
						if (val.hasOwnProperty('tags')) {
							if (val.tags.hasOwnProperty('bot_id')) {
								isbot = true;
							}
						}

						if (key != 'bot_connected' && key != 'previous_mapping' && isbot == false && val.name != '@everyone') {
							$('.profilepress-discord-roles').append('<div class="makeMeDraggable" style="background-color:#'+val.color.toString(16)+'" data-profilepress_role_id="' + val.id + '" >' + val.name + '</div>');
							$('#profilepress-defaultRole').append('<option value="' + val.id + '" >' + val.name + '</option>');
							makeDrag($('.makeMeDraggable'));
						}
					});
					var defaultRole = $('#selected_default_role').val();
					if (defaultRole) {
						$('#profilepress-defaultRole option[value=' + defaultRole + ']').prop('selected', true);
					}

					if (response.previous_mapping) {
						var mapjson = response.previous_mapping;
					} else {
						var mapjson = localStorage.getItem('profilepress_mappingjson');
					}

					$("#ets_profilepress_mapping_json_val").html(mapjson);
					$.each(JSON.parse(mapjson), function (key, val) {
						var arrayofkey = key.split('id_');
						var preclone = $('*[data-profilepress_role_id="' + val + '"]').clone();
						if(preclone.length>1){
							preclone.slice(1).hide();
						}
						
						if (jQuery('*[data-profilepress_plan_id="' + arrayofkey[1] + '"]').find('*[data-profilepress_role_id="' + val + '"]').length == 0) {
							$('*[data-profilepress_plan_id="' + arrayofkey[1] + '"]').append(preclone).attr('data-drop-profilepress_role_id', val).find('span').css({ 'order': '2' });
						}
						if ($('*[data-profilepress_plan_id="' + arrayofkey[1] + '"]').find('.makeMeDraggable').length >= 1) {
							$('*[data-profilepress_plan_id="' + arrayofkey[1] + '"]').droppable("destroy");
						}

						preclone.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '0px', 'order': '1' }).attr('data-profilepress_plan_id', arrayofkey[1]);
						makeDrag(preclone);
					});
				}

			},
			error: function (response) {
				$("#profilepress-connect-discord-bot").show().html("Error: Please check all details are correct").addClass('error-bk');
				console.error(response);
			},
			complete: function () {
				$(".profilepress-discord-roles .spinner").removeClass("is-active").css({ "float": "right" });
				$("#skeletabsTab1 .spinner").removeClass("is-active").css({ "float": "right", "display": "none" });
			}
		});

      		/*Flush settings from local storage*/
			  $("#revertMapping").on('click', function () {
				localStorage.removeItem('profilepress_mapArray');
				localStorage.removeItem('profilepress_mappingjson');
				window.location.href = window.location.href;
			});        
	   
			/*Create droppable element*/
			function init() {
						if($('.makeMeDroppable').length){
				$('.makeMeDroppable').droppable({
					drop: handleDropEvent,
					hoverClass: 'hoverActive',
				});
						}
						if($('.profilepress-discord-roles-col').length){
				$('.profilepress-discord-roles-col').droppable({
					drop: handlePreviousDropEvent,
					hoverClass: 'hoverActive',
				});
						}
			}
	
			$(init);		

		/*Create draggable element*/
		function makeDrag(el) {
			// Pass me an object, and I will make it draggable
			el.draggable({
				revert: "invalid",
				helper: 'clone',
				start: function(e, ui) {
				ui.helper.css({"width":"45%"});
				}
			});
		}

		/*Handel droppable event for saved mapping*/
		function handlePreviousDropEvent(event, ui) {
			var draggable = ui.draggable;
			if(draggable.data('profilepress_plan_id')){
				$(ui.draggable).remove().hide();
			}
			$(this).append(draggable);
			$('*[data-drop-profilepress_role_id="' + draggable.data('profilepress_role_id') + '"]').droppable({
				drop: handleDropEvent,
				hoverClass: 'hoverActive',
			});
			$('*[data-drop-profilepress_role_id="' + draggable.data('profilepress_role_id') + '"]').attr('data-drop-profilepress_role_id', '');

			var oldItems = JSON.parse(localStorage.getItem('profilepress_mapArray')) || [];
			$.each(oldItems, function (key, val) {
				if (val) {
					var arrayofval = val.split(',');
					if (arrayofval[0] == 'profilepress_plan_id_' + draggable.data('profilepress_plan_id') && arrayofval[1] == draggable.data('profilepress_role_id')) {
						delete oldItems[key];
					}
				}
			});
			var jsonStart = "{";
			$.each(oldItems, function (key, val) {
				if (val) {
					var arrayofval = val.split(',');
					if (arrayofval[0] != 'profilepress_plan_id_' + draggable.data('profilepress_plan_id') || arrayofval[1] != draggable.data('profilepress_role_id')) {
						jsonStart = jsonStart + '"' + arrayofval[0] + '":' + '"' + arrayofval[1] + '",';
					}
				}
			});
			localStorage.setItem('profilepress_mapArray', JSON.stringify(oldItems));
			var lastChar = jsonStart.slice(-1);
			if (lastChar == ',') {
				jsonStart = jsonStart.slice(0, -1);
			}

			var profilepress_mappingjson = jsonStart + '}';
			$("#ets_profilepress_mapping_json_val").html(profilepress_mappingjson);
			localStorage.setItem('profilepress_mappingjson', profilepress_mappingjson);
			draggable.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '10px' });
		}

		/*Handel droppable area for current mapping*/
		function handleDropEvent(event, ui) {
			var draggable = ui.draggable;
			var newItem = [];
			var newClone = $(ui.helper).clone();
			if($(this).find(".makeMeDraggable").length >= 1){
				return false;
			}
			$('*[data-drop-profilepress_role_id="' + newClone.data('profilepress_role_id') + '"]').droppable({
				drop: handleDropEvent,
				hoverClass: 'hoverActive',
			});
			$('*[data-drop-profilepress_role_id="' + newClone.data('profilepress_role_id') + '"]').attr('data-drop-profilepress_role_id', '');
			if ($(this).data('drop-profilepress_role_id') != newClone.data('profilepress_role_id')) {
				var oldItems = JSON.parse(localStorage.getItem('profilepress_mapArray')) || [];
				$(this).attr('data-drop-profilepress_role_id', newClone.data('profilepress_role_id'));
				newClone.attr('data-profilepress_plan_id', $(this).data('profilepress_plan_id'));

				$.each(oldItems, function (key, val) {
					if (val) {
						var arrayofval = val.split(',');
						if (arrayofval[0] == 'profilepress_plan_id_' + $(this).data('profilepress_plan_id')) {
							delete oldItems[key];
						}
					}
				});

				var newkey = 'profilepress_plan_id_' + $(this).data('profilepress_plan_id');
				oldItems.push(newkey + ',' + newClone.data('profilepress_role_id'));
				var jsonStart = "{";
				$.each(oldItems, function (key, val) {
					if (val) {
						var arrayofval = val.split(',');
						if (arrayofval[0] == 'profilepress_plan_id_' + $(this).data('profilepress_plan_id') || arrayofval[1] != newClone.data('profilepress_role_id') && arrayofval[0] != 'profilepress_plan_id_' + $(this).data('profilepress_plan_id') || arrayofval[1] == newClone.data('profilepress_role_id')) {
							jsonStart = jsonStart + '"' + arrayofval[0] + '":' + '"' + arrayofval[1] + '",';
						}
					}
				});

				localStorage.setItem('profilepress_mapArray', JSON.stringify(oldItems));
				var lastChar = jsonStart.slice(-1);
				if (lastChar == ',') {
					jsonStart = jsonStart.slice(0, -1);
				}

				var profilepress_mappingjson = jsonStart + '}';
				localStorage.setItem('profilepress_mappingjson', profilepress_mappingjson);
				$("#ets_profilepress_mapping_json_val").html(profilepress_mappingjson);
			}

			$(this).append(newClone);
			$(this).find('span').css({ 'order': '2' });
			if (jQuery(this).find('.makeMeDraggable').length >= 1) {
				$(this).droppable("destroy");
			}
			makeDrag($('.makeMeDraggable'));
			newClone.css({ 'width': '100%', 'left': '0', 'top': '0', 'margin-bottom': '0px', 'position':'unset', 'order': '1' });
		} 
		
		/*Clear log log call-back*/
		$('#ets-profilepress-clrbtn').click(function (e) {
			e.preventDefault();
			$.ajax({
				url: etsProfilePressParams.admin_ajax,
				type: "POST",
				data: { 'action': 'ets_profilepress_discord_clear_logs', 'ets_profilepress_discord_nonce': etsProfilePressParams.ets_profilepress_discord_nonce },
				beforeSend: function () {
					$(".clr-log.spinner").addClass("is-active").show();
				},
				success: function (data) {
         
					if (data.error) {
						// handle the error
						alert(data.error.msg);
					} else {
                                            
						$('.error-log').html("Clear logs Sucesssfully !");
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
					$(".clr-log.spinner").removeClass("is-active").hide();
				}
			});
		});

		if ( $.fn.wpColorPicker ) {
			$('#ets_profilepress_discord_connect_button_bg_color').wpColorPicker();
			$('#ets_profilepress_discord_disconnect_button_bg_color').wpColorPicker();
		}


		$('.profilepress-disconnect-discord-user').click(function (e) {
			e.preventDefault();
			$.ajax({
				url: etsProfilePressParams.admin_ajax,
				type: "POST",
				context: this,
				data: { 'action': 'ets_profilepress_discord_disconnect_user', 'ets_profilepress_discord_user_id': $(this).data('user-id') , 'ets_profilepress_discord_nonce': etsProfilePressParams.ets_profilepress_discord_nonce },
				beforeSend: function () {
						$(this).next('span.spinner').css('display','inline-block').addClass("is-active").show();
				},
				success: function (data) {         
					if (data.error) {
						// handle the error
						alert(data.error.msg);
					} else {
						$(this).prop('disabled', true);
						console.log(data);
					}
				},
				error: function (response, textStatus, errorThrown ) {
					console.log( textStatus + " :  " + response.status + " : " + errorThrown );
				},
				complete: function () {
					$(this).next('span.spinner').removeClass("is-active").hide();
				}
			});
		});

	}); // Document Ready

}// Is Admin

			/*Tab options*/
			if ($.skeletabs ) {
				$.skeletabs.setDefaults({
					keyboard: false,
				});
			}
})( jQuery );
