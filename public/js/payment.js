

$(function (){
    $('.add-carrito').on("change",function () {
        reload_carrito();
	});

	$('.change_address_js').on("change",function () {
		index_sub=$(this).data("sub");
		//primero se debe cargar el precio ya que puede ser que no sea enviable
		show_gastos_envio(index_sub);
        reload_carrito_subasta(index_sub, info_lots[index_sub]);
	});
	//si cambian a envio o a recoger

	$('.change_envio_js').on("click",function () {
		index_sub=$(this).data("sub");
		//primero se debe cargar el precio ya que puede ser que no sea enviable
		reload_carrito_subasta(index_sub, info_lots[index_sub]);

		if($(this).val() == 1){
			//envio a dirección
			envio = 5;
			$("#seguro_" + index_sub+ "_js").removeAttr("disabled");
		}else{
			//recoge cliente
			envio = 1;
			$("#seguro_" + index_sub+ "_js").prop("checked", false);
			$("#seguro_" + index_sub+ "_js").attr("disabled", "disabled");
		}
		$(".envios_" + index_sub+ "_js").val(envio);
	});

	$(".check_seguro_js").on("click", function (){
		index_sub = $(this).data("sub");

		if ($(this).is(":checked")) {
			$(".seguro_lote_" + index_sub+ "_js").val(1);
		}else{
			$(".seguro_lote_" + index_sub+ "_js").val(0);
		}


        reload_carrito_subasta(index_sub, info_lots[index_sub]);
	})




    $( ".submit_carrito" ).on("click",function() {
		sendCarrito($(this).attr('cod_sub'));

    });
});

function sendCarrito(cod_sub){

	$( ".submit_carrito" ).html("<div class='loader mini' style='width: 20px;height: 20px;margin-top: 0px;margin-bottom: 0;'></div>");
	$( ".submit_carrito" ).attr("disabled", "disabled");

   var pay_lote = $('#pagar_lotes_'+cod_sub).serialize();

   $.ajax({
		type: "POST",
		url:  '/gateway/pagarLotesWeb',
		data: pay_lote,
		success: function(data) {
			if(data.status == 'success'){
				window.location.href = data.msg;
			}else if(data.status == 'error'){
				$("#modalMensaje #insert_msg").html('');
				$("#modalMensaje #insert_msg").html(messages.error.generic);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				$( ".submit_carrito" ).html(" ");
				$( ".submit_carrito" ).prop("disabled", false);
			}
		},
		error: function (response){
			$("#modalMensaje #insert_msg").html('');
			$("#modalMensaje #insert_msg").html(messages.error.generic);
			$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
		}
	});
}

function reload_carrito() {

	var precio_global = 0;
	var imp_iva = 0;
	var precio_sin_iva = 0;

    $.each(info_lots, function (index_sub, value_sub) {
		subasta = reload_carrito_subasta(index_sub, value_sub);
        precio_global = precio_global + subasta.precio_final;
        imp_iva = imp_iva + subasta.imp_iva;
        precio_sin_iva = precio_sin_iva + subasta.precio_total;
    });
	$(".iva_final_global").text(imp_iva.toFixed(2).replace(".", ","));
	$(".precio_final_remate_global").text(precio_sin_iva.toFixed(2).replace(".", ","));
	$(".precio_final_global").text(precio_global.toFixed(2).replace(".", ","));
     if (precio_global <= 0) {
            $('.submit_carrito').attr("disabled", "disabled");
        } else {
            $('.submit_carrito').removeAttr("disabled");
        }
}

