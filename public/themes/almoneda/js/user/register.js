/*
 * Sobrescritura de metodos
 */

empresa = function () {

	$(".tipo_usuario .empresa").addClass("selected");
    $(".tipo_usuario .particular").removeClass("selected");

    $("#pri_emp").val("R");

    $(".registerParticular").hide();
	$(".registerEnterprise").show();

	$(".rsoc_inputgroup").removeClass('datos_right').addClass('datos_left');
	rsocEnabled();
}

particular = function () {

	$(".tipo_usuario .empresa").removeClass("selected");
    $(".tipo_usuario .particular").addClass("selected");

	$("#pri_emp").val("F");

    $(".registerParticular").show();
	$(".registerEnterprise").hide();

	$(".rsoc_inputgroup").addClass('datos_right').removeClass('datos_left');

	$('select[name="representar"]').val('N').trigger('change');
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

	$("#pri_emp").val("F");

	$('input[name="rsoc_cli"]').attr("id", 'texto__0__rsoc_cli')
				.prop( "disabled", true)
				.val('');
}

function rsocEnabled(){
	$('input[name="rsoc_cli"]').attr("id", 'texto__1__rsoc_cli')
	.prop("disabled", false);

	$("#pri_emp").val("R");
}
