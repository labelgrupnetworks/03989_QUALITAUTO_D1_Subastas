/****************************************************************************************/
/************************ FUNCIONES DE VALIDACION DE DATOS ******************************/
/****************************************************************************************/

//
//  Función de comprobación del tipo de dato nif
//
//  @texto - Texto a comprobar
//



function comprueba_nif(nif, obligatorio) {


    if (nif=="" && obligatorio == 0) {
        return true;
    }else if(nif!="" && $("[name='pais']").val() != "ES"){
		return true;
	}else {

        nif = nif.toUpperCase().replace(/[\s\-]+/g,'');
        if(/^(\d|[XYZ])\d{7}[A-Z]$/.test(nif)) {
            var num = nif.match(/\d+/);
            num = (nif[0]!='Z'? nif[0]!='Y'? 0: 1: 2)+num;
            if(nif[8]=='TRWAGMYFPDXBNJZSQVHLCKE'[num%23]) {
                return true;
            }
        }
        else if(/^[ABCDEFGHJKLMNPQRSUVW]\d{7}[\dA-J]$/.test(nif)) {
            for(var sum=0,i=1;i<8;++i) {
                var num = nif[i]<<i%2;
                var uni = num%10;
                sum += (num-uni)/10+uni;
            }
            var c = (10-sum%10)%10;
            if(nif[8]==c || nif[8]=='JABCDEFGHI'[c]) {
                return true;
            }
        }
        return false;
    }
}



//
//  Función de comprobación del tipo de dato email
//
//  @texto - Texto a comprobar
//


function comprueba_email(texto,obligatorio) {

    if (texto=="" && obligatorio == 0) {
        return true;
    }
    else {

        var mailres = true;
        var cadena = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890@._-";

        var arroba = texto.indexOf("@",0);
        if ((texto.lastIndexOf("@")) != arroba) arroba = -1;

        var punto = texto.lastIndexOf(".");

         for (var contador = 0 ; contador < texto.length ; contador++){
            if (cadena.indexOf(texto.substr(contador, 1),0) == -1){
                mailres = false;
                break;
         }
        }

        if ((arroba > 1) && (arroba + 1 < punto) && (punto + 1 < (texto.length)) && (mailres == true) && (texto.indexOf("..",0) == -1))
         mailres = true;
        else
         mailres = false;

        return mailres;
    }
}

//
//  Función de comprobación del tipo de dato numero
//
//  @texto - Texto a comprobar
//


function comprueba_numero(valor,obligatorio){
    if (valor=="" && obligatorio == 0) {
        return true;
    }
    else {
        valor = parseInt(valor);
        if (isNaN(valor)) {
             return false
        }else{
             return true
        }
     }
}


//
//  Función de comprobación del tipo de dato decimal
//
//  @texto - Texto a comprobar
//

function comprueba_decimal(valor,obligatorio){

    if (valor=="" && obligatorio == 0) {
        return true;
    }
    else {
        valor = parseFloat(valor);
        if (isNaN(valor)) {
             return false
        }else{
             return true
        }
    }
}


//
//  Función de comprobación del tipo de dato password
//
//  @texto - Texto a comprobar
//

function comprueba_password(valor,obligatorio){

    if (valor=="" && obligatorio == 0) {
        return true;
    }
    else {
        if (valor.length<5) {
             return false
        }else{
             return true
        }
    }
}


//
//  Función de comprobación general de un campo.
//
//  @texto - Texto a comprobar
//


function comprueba_campo(campo) {

    ret=true;
    tipo="";

    // Obtenemos el tipo de dato

    for (t=0;t<campo.id.length;t++) {
        if (campo.id.charAt(t)!="_" && campo.id.charAt(t+1)!="_") {
            tipo=tipo+campo.id.charAt(t);
        }
        else {
            break;
        }

    }
    tipo=tipo+campo.id.charAt(t);

    // Miramos el caso en el  que 2 campos han de ser iguales (Ej: confirmar email)

    if (typeof $(campo).attr("check") != "undefined") {
        campo2 = $("#"+campo.id).attr("check");

        a = $(campo).val();
        b = $("#"+campo2).val();

        if (a!=b) {
            ret=false;error="Los campos han de ser iguales";
        }
    }

    // Controlamos si el campo es obligatorio

    obligatorio=campo.id;
    obligatorio=obligatorio.replace(tipo,"").replace(campo.name,"").replace("__","").replace("__","");
    obligatorio=obligatorio.charAt(0);

    if (obligatorio==1 && campo.value.trim().length==0) {ret=false;error = ""}
    if (obligatorio==1 && tipo=='select' && campo.value=="-") {ret=false;error=""}
    if (obligatorio==1 && tipo=='bool' && !$(campo).is(':checked')) {ret=false;error=""}

    // En funcion del tipo de dato lanzamos la validación correspondiente

    if (tipo=='email' && !comprueba_email(campo.value, obligatorio) && ret) {
        ret=false;error="";
    }
    if (tipo=='numero' && !comprueba_numero(campo.value, obligatorio) && ret) {
        ret=false;error="";
    }
    if (tipo=='decimal' && (!comprueba_decimal(campo.value, obligatorio)) && ret) {
        ret=false;error="";
    }
    if (tipo=='password' && (!comprueba_password(campo.value, obligatorio)) && ret) {
        ret=false;error="";
    }

    if (tipo=='nif' && (!comprueba_nif(campo.value, obligatorio)) && ret) {
        ret=false;error="";
    }

	if(typeof custom_checks == 'function' && !custom_checks(campo)){
		ret=false;error="";
	}
    // Si hay error mostramos error y marcamos campo. Sin

    if (!ret) {
        muestra_error_input(campo,error);
    }
    else {
        oculta_error_input(campo);
    }

    return ret;
}



