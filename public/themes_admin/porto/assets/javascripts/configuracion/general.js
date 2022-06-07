	
/**
*
* Cambiar la posibilidad de registrar usuarios o no
* 
**/

    $('#save_registration_disabled').on('click', function () {

        $.ajax({
            type: "POST",
            url: "/admin/configuracion/save",
            data: {status: $('#select_registration')[0].value},
            success: function (response) {
                new PNotify({
                    title: 'Succes',
                    text: response.message,
                    type: 'success'
                });
            },
            error: function (response) {
                new PNotify({
                    title: 'Error',
                    text: 'Se ha producido un error',
                    type: 'danger'
                });
            }
        });


    });