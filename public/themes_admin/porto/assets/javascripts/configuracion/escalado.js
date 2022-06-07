$(document).ready(function () {
	$('#addEscalado').click(addEscalado);
});

function addEscalado(){

	var newRow = $("<div>", {'class': 'row items'});

	var column1 = $("<div>", {'class': 'col-12 col-md-2'});
	var columnImporte = $("<div>", {'class': 'col-12 col-md-4'});
	var columnPuja = $("<div>", {'class': 'col-12 col-md-4'});

	var inputImporte = newInput('importe');
	var inputPuja = newInput('puja');

	columnImporte.append(inputImporte);
	columnPuja.append(inputPuja);

	newRow.append(column1, columnImporte, columnPuja);

	$('#formEscalado').append('<br>');
	$('#formEscalado').append(newRow);
}

function newInput(name){

	return $("<input>", {
		'type' : 'text',
		'class': 'form-control effect-16',
		'name' : `${name}[]`,
		'id': `decimal__0__${name}[]`,
		'value': '',
		'onblur': 'comprueba_campo(this)',
		'data-placement': 'right',
		'autocomplete': 'off'
	});
}



