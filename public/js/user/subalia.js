/****************************************************************************************/
/*************************** FUNCIONES PARA REGISTRO DE USUARIO SUBALIA *****************/
/****************************************************************************************/

//#id
//.class

/*
 * Enviar petición de usuario. Si existe recuperar los datos  
 * @returns 
 */
function enviarDatos() {

    $.ajax({
        type: "POST",
        url: "subalia/register",
        data: $('#formUserLogin').serialize(),
        success: function (response) {
            formHidden(response.data, response.member);
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
 * Realizar login
 * @returns {undefined}
 */
function landingLogin(e) {
       
    $.ajax({
        type: "POST",
        url: '/login_post_ajax',
        data: $('#formSubalia').serialize(),
        success: function (response)
        {
            if (response.status === 'success') {
                location.reload();
            } else {
                showMessage(messages.error.error_register, "Error");
            }

        }
    });
}


/*
 * Rellenar formulario hidden con los datos recibidos, y enviar
 * 
 * @param info {object} info datos recibidos.
 */
function formHidden(info, member) {

    $("#member").val(member);
    $("#info").val(JSON.stringify(info));

    document.getElementById("formEnvio").submit();
}