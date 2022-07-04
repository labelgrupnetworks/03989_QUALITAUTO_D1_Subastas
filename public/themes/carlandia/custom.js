let sidebar = document.createElement("div");

$(document).ready(function () {

	$(".clictelefonoEvent_JS").on('click',function(){
		if(device() =="mobile" ){
			eventGa("Clic teléfono");
		}

	});

	$(".clicWhatsappEvent_JS").on('click',function(){

		ga('send','event','Clic Whatsapp',device());

	});

	$(".cliccontraofertarGrid_JS").on('click',function(){

		ga('send','event','Clic Controfertar listado',$(this).data("coche") + "/" + $(this).data("matricula"));


	});

	$(".clicVerfichaGrid_JS").on('click',function(){

		ga('send','event','Clic Ver Ficha',$(this).data("coche") + "/" + $(this).data("matricula"));


	});

	$(".clicVehiculosSimilares_JS").on('click',function(){
		matricula = $("#matricula_JS").val();
		coche = $("#nombre_coche_JS").val();

		ga('send','event','Clic Vehículos Similares',coche + "/" + matricula);


	});

	$(".clicAnadirFavoritos_JS").on('click',function(){
		matricula = $("#matricula_JS").val();
		coche = $("#nombre_coche_JS").val();
		ga('send','event','Añadir a Favoritos',coche + "/" + matricula);

	});

	$(".clicEliminarFavoritos_JS").on('click',function(){
		matricula = $("#matricula_JS").val();
		coche = $("#nombre_coche_JS").val();
		ga('send','event','Eliminar de Favoritos',coche + "/" + matricula);

	});


	$(".clicRechazoVehículosSimilares_JS").on('click',function(){
		matricula = $("#matricula_JS").val();
		coche = $("#nombre_coche_JS").val();
		if($("#amountOverModal").val() == "true" ){
			ga('send','event','Cercano Esperar Respuesta',coche + "/" + matricula);
		}else{
			ga('send','event','Rechazo Vehículos Similares',coche + "/" + matricula);
		}


	});


	$("#comprarYaModalEvent_JS").on('click',function(){

		matricula = $("#matricula_JS").val();
		coche = $("#nombre_coche_JS").val();

		if($("#amountOverModal").val() == "true"  ){
			ga('send','event','Cercano Comprar ya',coche + "/" + matricula);
		}else{
			ga('send','event','Rechazo Comprar ya',coche + "/" + matricula);
		}


	});

	$(".rechazoNuevaOfertaModalEvent_JS").on('click',function(){


		matricula = $("#matricula_JS").val();
		coche = $("#nombre_coche_JS").val();
		if($("#amountOverModal").val()  == "true"  ){
			ga('send','event','Cercano Incrementar Oferta',coche + "/" + matricula);
		}else{
			ga('send','event','Rechazo Nueva Oferta',coche + "/" + matricula);
		}
	});



	$(".clicDepositarSenal_JS").on('click',function(){
		matricula = $("#matricula_JS").val();
		coche = $("#nombre_coche_JS").val();
		ga('send','event',' Depositar señal',coche + "/" + matricula);

	});
	// Clic Vehículos Similares




	/* */
	$("#wahtsappEvent_JS").on('click',function(){
		eventGa("Compartir Whatsapp");
	});

	$("#facebookEvent_JS").on('click',function(){
		eventGa("Compartir Facebook");
	});

	$("#emailEvent_JS").on('click',function(){
		eventGa("Compartir Email");
	});

	$("#carfaxEvent_JS").on('click',function(){
		eventGa("Solicitar informe Carfax");
	});

	$("#comprarYaEvent_JS").on('click',function(){
		eventGa("Comprar ya inicio");
	});





	/* Pilar ha pedido el 31-05-22 que se retiren

	quitar  Contraofertar inicio
	-Este evento se lanzaría cada vez que un usuario haga clic en el botón "CONTRAOFERTAR" situado debajo de la opción "CONTRAOFERTAR", en la ficha de los vehículos de venta directa




		$("#contraofertarEvent_JS").on('click',function(){
			if($("#counteroffer-input").val() !=""){
				eventGa("Contraofertar inicio");
			}

		});
	*/
	$("#pujaAutomaticaEvent_JS").on('click',function(){
		eventGa("Puja automática inicio");
	});

	$("#pujaEvent_JS").on('click',function(){
		eventGa("Puja manual inicio");
	});

	$("#emailEventProfesionales_JS").on('click',function(){
		ga('send','event','ACCIONES NO LEADS PROFESIONALES','Clic Email');

	});

	$("#buscadorHomeEvent_JS").on('click',function(){
		ga('send','event','ACCIONES NO LEADS HOME','Buscador Home');

	});

	$("#stockVentadirectaEvent_JS").on('click',function(){
		ga('send','event','ACCIONES NO LEADS HOME','Botón stock Venta Directa');
	});

	$("#stockSubastaEvent_JS").on('click',function(){
		ga('send','event','ACCIONES NO LEADS HOME',' Botón stock Subasta');
	});


	$('.lot-action_comprar_lot').off('click');
	$('.lot-action_comprar_lot').on('click', lotActionComprar);
	$('.closedd_fichalogin').on('click', cerrarFichaLogin);

	$("#accerder-user-ficha-form input[name='password']").on('keyup', function (e) {
		if (e.keyCode == 13) {
			$("#accerder-user").click()
		}
	});
	$("#accerder-ficha-user").click(function () {
		$(this).addClass('loadbtn')
		$('.login-content-form').removeClass('animationShaker')
		$.ajax({
			type: "POST",
			url: '/login_post_ajax',
			data: $('#accerder-user-ficha-form').serialize(),
			success: function (response) {
				if (response.status == 'success') {
					location.reload();
				} else {
					$(".message-error-log").text('').append(messages.error[response.msg]);
					$("#accerder-ficha-user").removeClass('loadbtn')
					$('.login-content-form').addClass('animationShaker')
				}
			}
		});
	});

	$(document).off('scroll');
	$(document).on('scroll', function (e) {

		//Por el momento elminamos el menu en scroll
		/* if ($(document).scrollTop() > 33) {
			$('header').addClass('fixed w-100 top-0 left-0')

		}
		if ($(document).scrollTop() <= 33) {
			$('header').removeClass('fixed w-100 top-0 left-0')

		} */
		if ($(document).scrollTop() > 100) {
			$('.button-up').show(500);		}
		if ($(document).scrollTop() <= 100) {
			$('.button-up').hide(500)
		}

		/**
		 * Comportamiento de navs de features en ficha lote
		 *  */
		if ($(document).scrollTop() > 780) {
			document.getElementById('data-container')?.prepend(document.getElementById('nav-positions'));
		}
		if ($(document).scrollTop() <= 780) {
			document.getElementById('galery-container')?.insertBefore(document.getElementById('nav-positions'), document.getElementById('thumnail-container'));
		}
	});

	//Filtros cerrados en mobile y tablet, abiertos en desktop
	if ($('#collapse_filter').length > 0 && (window.innerWidth >= 992 || Boolean(getQueryParams('category')))){
		$('#collapse_filter').collapse('show');
		$('[data-target="#collapse_filter"] i').removeClass('fa-plus').addClass('fa-minus');
	}


	/**
	 * Obtiene la úlitma posicion del slider guardada, y en caso de abrirse situa
	 * esa posición en él
	 */
	sidebar = document.querySelector(".auction-lots-view");
	$('#collapse_filter').on('shown.bs.collapse', function (e){

		if(e.target.id != 'collapse_filter'){
			return;
		}

		let top = localStorage.getItem("sidebar-scroll");
		if (top !== null) {
			sidebar.scrollTop = parseInt(top, 10);
		}
	})

	$('.collapse-js').on('show.bs.collapse', function (e) {

		const element = e.target.closest('.filters-auction-content').querySelector('#js-collapse_simbol > i');
		if(element){
			$(element).removeClass('fa-plus').addClass('fa-minus');
		}

	});

	$('.collapse-js').on('hide.bs.collapse', function (e) {
		const element = e.target.closest('.filters-auction-content').querySelector('#js-collapse_simbol > i');

		if(!e.target.classList.contains('collapse-js') || e.target.classList.contains('auction__filters-type-list') || e.target.classList.contains('order_filter_group')){
			return;
		}

		if(element){
			$(element).removeClass('fa-minus').addClass('fa-plus');
		}
	});

	$('i.js-info-modal').on('click', (e) => openModal(e));

	$('i.js-grid-modal, i.js-modal').on('click', (e) => openGridModal(e));

	$('.lot-action_contraofertar').on('click', (e) => contraOfertar(e));

	$('select[name="category"]').on('change', e => changeSubCategoriesSelect(e));

	$('.custom_btn_login').on('click', contraofertarLogin);

	$('.btn_login').off('click')
	$('.btn_login').on('click', function () {
		$('.login_desktop').fadeToggle("fast");
		$('.login_desktop [name=email]').focus();
		$('.login-register-block').show();
	});

	//Solo estamos utilizando el history.state en la ficha, si se llega a utilizar en otro lugar deberemos checkear condiciones
	if(Boolean(history.state)){
		$("#counteroffer-input").val(history.state.counterofferValue);

		/* const historyData = Object.assign({}, history.state); */

		if(!cod_licit) {
			reatryCounteroffer(history.state);
			return;
		}

		history.replaceState(null, '');
		window.contraofertarLoteFicha();
	}

	$('.focus-counteroffer').on('click', function (e) {
		const element = document.getElementById("counteroffer-input");
		element.setAttribute("tabindex", "1");
		$.magnificPopup.close();
		element.focus();
	});

});