function muestra_error_input(campo,error) {

    id = $(campo).attr("id");

	$("label[for="+id+"]").addClass('has-error');
    $(campo).addClass('has-error');
	$(campo).addClass('is-invalid');

    if (campo.value != "" && campo.value != null) {
        $(campo).addClass('has-content');
    }
    else {
        $(campo).removeClass('has-content');
        $(campo).parent().removeClass('has-content');
    }

    $(campo).attr("data-content",error);

    //Eloy(24/10/2019)
    //Ocultado porque cuando estan fuera de pantalla
    //o en pantallas mobiles, no se muestra correctamente
    //$(campo).popover("show");

}

function oculta_error_input(campo) {

    $(campo).addClass('has-content');

    $(campo).removeClass('has-error');
	$(campo).removeClass('is-invalid');

    if (campo.value != "" && campo.value != null) {
        $(campo).addClass('has-content');
    }
    else {
        $(campo).removeClass('has-content');
        $(campo).parent().removeClass('has-content');
    }

}


//
//  Función de comprobación de un formulario y el submit asociado
//
//  @form - Objeto Formulario a tratar
//

function submit_form(form, ret) {
	err=0;
    for (tt=0;tt<form.elements.length;tt++) {
		if (form.elements[tt].type!="button" && form.elements[tt].type!="hidden" && form.elements[tt].getAttribute("disabled") == null) {
            resultado = comprueba_campo(form.elements[tt]);
			if (!resultado) {err++;}
        }
    }
    if (!err) {
        if (ret == 1) {
            return true;
        }
        else {
            $(form).submit();
        }
    }
    else {
        if (ret == 1) {

            return false;
        }
        else {
            if (typeof messages == "undefined") {

                showMessage("Ha ocurrido un error");
            }
            else {
                showMessage(messages.error.hasErrors);
            }
        }
    }
}





//
//  Función de comprobación de un formulario sin submit
//
//  @form - Objeto Formulario a tratar
//

function check_form(form) {

    err=0;
    for (tt=0;tt<form.elements.length;tt++) {
        if (form.elements[tt].type!="button" && form.elements[tt].type!="hidden") {
            resultado = comprueba_campo(form.elements[tt]);
            if (resultado==false) {err++;}
        }
    }

    if (err) {
        return false;
    }
    else {
        return true;
    }
}



$(document).ready(function () {

    $(".form-control").on("focus",function() {
        oculta_error_input($(this));
    });

    var imagesarr = [];
    function myFunction( el ) {
        $(el).remove()
    }


  $('#dropzone').on('dragover', function() {
    $(this).addClass('hover');
  });

  $('#dropzone').on('dragleave', function() {
    $(this).removeClass('hover');
  });

  $('#dropzone #images').on('change', function(e) {

    max_size = 6000;
    var size = 0
    $("#form-valoracion-adv").find('input[type="file"]').each(function (index, element) {
            $(element.files).each(function(index, el){
                size = size + ((el.size / 1024))
            })

        });
    if(Math.floor(size) < max_size){
        var idrandom = 'image-'+Math.random();
        var x = $('#images').clone();
        $(x)
            .attr('id', idrandom)
            .hide()
        $('#dropzone').append(x)
        $('.error-dropzone').hide()
    }else{
        $(this).removeClass('hover');
        $(this).val(null);
        $('.error-dropzone').show()
        return false
    }

    var img = e.target.files
    for(i = 0; i < this.files.length ; i++){

        var file = this.files[i];
    $('#dropzone').removeClass('hover');


    if ((/image\/(gif|png|jpeg|jpg)$/i).test(file.type)) {
        var reader = new FileReader(file);
        reader.readAsDataURL(file);

        reader.onload = function(e) {
        var data = e.target.result,
            $img = $('<img class="img-responsive" />').attr('src', data).fadeIn();
            $div  = $('<div onclick="myFunction(this)" id='+idrandom+' class="mini-upload-image"><div class="delete-img">Delete</div></div>');
            $(x).attr('id', idrandom).hide()
            $('#dropzone').append(x)
            $div.append(x)
            $div.append($img)
            $('#images').val('')
        $('#dropzone .mini-file-content').append($div);

      };
    } else {
      alert('Archivo no permitido')
    }



    }

  });


    $("#autoformulario").submit(function (event) {

        event.preventDefault();
        formData = new FormData(this);

        var max_size = 1000000;
        var size = 0;
		var error = 0;
        response = $("#g-recaptcha-response").val();
		response=true;
        if (!response) {
            error = error + 1;
            $(".g-recaptcha iframe").addClass("has-error");
            $("#insert_msg").html(messages.error.recaptcha_incorrect);
            $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
        }
        else {
            $(".g-recaptcha iframe").removeClass("has-error");


            $("#autoformulario").find('input[type="file"]').each(function (index, element) {

                $(element.files).each(function(index, el){
                    size = size + ((el.size / 1024))
                })

            });

            if (Math.floor(size) < max_size) {
                $.ajax({
                    type: "POST",
                    url: "/es/autoformulario-send",
                    data: formData,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        if (result.status == 'success') {
                            window.location.href = result.url;
                        } else {
                            $("#modalMensaje #insert_msg").html(result.message);
                            $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
                        }
                    },
                    error: function (result) {
                        $("#insert_msg").html(messages.error.generic);
                        $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
                    }
                });
            }
            else {

                if (Math.floor(size)>1) {

                    $("#insert_msg").html(messages.error.max_size_img);
                    $.magnificPopup.open({ items: { src: '#modalMensaje' }, type: 'inline' }, 0);
                }
            }
        }
    });





});





