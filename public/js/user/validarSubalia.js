/****************************************************************************************/
/*************************** FUNCIONES PARA REGISTRO DE USUARIO SUBALIA *****************/
/****************************************************************************************/

//#id
//.class

/*
 * Usario no logeado, Realizar login
 * @returns {metodo envair datos a controllador}
 */
function validarLogin(e) {

    $.ajax({
        type: "POST",
        url: '/login_post_ajax',
        data: $('#formUserNoLogin').serialize(),
        success: function (response)
        {
            if (response.status === 'success') {
                construirDatos($('#formUserNoLogin').serialize());
            } else {
                showMessage(messages.error.error_register, "Error");
            }

        }
    });
}



function construirDatos(datos) {

    if(datos == undefined){
        datos = $('#formUserLogin').serialize();
    }

    $.ajax({
        type: "POST",
        url: "subalia/valida",
        data: datos,
        success: function (response) {
            console.log(response);
            formHidden(response.data);
        },
        error: function (response) {
            if (response.status == 400) {
                showMessage(response.message);
            } else {
                showMessage(messages.error.hasErrors);
            }
        }
    });
}


/*
 * Rellenar formulario hidden con los datos recibidos, y enviar
 *
 * @param info {object} info datos recibidos.
 */
function formHidden(data) {


    $("#info").val(JSON.stringify(data.info));
    $("#cod_auchouse").val(data.cod_auchouse);
    $("#redirectH").val(data.redirect);

    document.getElementById("formToSubalia").submit();
}