function reload_carrito_subasta(index_sub, value_sub){
	var precio_envio = 0;
	var sum_precio_envio = 0;
	var precio_total = 0;
	var precio_seguro = 0;
	var imp_iva = 0;
	var precio_base = 0;
	$.each(value_sub.lots, function (index, value) {
		if ($("#add-carrito-" + index_sub + "-" + index + "").is(":checked")) {
			precio_base = precio_base + value.base;

			precio_total = precio_total + value.himp + value.base;
			sum_precio_envio = sum_precio_envio + value.himp + value.base;

		}

	});
	imp_iva =  ( precio_base * (info_lots.iva/100));
	precio_total_iva = precio_total + imp_iva;
	if (sum_precio_envio > 0) {
		//si existe la opción de recogida por parte del usuario y está seleccionada
		if ($("#recogida_almacen_" + index_sub + "_js").is(":checked")) {
			precio_envio = 0;
		}else{
			precio_envio = calc_gastos_envio(sum_precio_envio, index_sub);
			if( $("#seguro_" + index_sub +"_js").is(":checked")){
				precio_seguro = calc_seguro_envio(index_sub);
			}
		}

	}
	//mostrar coste seguro



	$(".text-gasto-envio-" + index_sub).text(precio_envio.toFixed(2).replace(".", ","));
	precio_final = parseFloat(precio_total_iva) + parseFloat(precio_envio) + parseFloat(precio_seguro);


	if(typeof extraCharge != 'undefined'){
		precio_final += precio_final * (extraCharge/100);

	}

	$(".iva_final_" + index_sub).text(imp_iva.toFixed(2).replace(".", ","));
	$(".precio_final_remate_" + index_sub).text(precio_total.toFixed(2).replace(".", ","));
	$(".precio_final_" + index_sub).text(precio_final.toFixed(2).replace(".", ","));

	if (precio_final <= 0) {
		$('.submit_carrito[cod_sub="' + index_sub + '"]').attr("disabled", "disabled");
	} else {
		$('.submit_carrito[cod_sub="' + index_sub + '"]').removeAttr("disabled");
	}
	var subasta = {
		precio_final: precio_final,
		imp_iva: imp_iva,
		precio_total: precio_total,

	 }

	return subasta;
}


function calc_gastos_envio(sum_precio_envio, cod_sub){
	var precio_envio =0;

	$.ajax({
		type: "POST",
		async: false,
		url: '/api-ajax/gastos_envio',
		data: { 'precio_envio': sum_precio_envio, cod_sub :  cod_sub, ['clidd_' + cod_sub] : $("#clidd_"+ cod_sub).val()},
		success: function (data) {
			precio_envio = data.imp + data.iva;
			coste_envio = data.impSeguro + data.ivaSeguro;
			//$("#coste-seguro-" + cod_sub + "_js").text (coste_envio.toFixed(2).replace(".", ","));
		}
	});

	//para los

	return precio_envio;
}

function show_gastos_envio( cod_sub, codd_clid){
	precio_envio = calc_gastos_envio(0, cod_sub, codd_clid);
	//no se puede enviar
	if(precio_envio == -1){

		$("#envioPosible_" + cod_sub + "_js").addClass("hidden");
		$("#envioNoDisponible_" + cod_sub + "_js").removeClass("hidden");
		$("#coste-envio-" + cod_sub + "_js").text(0);
		$("#seguro_" + cod_sub+ "_js").prop("checked", false);
		$("#recogida_almacen_" + cod_sub + "_js").prop('checked',true);

		$("#seguro_" + index_sub+ "_js").attr("disabled", "disabled");
	}else{
		$("#envioPosible_" + cod_sub + "_js").removeClass("hidden");
		$("#envioNoDisponible_" + cod_sub + "_js").addClass("hidden");
		$("#coste-envio-" + cod_sub + "_js").text(precio_envio.toFixed(2).replace(".", ","));
		$("#seguro_" + cod_sub+ "_js").removeAttr("disabled");
	}

}

function show_seguro_envio(cod_sub){
	seguro = calc_seguro_envio(cod_sub);

	$("#coste-seguro-" + cod_sub + "_js").text(seguro.toFixed(2).replace(".", ","));
}

function calc_seguro_envio(cod_sub ){
	porcentaje = $("#porcentaje-seguro-" + cod_sub + "_js").val();
	ivaAplicable = $("#iva_aplicable-" + cod_sub + "_js").val();
	precio_final=0;
	lotes = info_lots[cod_sub]["lots"];
	$.each(lotes, function (index, value) {
		if ($("#add-carrito-" + cod_sub + "-" + index + "").is(":checked")) {
			//sumamosl os precios del lotes
			precio_final = precio_final + value.himp + value.iva + value.base;
		}
	});

	seguro = Math.round(precio_final * porcentaje) / 100;
	tax_seguro =  (seguro * ivaAplicable ) ;
	totalSeguro = seguro + tax_seguro;
	return totalSeguro;
}
