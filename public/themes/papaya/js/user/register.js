/*
 * Sobrescritura de metodos
 */

empresa = function () {

    $("#pri_emp").val("J");
    $("#inlineCheckbox2").prop('checked', true);
    $("#inlineCheckbox1").prop('checked', false);
    //muestra cuadro en form clasico
    //$(".tipo_usuario .empresa").addClass("selected");
	//$(".tipo_usuario .particular").removeClass("selected");

	$('input[name="date"]').prop('id', 'fecha__0__data');

    $(".registerParticular").hide();
	$(".registerEnterprise").show();

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
		$(this).val() == 'S' ? showRepreTable() : hideRepreTable();
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
}

function showRepreTable() {
	$("#js-repre-table").show();
	//add required in all inputs in table
	const table = document.getElementById('js-repre-table');
	table.querySelectorAll('input').forEach(input => input.required = true);
}

function hideRepreTable() {
	$("#js-repre-table").hide();
	const table = document.getElementById('js-repre-table');
	table.querySelectorAll('input').forEach(input => {
		input.required = false;
		input.value = '';
	});

}

function addRow() {
	const table = document.getElementById('js-repre-table');
	const rowNumber = table.rows.length - 1;
	const arrayNumber = rowNumber - 1;

	const clonRow = table.rows[1].cloneNode(true);
	clonRow.querySelector('[name*=alias]').value = '';
	clonRow.querySelector('[name*=alias]').attributes['name'].value = 'repre['+arrayNumber+'][alias]';
	clonRow.querySelector('[name*=name]').value = '';
	clonRow.querySelector('[name*=name]').attributes['name'].value = 'repre['+arrayNumber+'][name]';
	clonRow.querySelector('[name*=cif]').value = '';
	clonRow.querySelector('[name*=cif]').attributes['name'].value = 'repre['+arrayNumber+'][cif]';

	const trashButton = $('<button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>');
	clonRow.querySelector('[name*=cif]').parentNode.appendChild(trashButton[0]);

	//instert row in tbody
	table.querySelector('tbody').appendChild(clonRow);
}

function removeRow(button) {
	const row = button.closest('tr');
	row.remove();
}
