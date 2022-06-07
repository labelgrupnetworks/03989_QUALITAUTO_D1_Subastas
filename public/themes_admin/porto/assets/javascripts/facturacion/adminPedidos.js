$(function () {

	$("input[name=descuento]").on('change', function(event){
		calcImporteFinal();
	});

	$("input[name=impTotalForzado]").on('change', function(event){
		calcDescuento();
	});

	$('#addObra').on('click', function(event) {
		idObra = $("select[name=obra]").val();
		nombreObra = $("select[name=obra] option:selected").text();

		if( typeof idObra != 'undefined' &&  idObra !=""){
			addObra(idObra,nombreObra);
		}

	});


	$("#CrearPedidoBtn").on("click", function(event){
		if($("select[name=client]").val() == ""){
			$("#errorCreatePedido").html("No se puede crear la Venta, no se ha seleccionado ningun comprador");
			$("#errorCreatePedido").removeClass("hidden");

		}else if($("input[name^=subastas]").length==0){
			$("#errorCreatePedido").html("No se puede crear la Venta, no se ha seleccionado ninguna obra");
			$("#errorCreatePedido").removeClass("hidden");
		}else{
			$("#errorCreatePedido").addClass("hidden");
			crearPedido();
		}

	})

});

function addObra(idObra,nombreObra){

		// si la obra no está en el listado
		if($("#" +idObra ).length == 0){
			$("#errorAddObra").addClass="hidden";
			subastaRef = idObra.split("-");

			nuevaObra = $("#obraClon").clone();
			nuevaObra.removeClass("hidden");
			nuevaObra.attr("id",idObra);
			nuevaObra.find("span").html(nombreObra);
			nuevaObra.find("input").attr("name", "subastas[" + subastaRef[0] + "][]");
			nuevaObra.find("input").attr("value",  subastaRef[1] );
			nuevaObra.find("label").on("click", function(event){
				$( this ).parent().remove();
				/* calculamos despues de que se haya quitado la obra */
				calcImportePedido();
			})

			nuevaObra.appendTo($("#obrasList"));
			/* calculamos despues de que se haya añadido la obra */
			calcImportePedido();
		}else{
			$("#errorAddObra").html("La obra ya existe en la venta");
			$("#errorAddObra").removeClass("hidden");

		}


}

function crearPedido(){
	$.ajax({
		async: true,
		type: "POST",
		data: $('#pedidosForm').serialize(),
		url: "/admin/pedidos",

		success: function (data) {
			window.location.href ="/admin/pedidos";
		},
		error: function (errors) {
			$("#errorCreatePedido").html("Se ha producido un error al crear el pedido");
			$("#errorCreatePedido").removeClass("hidden");
			console.log(errors);

		},
	});

}

function calcImportePedido(){
	$.ajax({
		async: true,
		type: "POST",
		data: $('#pedidosForm').serialize(),
		url: "/admin/pedidos/importeBasePedido",

		success: function (data) {
			iva = parseFloat(data.iva);
			$("#iva").val(iva);
			importeBase = parseFloat(data.importe);
			$("#importeBasePedido").val(importeBase);
			$("#importeBasePedidoLabel").html(importeBase);
			calcImporteFinal();

		},
		error: function (errors) {
			$("#errorCreatePedido").html("Se ha producido un error al cargar el importe de las obras");
			$("#errorCreatePedido").removeClass("hidden");
			console.log(errors);

		},
	});

}

function calcImporteFinal(){
			descuento = parseFloat($("input[name=descuento]").val());
			importeBase = parseFloat($("#importeBasePedido").val());
			iva = parseFloat($("#iva").val());
			importeTotal = importeBase - (importeBase * descuento /100 );
			importeTotal = importeTotal * (1+ (iva/100));
			$("input[name=impTotalForzado]").val(importeTotal);
}

function calcDescuento(){
	importeTotal = parseFloat($("input[name=impTotalForzado]").val());
	importeBase = parseFloat($("#importeBasePedido").val());
	iva = parseFloat($("#iva").val());
	importeTotalSinIva = importeTotal / (1+ (iva/100));
	descuento = importeBase / (importeBase - importeTotalSinIva);
	$("input[name=descuento]").val(descuento)
}