function getQueryParams(paramName){
	const query = location.search;
	const params = new URLSearchParams(query);
	return params.get(paramName);
}


function cerrarFichaLogin() {
	$('.ficha_login_desktop').fadeToggle("fast");
}

function licitIsLogin(){
	if (typeof cod_licit == 'undefined' || cod_licit == null){
		return null;
	}

	return cod_licit;
}

function lotActionComprar(e) {
	e.stopPropagation();
	$.magnificPopup.close();

	if (!licitIsLogin()){

		$('.ficha_login_desktop').fadeToggle("fast");
		$('.ficha_login_desktop [name=email]').trigger('focus');
		return;
	}

	$.magnificPopup.open({ items: { src: '#modalComprarFicha' }, type: 'inline' }, 0);
	return;
}

function openGridModal(event){
	const title = event.target.dataset.title;
	const content = event.target.dataset.content;
	const modal = document.getElementById('modalAjax');

	modal.querySelector('.modal-title').textContent = title;
	modal.querySelector('.modal-body').innerHTML = content;

	$(modal).modal('show');
}

/**
 * En caso de existir el slider, antes de abandonar la página guardamos el estado
 * del slider
*/
window.addEventListener("beforeunload", () => {
	localStorage.setItem("sidebar-scroll", sidebar?.scrollTop);
});

