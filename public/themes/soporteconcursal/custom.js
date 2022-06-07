ajax_carousel = function(key, replace, callbackKey = '' ,isCallback = false) {
	//$( "#"+key ).siblings().removeClass('hidden');
	$.ajax({
		type: "POST",
		url: "/api-ajax/carousel",
		data: { key: key, replace: replace },
		success: function (result) {

			if(isCallback){
				key = callbackKey;
			}

			if (result === '' && !isCallback) {
				ajax_carousel('lotes_random', replace, key, true);
				return;
			}
			else if(result === ''){
				$("#" + key + '-content').hide();
			}


			$("#" + key).siblings('.loader').addClass('hidden');
			$("#" + key).html(result);
			if (key === 'lotes_destacados') {
				carrousel_molon($("#" + key));
			}
			else {
				carrousel_molon_new($("#" + key));
			}
			$('.lazy').Lazy({
				// your configuration goes here
				scrollDirection: 'vertical',
				effect: 'fadeIn',
				effectTime: 100,
				visibleOnly: true,
				onError: function (element) {
					console.log('error loading ' + element.data('src'));
				},
				afterLoad: function (element) {
					$('.text-input__loading--line').hide()
				},
			});
			$('[data-countdown]').each(function () {
				$(this).data('ini', new Date().getTime());
				countdown_timer($(this));
			});
		}

	});

};

function categoryToogle(){

	if($('.header_categories').css('display') == 'block'){
		$('.header_categories').animate({left: '-100%'}, "slow").fadeOut(300);
		return;
	}

	$('.header_categories').toggle().animate({left: $('.category-button').offset().left}, "slow");
	return;
}

window.onload = function(){

	$('.lot-action_info_lot').on('click', function (e) {
		e.stopPropagation();
		$.magnificPopup.close();
		if (typeof cod_licit == 'undefined' || cod_licit == null) {
			$("#insert_msg").html("");
			$("#insert_msg").html(messages.error.need_login);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			return;
		}

		$.ajax({
			type: "POST",
			url: '/api-ajax/info-lot-email',
			data: {cod_licit: cod_licit, cod_sub: cod_sub, ref: ref},
			success: function( data ) {

				$("#insert_msg").html("");

				if (data.status == 'error'){
						$("#insert_msg").html(data.msg);
						$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				}
				else if(data.status == 'success'){
					$("#insert_msg").html(data.msg);
					$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				}


			}
		});

	});
}


