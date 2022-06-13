$(function() {

	$(".addShippingCart_JS").on("click", function(e){

		if (typeof cod_licit == 'undefined' || cod_licit == null )
		{
			$("#insert_msg_title").html("");
			$("#insert_msg").html(messages.error.mustLogin);
			$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);

		}else{

			addLot(cod_sub, ref)
		}
	});
	$(".deleteLot_JS").on("click", function(e){
			deleteLot($(this).data("sub"), $(this).data("ref"), true);
	});



	$('.change_address_carrito_js').on("change",function () {
		calcShippingCosts();
	});

	$("#seguro_carrito_js").on("click", function (){
		calcCostToPay();
	});

	$('.change_envio_carrito_js').on("click",function () {

		if($(this).val() == 1){
			//envio a dirección

			$("#seguro_carrito_js").removeAttr("disabled");
		}else{
			//recoge cliente

			$("#seguro_carrito_js").prop("checked", false);
			$("#seguro_carrito_js").attr("disabled", "disabled");
		}
		calcCostToPay();
	});

	$( ".submitShoppingCart_JS" ).on("click",function() {
		if($("#acceptCheck").is(':checked')){
			sendShoppingCart($(this).attr('cod_sub'));
		}else{
			$("#insert_msg").html(messages.neutral["accept_condiciones"]);
			$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
		}
    });
})


function addLot(cod_sub, ref){
	token = $('input[name="_token"]').val();

	$.ajax({
		type: "POST",
		url: "/addLotCart",
		data: { _token: token , codSub: cod_sub, ref: ref},
		success: function (response) {

			if(response.status == "success"){
				$("#msg_title_ShoppingCart").html(messages.success["add_cart"]);
				$.magnificPopup.open({ items: { src: '#modalShoppingCart' }, type: 'inline' }, 0);
			}else if (response.status == 'error') {
				$("#insert_msg").html(messages.error[response.errorMsg]);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
			}

		}
	});
}

function deleteLot(cod_sub, ref, reload){
	token = $('input[name="_token"]').val();
	$.ajax({
		type: "POST",
		url: "/deleteLotCart",
		data: { _token: token, codSub: cod_sub, ref: ref},
		success: function (response) {

			if(response.status == "success"){
				$("#insert_msgweb").html(messages.success["delete_lot_cart"]);
				$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
				if(reload){
					setTimeout(function (){
						location.reload()
					},1500);
				}
			}else if (response.status == 'error') {
				$("#insert_msgweb").html(messages.error["delete_lot_cart"]);
				$.magnificPopup.open({ items: { src: '#modalMensajeWeb' }, type: 'inline' }, 0);
			}
		}
	});
}






function calcShippingCosts(){
	token = $('input[name="_token"]').val();

	$.ajax({
		type: "POST",
		url: "/shippingCostsCart",
		data: { _token: token, clidd_carrito: $('#clidd_carrito').val()},
		success: function (response) {

			if(response.status == "success"){

				$("#envioPosible_carrito_js").removeClass("hidden");
				$("#envioNoDisponible_carrito_js").addClass("hidden");
				$("#seguro_carrito_js").removeAttr("disabled");
				formattedCosts = new Intl.NumberFormat("de", {minimumFractionDigits: 2}).format(parseFloat(response.costs));
				$("#coste-envio-carrito_js").text(formattedCosts);
				$("#gastosEnvio_JS").val(response.costs);
				calcCostToPay();
			}else if(response.status == "error"){
				$("#envioPosible_carrito_js").addClass("hidden");
				$("#envioNoDisponible_carrito_js").removeClass("hidden");
				$("#seguro_carrito_js").prop("checked", false);
				$("#recogida_almacen_carrito_js").prop("checked", true);
				$("#seguro_carrito_js").removeAttr("disabled");
				$("#coste-envio-carrito_js").text(0);
				$("#gastosEnvio_JS").val(0);
				calcCostToPay();
			}
		}
	});
}
//new Intl.NumberFormat("de", {}).format(auction_info.lote_actual.importe_escalado_siguiente)
function calcCostToPay(){
	total = parseFloat($("#totalLotes_JS").val());


	if( $("#seguro_carrito_js").prop("checked")){
		total +=parseFloat($("#seguro_JS").val());
	}

	if( $("#envio_agencia_carrito_js").prop("checked")){
		total +=parseFloat($("#gastosEnvio_JS").val());
	}

	formattedTotal = new Intl.NumberFormat("de", {minimumFractionDigits: 2, maximumFractionDigits: 2,style: 'currency', currency: 'EUR'}).format(parseFloat(total));
	$(".precio_final_carrito").html( formattedTotal )
}




function sendShoppingCart(cod_sub){

	$( ".submitShoppingCart_JS" ).html("<div class='loader mini' style='width: 20px;height: 20px;margin-top: 0px;margin-bottom: 0;'></div>");
//	$( ".submitShoppingCart_JS" ).attr("disabled", "disabled");

   var pay_lote = $('#pagar_lotes_'+cod_sub).serialize();

   $.ajax({
		type: "POST",
		url:  '/shoppingCart/pay',
		data: pay_lote,
		success: function(data) {
			if(data.status == 'success'){
				window.location.href = data.location;
			}else if(data.status == 'error'){
				$("#modalMensaje #insert_msg").html(messages.error[data.msgError]);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				$( ".submitShoppingCart_JS" ).html(" ");
				$( ".submitShoppingCart_JS" ).prop("disabled", false);
				if(data.msgError == "lotsLost"){
					setTimeout(function (){
						location.reload()
					},1500);
				}
			}
		},
		error: function (response){
			$("#modalMensaje #insert_msg").html('');
			$("#modalMensaje #insert_msg").html(messages.error.generic);
			$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
		}
	});
}