window.contraofertarLoteFicha = function() {

	const imp = $("#counteroffer-input").val();
	const ruta = '/es/api/contraofertar/subasta';

	$.ajax({
        type: "POST",
        url:  ruta + '-' + cod_sub,
        data: {cod_sub, ref, imp, cod_licit},
        success: function( data ) {

            $("#insert_msg").html("");

            if (data.status == 'error'){

                $("#insert_msg").html(data.msg_1);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'});

            }else if(data.status == 'success'){

				//añadir eventos compra ya venta directa
				matricula = $("#matricula_JS").val();
				precio =$("#price_compra_ya_JS").val();
				coche = $("#nombre_coche_JS").val();
				ga('send','event','FORMULARIO HAZ TU OFERTA',coche  + "/" +  matricula,precio);
				//evento fbq
				fbq('track', 'Lead', {value: 1,  });

				//Si la contraoferta ha sido rechazada
				if(data.pujarep == 'K') {
					$("#modalContraofertaRechazada .insert_msg").html(data.msg);
					$("#amountOverModal").val(data.amountOver);

					const btnSimiliarLots = document.getElementById('btn-similares-modal');
					const btnFocusCounteroffer = document.getElementById('btn-focus-counteroffer');
					const buttonComprarModal = document.querySelector('#comprarYaModalEvent_JS');

					buttonComprarModal.dataset.amountOver = data.amountOver;

					//Eliminamos la posibilidad de que el botón tenga el evento asignado con anterioridad
					btnSimiliarLots.removeEventListener('click', waitForAnswer);

					if(data.amountOver) {
						btnSimiliarLots.addEventListener('click', waitForAnswer);
						btnSimiliarLots.href = '';
						btnSimiliarLots.textContent = 'ESPERAR RESPUESTA';
						btnFocusCounteroffer.textContent = 'INCREMENTAR OFERTA';

					}
					else {
						btnSimiliarLots.href = data.similar_lots;
						btnSimiliarLots.textContent = 'VEHÍCULOS SIMILARES';
						btnFocusCounteroffer.textContent = 'HACER NUEVA OFERTA';
					}

					$.magnificPopup.open({items: {src: '#modalContraofertaRechazada'}, type: 'inline'}, 0);
					return;
				}


                $("#insert_msg").html(data.msg);
                $.magnificPopup.open({
					items: {src: '#modalMensaje'},
					type: 'inline',
					callbacks: {
						afterClose: () => reatryPayDeposit(data),
					}
				}, 0);
            }
        },
		error: function(error){
			$("#insert_msg").html(messages.error.counteroffer);
			$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
		}

    });
}

