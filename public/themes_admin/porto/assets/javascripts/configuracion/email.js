

function actualiza() {

	token = $("[name='_token']").val();

	html = $("#textogrande__1__plantilla").val();


	$("#resultado").html(html);

	$.post( "/admin/email/guardarPlantilla", {html:html, _token:token}, function( data ) {
        
        if (data != "OK") {

        	showMessage("Ha ocurrido un error al guardar la plantilla","Error");

        }

    });

}


function seleccionaIdioma(lang) {

	$('.tab').hide();
	$('#tab'+lang).show();
	$("[name='subject_email_"+lang+"']").trigger('change');

}

$(document).ready(function () {

	$("#textogrande__1__plantilla").on("change",function() {
		actualiza();
	});

	$("[name^='subject_email'] , [name^='body_email']").on("change",function() {
		guardarEmail($(this));
	});

	function guardarEmail(item) {
		
		a = item.attr("id").split("_");
		idioma = a[a.length-1];

		asunto = $("#texto__0__subject_email_"+idioma).val();
		cuerpo = $("#textogrande__0__body_email_"+idioma).val();
		key = $("#1__cod_email").val();
		token = $("[name='_token']").val();

		$.post( "/admin/email/guardarEmail", {asunto:asunto,cuerpo:cuerpo,idioma:idioma,key:key, _token:token}, function( data ) {
        
	        $("#preview").html(data);
	        
	    });
	}

	
	$("#texto__1__des_email, #select__1__type_email, #bool__0__enabled_email").on("change",function() {

		key = $("#1__cod_email").val();
		tipo = $("#select__1__type_email").val();
		descripcion = $("#texto__1__des_email").val();
		activo = $("#bool__0__enabled_email").prop("checked");
		asunto = $("#texto__0__subject_email_es").val()
		cuerpo = $("#textogrande__0__body_email_es").val()

		token = $("[name='_token']").val();

		$.post( "/admin/email/guardar", {key:key, activo:activo, descripcion:descripcion, tipo:tipo, _token:token, cuerpo:cuerpo, asunto:asunto}, function( data ) {
        
	        if (data != "OK") {

	        	showMessage("Ha ocurrido un error al guardar la plantilla","Error");

	        }

	    });

	});
	

	if (typeof($("#textogrande__1__plantilla")).attr("id")!="undefined") {
		actualiza();
	}

		

	if (typeof($("#texto__1__des_email")).attr("id")!="undefined") {
		
		$("[name='subject_email_es']").trigger("change");
		
	}



});