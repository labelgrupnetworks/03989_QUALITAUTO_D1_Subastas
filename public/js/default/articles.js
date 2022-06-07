

$(function() {

	$(".PrincipalTallaColor_JS").on("change", function(e){
		nameSelect = $(this).attr("name");
		reload = false;
		$(".tallaColor_JS ").each(function(){
			if($(this).attr("name") != nameSelect){
				reload = true;
				$(this).val("");
				$(this).parent().removeClass("hidden");
			}

		})

		//si hay mas de un selector recargamos
		if(reload){
			getTallasColoresFicha();
		}

	});

	$(".tallaColor_JS").on("change", function(e){

		//los valores negativos indican que no hay stock
		if($(this).val() < 0){
			$(".siStock_JS").addClass("hidden");
			$(".noStock_JS").removeClass("hidden");
		}else{
			$(".noStock_JS").addClass("hidden");
			$(".siStock_JS").removeClass("hidden");
		}
		/* poner precio correcto */

		if($(this).val() == "" ){

			$(".art-price_JS").addClass("hide");
			$("#art-original_JS").removeClass("hide");
		}else{
			id=  parseInt($(this).val());
			/* convertimos el valor en positivo */
			if(id<0){
				id= id * -1;
			}
			$(".art-price_JS").addClass("hide");
			$("#art-"+ id + "_JS").removeClass("hide");
		}
	});


	$(".addArticleCard_JS").on("click", function(e){

		if (typeof logged == 'undefined' || logged == false )
		{
			$("#insert_msg_title").html("");
			$("#insert_msg").html(messages.error.mustLoginArticle);
			$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);

		}else{
			var empty = false;
			$(".tallaColor_JS").each(function () {
				console.log($(this).val());
				if( $(this).val() ==""){
					empty = true;

				}
			});

			if(!empty){
				addArticle();
			}else{
				/* Mostrar frase de debe elegir todas las caracteristicas */

				$("#insert_msg_title").html("");
				$("#insert_msg").html(messages.error.notVarianteArticle);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
			}

		}
	});

	$(".deleteArticle_JS").on("click", function(e){
		deleteArticle($(this).data("idart"),true);
	});


	$( ".submitArticleCart_JS" ).on("click",function() {
		sendArticleCart();
    });

});



function getTallasColoresFicha(){
	token = $('input[name="_token"]').val();

	$.ajax({
		type: "POST",
		url:  "/" + lang  + "/getTallasColoresFicha",
		data:  $("#articleForm").serialize(),
		success: function (response) {


			for (var key in response) {
				$select = $("select[name='tallaColor[" + key +"]']");
				$select.children().remove();


				var options = response[key];
				$select.append("<option></option>");
				for( var keyOption in options){
					if(options[keyOption]["stk_art"] == "S" && options[keyOption]["stock"]<=0){
						//lo marcamos con negarivo para indicar que no hay stock
						id = keyOption * -1;
					}else{
						id = keyOption;
					}
					$select.append("<option value='" + id + "'> " +   options[keyOption]["valor_valvariante"] + " </option");
				}
			  }

		}
	});
}


function addArticle(){


	$.ajax({
		type: "POST",
		url:  "/" + lang  + "/addArticleCart",
		data: $("#articleForm").serialize(),
		success: function (response) {

			if(response.status == "success"){
				$("#msg_title_ArticleCart").html(messages.success["add_cart"]);
				$.magnificPopup.open({ items: { src: '#modalArticleCart' }, type: 'inline' }, 0);
			}else if (response.status == 'error') {
				$("#insert_msg").html(messages.error[response.errorMsg]);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
			}

		}
	});
}

function changeUnitsArticle(idart, units){

	token = $('input[name="_token"]').val();
	$.ajax({
		type: "POST",
		url:  "/" + lang  + "/changeUnitsArticleCart/"+ idart + "/" + units,
		data: {_token: token} ,
		success: function (response) {
			/* de momento no es necesairo mostrar un mensaje, por que le usuario ya ve los cambios en le precio */
		}
	});
}


function deleteArticle(idart,  reload){
	token = $('input[name="_token"]').val();
	$.ajax({
		type: "POST",
		url: "/deleteArticleCart",
		data: { _token: token, idArt: idart },
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
	calcCostToPay();
	/*
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

			}
		}
	});
	*/
}

function calcCostToPay(){
	total=0;
	for(article of articles){
		if (!isNaN($("input[name=units_"+ article+"]").val())){
			total+=parseFloat($("input[name=units_"+ article+"]").val())  * parseFloat($("input[name=pvp_"+ article+"]").val());
		}

	}
	total = Math.round(total *100)/100;
	formattedTotal = new Intl.NumberFormat("de", {minimumFractionDigits: 2}).format(parseFloat(total));

	$(".totalArticulos_JS").html(formattedTotal);
	$(".totalPagar_JS").html(formattedTotal);

//	total = parseFloat($("#totalLotes_JS").val());


}


function sendArticleCart(cod_sub){

	$( ".submitArticleCart_JS" ).html("<div class='loader mini' style='width: 20px;height: 20px;margin-top: 0px;margin-bottom: 0;'></div>");
	$( ".submitShoppingCart_JS" ).attr("disabled", "disabled");

   var pay_articles = $('#articleCartForm').serialize();

   $.ajax({
		type: "POST",
		url:  '/articleCart/pay',
		data: pay_articles,
		success: function(data) {
			if(data.status == 'success'){
				window.location.href = data.location;
			}else if(data.status == 'error'){
				$("#modalMensaje #insert_msg").html(messages.error[data.msgError]);
				$.magnificPopup.open({items: {src: '#modalMensaje'}, type: 'inline'}, 0);
				$( ".submitArticleCart_JS" ).html(" ");
				$( ".submitArticleCart_JS" ).prop("disabled", false);
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
