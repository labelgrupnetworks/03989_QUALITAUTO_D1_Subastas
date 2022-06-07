/*
 * Sobrescritura de metodos
 */

empresa = function () {

    $("#pri_emp").val("R");
    $("#inlineCheckbox2").prop('checked', true);
    $("#inlineCheckbox1").prop('checked', false);
    //muestra cuadro en form clasico
    //$(".tipo_usuario .empresa").addClass("selected");
	//$(".tipo_usuario .particular").removeClass("selected");

	$('input[name="date"]').prop('id', 'fecha__0__data');

    $(".registerParticular").hide();
	$(".registerEnterprise").show();

	$(".rsoc_inputgroup").addClass("mt-3");
	rsocEnabled();

    $(".datos_contacto .cif").show();
    $(".datos_contacto .nif").hide();

    $('.gener-group').hide();
    $('.fech_nac').hide();

}

particular = function () {

	$("#pri_emp").val("F");
	$('input[name="date"]').prop('id', 'fecha__1__data');

    $("#inlineCheckbox1").prop('checked', true);
    $("#inlineCheckbox2").prop('checked', false);

    $(".registerParticular").show();
	$(".registerEnterprise").hide();

	$(".rsoc_inputgroup").removeClass("mt-3");

	$('select[name="representar"]').val('N').trigger('change');

    $(".datos_contacto .cif").hide();
    $(".datos_contacto .nif").show();

    $('.gener-group').show();
    $('.fech_nac').show();
}



/*
 * Metodos nuevos
 */
$(document).ready(function () {

	rsocDisabled();

	$('select[name="representar"]').on("change", function(){
		$(this).val() == 'S' ? rsocEnabled() : rsocDisabled();
	});

});

function rsocDisabled(){
	$('input[name="rsoc_cli"]').attr("id", 'texto__0__rsoc_cli')
				.prop( "disabled", true)
				.val('');
}

function rsocEnabled(){
	$('input[name="rsoc_cli"]').attr("id", 'texto__1__rsoc_cli')
	.prop("disabled", false);

	$("#pri_emp").val("R");
}