window.comprarLoteFichaCarlandia = function()
{
	const buttonComprar = document.querySelector('#comprarYaEvent_JS');
	const typePuja = buttonComprar.dataset.typePuja;

	const buttonComprarModal = document.querySelector('#comprarYaModalEvent_JS');
	const amountOver = buttonComprarModal.dataset.amountOver;

	const ruta = '/es/api/comprar-aux/subasta';

    $.ajax({
        type: "POST",
        url: `${ruta}-${cod_sub}`,
        data: { cod_sub: cod_sub, ref: ref, 'type-puja': typePuja },
        success: function( data ) {

            $("#insert_msg").html("");

            if (data.status == 'error'){
				$("#insert_msg").html(data.msg_1);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'});
				return;
            }
			//añadir eventos compra ya venta directa
				matricula = $("#matricula_JS").val();
				precio =$("#price_compra_ya_JS").val();
				coche = $("#nombre_coche_JS").val();
				if(typePuja=='B'){
					ga('send','event','FORMULARIO VENTA DIRECTA COMPRAR YA',coche  + "/" +  matricula,precio);
				}else if(typePuja=='Y'){
					ga('send','event','FORMULARIO SUBASTA COMPRAR YA',coche  + "/" +  matricula,precio);
				}
			//evento fbq
				fbq('track', 'Lead', {value: 1,  });

			$("#insert_msg").html(data.msg);

			const depositarButton = document.querySelector('#depositarSeñalComprar_JS');
			if(depositarButton) {
				depositarButton.removeEventListener('click', (event) => depositarSenal(event, amountOver, typePuja));
				depositarButton.addEventListener('click', (event) => depositarSenal(event, amountOver, typePuja) );
			}

			$.magnificPopup.open({
				items: {src: '#modalMensaje'},
				type: 'inline',
				callbacks: {
					afterClose: () => reatryPayDeposit(data),
				}
			}, 0);


			return;

        },
		error: function (e) {
			$("#insert_msg").html(messages.error.buying);
			$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'});
		}
    });
};

function depositarSenal(event, amountOver, typePuja) {
	event.preventDefault();

	const matricula = $("#matricula_JS").val();
	const coche = $("#nombre_coche_JS").val();

	const isOnline = typePuja === 'Y';
	const textEvent = (isOnline, amountOver) => {
		if(isOnline) return 'Depositar señal';
		if(!isOnline && amountOver === "true") return 'Cercano Depositar señal';
		if(!isOnline && amountOver === "false") return 'Rechazo Depositar señal';
	}

	ga('send', 'event', textEvent(isOnline, amountOver), coche + "/" + matricula);

	window.location.href = event.target.href;
}

function waitForAnswer(e){
	e.preventDefault();
	$.magnificPopup.close();
	$("#insert_msg").html('Te informaremos sobre la decisión del vendedor cuanto antes.<br>Recuerda que en ese plazo, el vehículo podrá ser vendido a otro comprador.');
	setTimeout(function(){
		$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
	} ,200);
}

function contraOfertar(event){
	event.stopPropagation();
	$.magnificPopup.close();

	if (typeof cod_licit == 'undefined' || cod_licit == null) {
		sendCounterofferWithNotUser();
		return;
	}

	$('#counteroffer_value').html($('#counteroffer-input').val());
	$.magnificPopup.open({ items: { src: '#modalContraofertarFicha' }, type: 'inline' }, 0);
	return;
}

function sendCounterofferWithNotUser(){
	const counterofferValue = parseInt($('#counteroffer-input').val());

	if(isNaN(counterofferValue) || counterofferValue <= 0){
		$('#insert_msg').html(messages.error.counteroffer);
		$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
		return;
	}

	fetchWrapper(`/es/api/check-contraofertar/subasta-${cod_sub}`, 'POST', {cod_sub, ref, imp: counterofferValue})
		.then(data => {
			if (data.status == 'error') {
				$('#insert_msg').html(data.message);
				$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
				return;
			}
			//la url marca en que url se podran recuperar los datos, si no pongo nada los recuperaremos al volver aquí
			//history.pushState(state, '', e.target.href);
			history.pushState(data, '');

			const loginButton = document.querySelector('#modal-contraofertarSinLicitador');
			const url = new URL(loginButton.href);
			url.searchParams.set('counterofferType', data.counterofferType);
			url.searchParams.set('amountOverOriginalValue', data.amountOverOriginalValue.toString());
			loginButton.href = url.toString();

			$('#modalContraofertarSinLicitador .insert_msg').html(data.message);

			$.magnificPopup.open(
				{
					items: { src: '#modalContraofertarSinLicitador' },
					type: 'inline',
					callbacks: {afterClose: () => reatryCounteroffer(data)}
				}, 0);
			return;
		})
		.catch(error => {
			$('#insert_msg').html(messages.error.counteroffer);
			$.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
			return;
		});
}

