/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {

    $(".save_traducciones").click(function () {
        
        var str = $("#traducciones").serializeArray();

        $.ajax({
            url: "/traducciones/save",
            type: "post",
            data: str,
            success: function () {
                showMessage("Traducciones guardadas correctamente", "Traducciones");
            },
            error: function () {
                showMessage("Ha ocurrido algun error al guardar", "Error");
            }
        });

    });

    $(".new_traducciones").click(function () {

        var str = $("#new_traduction").serializeArray();
        $.ajax({
            url: "/traducciones/new",
            type: "post",
            data: str,
            success: function () {
                showMessage("Traducciones guardadas correctamente", "Traducciones");
            },
            error: function () {
                showMessage("Ha ocurrido algun error al guardar", "Error");
            }
        });
    });

});

