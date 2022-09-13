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

		calcShippingCosts($(this).data("sub"));
	});

	$(".check_seguro_js").on("click", function (){

		calcCostToPay($(this).data("sub"));
	});

	$('.change_envio_carrito_js').on("click",function () {
		sub = $(this).data("sub")

		if($(this).val() == 1){
			//envio a dirección

			$("#seguro_carrito_" + sub +"_js").removeAttr("disabled");
		}else{
			//recoge cliente

			$("#seguro_carrito_" + sub +"_js").prop("checked", false);
			$("#seguro_carrito_" + sub +"_js").attr("disabled", "disabled");
		}
		calcCostToPay(sub);
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






function calcShippingCosts(codsub){
	token = $('input[name="_token"]').val();

	$.ajax({
		type: "POST",
		url: "/shippingCostsCart",
		data: { _token: token, clidd_carrito: $('#clidd_carrito_' + codsub).val()},
		success: function (response) {

			if(response.status == "success"){

				$("#envioPosible_carrito_" + codsub + "_js").removeClass("hidden");
				$("#envioNoDisponible_carrito_" + codsub + "_js").addClass("hidden");
				$("#seguro_carrito_" + codsub + "_js").removeAttr("disabled");
				formattedCosts = new Intl.NumberFormat("de", {minimumFractionDigits: 2}).format(parseFloat(response.costs));
				$("#coste-envio-carrito_" + codsub + "_js").text(formattedCosts);
				$("#gastosEnvio_" + codsub + "_JS").val(response.costs);
				calcCostToPay(codsub);
			}else if(response.status == "error"){
				$("#envioPosible_carrito_" + codsub + "_js").addClass("hidden");
				$("#envioNoDisponible_carrito_" + codsub + "_js").removeClass("hidden");
				$("#seguro_carrito_" + codsub + "_js").prop("checked", false);
				$("#recogida_almacen_carrito_js").prop("checked", true);
				$("#seguro_carrito_" + codsub + "_js").removeAttr("disabled");
				$("#coste-envio-carrito_" + codsub + "_js").text(0);
				$("#gastosEnvio_" + codsub + "_JS").val(0);
				calcCostToPay(codsub);
			}
		}
	});
}
//new Intl.NumberFormat("de", {}).format(auction_info.lote_actual.importe_escalado_siguiente)
function calcCostToPay(codsub){
	console.log(codsub);
	total = parseFloat($("#totalLotes_" + codsub + "_JS").val());


	if( $("#seguro_carrito_" + codsub + "_js").prop("checked")){
		total +=parseFloat($("#seguro_" + codsub + "_JS").val());
	}

	if( $("#envio_agencia_carrito_" + codsub + "_js").prop("checked")){
		total +=parseFloat($("#gastosEnvio_" + codsub + "_JS").val());
	}

	formattedTotal = new Intl.NumberFormat("de", {minimumFractionDigits: 2, maximumFractionDigits: 2,style: 'currency', currency: 'EUR'}).format(parseFloat(total));
	$(".precio_final_carrito_" + codsub).html( formattedTotal )
}




function sendShoppingCart(cod_sub){

	$( ".submitShoppingCart_JS" ).html("<div class='loader mini' style='width: 20px;height: 20px;margin-top: 0px;margin-bottom: 0;'></div>");
//	$( ".submitShoppingCart_JS" ).attr("disabled", "disabled");

   var pay_lote = $('#pagar_lotes_'+cod_sub).serialize();

   $.ajax({
		type: "POST",
		url:  '/shoppingCart/pay?codSub='+cod_sub,
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
