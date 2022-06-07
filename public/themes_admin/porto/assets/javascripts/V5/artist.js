


/*
 *
 * METODOS CRUD DE FAQ
 *
 */

$(function(){

	loadArtist();

	$("#artistCreate_JS").on("click", function(){
		CreateArtist();
	})

	$("#artistSave_JS").on("click", function(){
		saveArtist();
	})

	$('.jsChangeStatus').on("click", estadoartist);



})

function loadArtist() {



    $.ajax({
        type: "POST",
        url: "/admin/artist/loadarticles",
        data: {"id_artist": $('#id_artist').val()},
        success: function (response) {

               $("#articlesList").html(response);
			   /* no existen hasta que no se cargan en el html */
			   $(".artistDelete_JS").on("click", function(){
					deleteArtist($(this).data("id"));
				})

        },
        error: function (response) {
            new PNotify({
                title: 'Error',
                text: 'Se ha producido un error',
                type: 'danger'
            });
        }
    });

}

function CreateArtist() {



    $.ajax({
        type: "POST",
        url: "/admin/artist/createarticle",
        data: {"id_artist": $('#id_artist').val()},
        success: function (response) {
			loadArtist();
        },
        error: function (response) {
            new PNotify({
                title: 'Error',
                text: 'Se ha producido un error',
                type: 'danger'
            });
        }
    });

}

function deleteArtist(id_artist) {
	bootbox.confirm("¿Estas seguro de que quieres eliminar este registro?", function (result) {
		token = $("[name='_token']").val();
		if(id_artist!=''){
			if (result) {
				$.ajax({
					type: "DELETE",
					url: "/admin/artist/"+id_artist,
					data: {_token: token},
					success: function (response) {
						new PNotify({
							title: 'Success',
							text: 'Se ha eliminado correctamente',
							type: 'success'
						});
						setTimeout(location.reload(), 2000);
					},
					error: function (response) {
						new PNotify({
							title: 'Error',
							text: 'Se ha producido un error',
							type: 'danger'
						});
					}
				});
			}
		}else{
			new PNotify({
				title: 'Error',
				text: 'Se ha producido un error',
				type: 'danger'
			});
		}
	})
}

function saveArtist() {



    $.ajax({
        type: "POST",
        url: "/admin/artist/updatearticles",
        data: $('#artistArticleFrm').serialize(),
        success: function (response) {
            if (response.status == "success") {
                new PNotify({
                    title: 'Información',
                    text: 'Se guardado correctamente',
                    type: 'success'
                });
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Se ha producido un error',
                    type: 'danger'
                });
            }
        },
        error: function (response) {
            new PNotify({
                title: 'Error',
                text: 'Se ha producido un error',
                type: 'danger'
            });
        }
    });

}

function estadoartist(id) {

	var button = this;
	var activo = 1;
	if (button.getAttribute("estado") == 'on') {
		activo = 0;
	}

	var id = this.id;

	$.ajax({
		url: "/admin/artist/activar",
		type: "post",
		data: { "activo": activo, "id": id },
		success: function () {

			if (activo == 0) {
				button.setAttribute("estado", "off");
				button.setAttribute("title", "Activar");
				$(button).removeClass("btn-success");
				$(button).addClass("btn-danger");
			}
			else {
				button.setAttribute("estado", "on");
				button.setAttribute("title", "Desactivar");
				$(button).removeClass("btn-danger");
				$(button).addClass("btn-success");
			}



		},
		error: function () {
			error("Error en servidor, contacte con equipo técnico");
		}
	});
}
