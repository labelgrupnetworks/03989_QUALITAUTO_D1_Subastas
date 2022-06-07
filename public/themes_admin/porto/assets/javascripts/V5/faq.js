/*
 *  Version: pruebas
 *  Author: Eloy
 *  Pruebas con metodos faq de subalia
 */




/*
 *
 * METODOS DE ORDENACION DE FAQ
 *
 */
$(".sortable").sortable({
    items: $(this).data('child'),
    update: function (event, ui) {
        var productOrder = $(this).sortable('toArray');
        var isCategory = $(this)[0].dataset.iscategory;
        saveOrder(productOrder, isCategory);
    }
});

function saveOrder(arrayId, isCategory) {

     var lang = obtainLang();

    $.ajax({
        type: "POST",
        url: "/admin/faqs/" + lang +"/order",
        data: {order: arrayId,category: isCategory},
        success: function (response) {
            if (response.status == "success") {
                new PNotify({
                    title: 'Success',
                    text: response.message,
                    type: 'success'
                });
            } else {
                new PNotify({
                    title: 'Error',
                    text: response.message,
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


function saveCategoryOrder(arrayId) {

    var lang = obtainLang();

    $.ajax({
        type: "POST",
        url: "/admin/faqs/" + lang +"/categories/order",
        data: {order: arrayId},
        success: function (response) {
            if (response.status == "success") {
                new PNotify({
                    title: 'Success',
                    text: response.message,
                    type: 'success'
                });
            } else {
                new PNotify({
                    title: 'Error',
                    text: response.message,
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




/**
 * SELECCIÓN DE IDIOMA
 */
$("#lang_es").click(function() {
    window.location.href = "/admin/faqs/es";

});

$("#lang_en").click(function() {
    window.location.href = "/admin/faqs/en";

});

/*
 *
 * METODOS CRUD DE FAQ
 *
 */

function saveFaq() {

    var lang = obtainLang();

    $.ajax({
        type: "POST",
        url: "/admin/faqs/" + lang + "/editRun",
        data: $('#formWEB_FAQ').serialize(),
        success: function (response) {
            if (response.status == "success") {
                document.location = "/admin/faqs/"+lang.toLowerCase();
            } else {
                new PNotify({
                    title: 'Error',
                    text: response.message,
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


function deleteFaq(cod) {

    bootbox.confirm("¿Estas seguro de que quieres eliminar este registro?", function (result) {
        if (result) {

            $.ajax({
                type: "POST",
                url: "/admin/faqs/delete",
                data: {cod: cod},
                success: function (response) {
                    if (response.status == "success") {
                        document.location = document.location;
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: response.message,
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
    });
}


function newFaqCat() {

    if ($("#new").val() == "") {
        new PNotify({
            title: 'Error',
            text: 'Debes añadir el nombre',
            type: 'danger'
        });
    } else {

        var lang = obtainLang();

        $.ajax({
            type: "POST",
            url: "/admin/faqs/" + lang + "/categories/newRun",
            data: $('#newCat').serialize(),
            success: function (response) {
                if (response.status == "success") {
                    document.location = "/admin/faqs/"+ lang.toLowerCase();
                } else {
                    new PNotify({
                        title: 'Error',
                        text: response.message,
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

}

function deleteFaqCat(cod) {

    bootbox.confirm("¿Estas seguro de que quieres eliminar este registro?", function (result) {
        if (result) {


            $.ajax({
                type: "POST",
                url: "/admin/faqs/categories/delete",
                data: {cod: cod},
                success: function (response) {
                    if (response.status == "success") {
                        document.location = document.location;
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: response.message,
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
    });
}

function saveFaqCat() {

    lang = obtainLang();

    $.ajax({
        type: "POST",
        url: "/admin/faqs/" + lang + "/categories/editRun",
        data: $('#formWEB_FAQCAT').serialize(),
        success: function (response) {
            if (response.status == "success") {
                document.location = "/admin/faqs/"+lang.toLowerCase();

            } else {
                new PNotify({
                    title: 'Error',
                    text: response.message,
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


function obtainLang(){

    var path = window.location.pathname;
    var lang = path.split("/", 4);
    return lang[3];
}

