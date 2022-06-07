
window.onload = function(){

	document.getElementById('js-logout')?.addEventListener('click', e => {
		e.preventDefault();
		fetch(e.target.href)
  			.then(window.location.href = '/');
	});

	$(".confirm_delete").off('click');
	$(".confirm_delete").on('click', e => deleteOrder(e));

	$("#accerder-user").off('click');
	$("#accerder-user").on('click', function () {
		$(this).addClass('loadbtn')
		$('.login-content-form').removeClass('animationShaker')
		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-form').serialize(),
			success: function (response) {
				if (response.status == 'success') {
					location.href = '/';
				} else {
					$(".message-error-log").text('').append(messages.error[response.msg]);
					$("#accerder-user").removeClass('loadbtn')
					$('.login-content-form').addClass('animationShaker')
				}
			}
		});
	});

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


	if($('#bid_modal_pujar').length){
		$('#bid_modal_pujar').autoComplete('destroy');
	}

	$("#confirm_orden_custom").on('click', function (event) {

		const subabierta = Boolean(this.dataset.subcsub);
		imp = $("#bid_modal_pujar").val();
		$.magnificPopup.close();
		$.ajax({
			type: "POST",
			url: routing.ol + '-' + cod_sub,
			data: { cod_sub: cod_sub, ref: ref, imp: imp },
			success: function (data) {
				if (data.status == 'error') {

					$("#insert_msg_title").html("");
					$("#insert_msg").html(data.msg_1);
					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				} else if (data.status == 'success') {
					//divisas, debe existir el selector
					if(typeof $("#currencyExchange").val() != 'undefined'){
						changeCurrency(data.imp, $("#currencyExchange").val(),"yourOrderExchange_JS");
					}

					$("#insert_msg").html(data.msg);
					$("#tuorden").html(data.imp.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1."));
					$(".delete_order").removeClass("hidden");
					$("#insert_msg_title").html("");
					$(".hist_new").removeClass("hidden");
					$(".custom").removeClass("hidden");
					$("#bid_modal_pujar").val(data.imp_actual);
					if (subabierta && !data.winner) {

						if($("#over-bid").length){
							$("#over-bid").removeClass('hidden');
						}
						else{
							let $container = $("#insert_msg").parent();
							$(`<p id="over-bid">${messages.error.your_order_lose}</p>`).insertAfter($container);
						}

					} else {
						$("#over-bid").addClass("hidden");
					}

					$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				}

			}
		});


	});

	$('.table.js-table-accordion').on('hide.bs.collapse', function (e) {
		$(`.accordion-${e.target.id} i`).removeClass('fa-minus').addClass('fa-plus');
	});
	$('.table.js-table-accordion').on('show.bs.collapse', function (e) {
		$(`.accordion-${e.target.id} i`).removeClass('fa-plus').addClass('fa-minus');
	});
}

function deleteOrder(event){
	var ref = $(event.target).attr("ref");
	var sub = $(event.target).attr("sub");
	$.magnificPopup.close();
	$.ajax({
		type: "POST",
		url: '/api-ajax/delete_order',
		data: { ref: ref, sub: sub },
		success: function (response) {
			res = jQuery.parseJSON(response);
			if (res.status == 'success') {
				location.reload();
			} else {
				if ($(res.respuesta).empty()) {
					$("#" + res.respuesta + " .form-group-custom input").addClass("has-error-custom");
				}
				$("#insert_msg").html(messages.error[res.msg]);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			}

		}

	});
}




carrousel_molon = function(carrousel) {

	if (carrousel.data('hasSlick')) {
		carrousel.slick('unslick');
	}

	var rows = 1;

	carrousel.slick({
		slidesToScroll: 1,
		rows: rows,
		autoplay: true,
		/*slidesPerRow: 4,*/
		slidesToShow: 4,
		arrows: true,
		dots: false,
		prevArrow: $('.fa-chevron-left'),
		nextArrow: $('.fa-chevron-right'),
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					infinite: true,

					rows: 1,
					slidesPerRow: 3,
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					rows: 1,
					slidesPerRow: 2,

				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					rows: 1,
					slidesPerRow: 1,
				}
			}

		]
	});

	carrousel.data('hasSlick', true);
}

see_img = function(){
	return;
}