function reatryCounteroffer({amountOverOriginalValue, messageToCancel, urlSimilar}){
	//Si se muestra el login, aunque se cierre el modal no debemos mostrar el segundo modal
	if(document.querySelector('.login_desktop')?.style.display != 'none'){
		return;
	}

	$('#modalCerrarContraofertar .insert_msg').html(messageToCancel);
	$('#modalLinkRegister').show();
	$('#modalLinkDeposit').hide();

	const similarLotsBlock = document.querySelector('.similiar-lots');

	similarLotsBlock.classList.toggle('hidden', !amountOverOriginalValue);
	similarLotsBlock.querySelector('a').setAttribute('href', urlSimilar);

	$.magnificPopup.open({
		items: { src: '#modalCerrarContraofertar' },
		type: 'inline',
	}, 0);
}

function reatryPayDeposit({messageToCancel, payLink}){

	$('#modalLinkRegister').hide();
	$('#modalLinkDeposit').attr('href', payLink).show();
	$('.similiar-lots').hide();
	$('#modalCerrarContraofertar .insert_msg').html(messageToCancel);
	$.magnificPopup.open({
		items: { src: '#modalCerrarContraofertar' },
		type: 'inline',
	}, 0);
}

function contraofertarLogin(e){
	e.stopPropagation();
	$('.login-register-block').hide();
	$('.login_desktop').fadeToggle("fast");
	$('.login_desktop [name=email]').trigger('focus');
	$.magnificPopup.close();
}

function fetchWrapper(url, method, body) {
	return fetch(url, {
		method: method,
		body: body ? JSON.stringify(body) : null,
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		},
	})
	.then(response => {
		if (response.status >= 400 || !response.ok) {
			throw new Error(response.statusText);
		}
		return response.json();
	});
}



function openModal(event){

	const element = event.target;
	const idModal = element.dataset.modal;

	$.magnificPopup.open({ items: { src: `#${idModal}` }, type: 'inline' }, 0);
}

function changeSubCategoriesSelect(event) {

	const linOrtsec = event.target.value;

	const subCategorySelect = document.querySelector("[name='section']");

	deleteSelect(subCategorySelect, true);

	if (linOrtsec == '') {
		return false;
	}

	subCategories.filter(subCatergory => subCatergory.lin_ortsec1 == linOrtsec)
		.forEach(subCatergory => {
			const option = new Option(subCatergory.des_sec, subCatergory.cod_sec);
			subCategorySelect.add(option, undefined);
		});

	return true;
}

function deleteSelect(select, saveFirst) {
	while (select.options.length > saveFirst ? 1 : 0) {
        select.remove(saveFirst ? 1 : 0);
    }
}

function sendContactCarlandia() {

	$(".g-recaptcha").find("iframe").removeClass("has-error");

	response = $("#g-recaptcha-response").val();

	if (response) {
		$.ajax({
			type: "POST",
			url: "/contactSendmail",
			data: $(contactForm).serialize(),
			success: function (response) {
				if (response.status == "error") {
					showMessage(response.message);
				} else {
					//evento ga
						ga('send','event','FORMULARIO GENERAL','Formulario general');
					//evento fbq
						fbq('track', 'Lead', {value: 1,  });
					showMessage(response, "");
					setTimeout("location.reload()", 4000);
				}
			},
			error: function (response) {
				showMessage("Error");
			}
		});
	} else {
		$(".g-recaptcha").find("iframe").addClass("has-error");
		showMessage(messages.error.hasErrors);
	}
}
function device(){
	if( navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/BlackBerry/i)||  navigator.userAgent.match(/Windows Phone/i) || navigator.userAgent.match(/iPod/i)){
		return "mobile";
	}else if ( navigator.userAgent.match(/iPad/i)) {
		return "tablet";
	}else{
		return "desktop";
	}
}

/* function sendBuyOnlineLot() {

	//abre directamente la ventana emergente de login
	if (typeof cod_licit == 'undefined' || cod_licit == null) {
		$('.login_desktop').fadeToggle("fast");
		$('.login_desktop [name=email]').trigger('focus');
		return;
	}

	$.ajax({
		type: "POST",
		data: $("#buyLotForm").serialize(),
		url: '/es/make-offer',
		success: function (res) {
			showMessage(messages.success.thanks);
		},
		error: function (e) {
			showMessage(messages.error.makeoffer_error);
		}
	});

} */
