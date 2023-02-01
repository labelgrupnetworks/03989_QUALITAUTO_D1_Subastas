empresa = function () {
	$("#pri_emp").val("J");
	$(".tipo_usuario .empresa").addClass("selected");
	$(".tipo_usuario .particular").removeClass("selected");
	$(".registerParticular").hide();
	$(".registerEnterprise").show();
	$(".datos_contacto .cif").show();
	$(".datos_contacto .nif").hide();

	const dateInput = document.querySelector("[name=date]");
	$(dateInput.parentElement).hide();

	inputRequired('last_name', false);
	inputRequired('date', false);
}

particular = function () {
	$("#pri_emp").val("F");
	$(".tipo_usuario .empresa").removeClass("selected");
	$(".tipo_usuario .particular").addClass("selected");
	$(".registerParticular").show();
	$(".registerEnterprise").hide();
	$(".datos_contacto .cif").hide();
	$(".datos_contacto .nif").show();

	const dateInput = document.querySelector("[name=date]");
	$(dateInput.parentElement).show();

	inputRequired('date', true);
	inputRequired('last_name', true);
}
