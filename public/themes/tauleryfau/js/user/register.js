/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

particular = function () {
	$(".registerParticular").show();
	$(".registerEnterprise").hide();
	labelDniReload();
};

empresa = function () {
	$(".registerParticular").hide();
	$(".registerEnterprise").show();
	labelDniReload();
};

function inputRequired(name, required) {
	let valRequired = required ? 1 : 0;
	$(`input[name='${name}']`).prop("id", `texto__${valRequired}__${name}`);
}

clidNotRequired = function () {

	//cambiar requerimiento de inputs
	$("select[name='clid_pais']").prop("id", "select__0__clid_pais");
	$("select[name='clid_codigoVia']").prop("id", "select__0__clid_codigoVia");
	inputRequired('clid_cpostal', false);
	inputRequired('clid_poblacion', false);
	inputRequired('clid_provincia', false);
	inputRequired('clid_direccion', false);

	inputRequired('name_clidTemp', false);
	inputRequired('lastName_clidTemp', false);
	inputRequired('preftel_clid', false);
	inputRequired('tele_clid', false);

	//indicar que se utilizaran los inputs por defecto
	$('#clid').val(1);
}

clidRequired = function () {

	//cambiar requerimiento de inputs
	$("select[name='clid_pais']").prop("id", "select__1__clid_pais");
	$("select[name='clid_codigoVia']").prop("id", "select__1__clid_codigoVia");
	inputRequired('clid_cpostal', true);
	inputRequired('clid_poblacion', true);
	inputRequired('clid_provincia', true);
	inputRequired('clid_direccion', true);

	inputRequired('name_clidTemp', true);
	inputRequired('lastName_clidTemp', true);
	inputRequired('preftel_clid', true);
	inputRequired('tele_clid', true);

	$('#clid').val(0);
}

cleanDirection = function(){
	$("select[name='clid_pais']").val($("select[name='pais']").val());
	$("input[name='clid_cpostal']").val($("input[name='cpostal").val());
	$("input[name='clid_poblacion']").val($("input[name='poblacion").val());
	$("input[name='clid_provincia']").val($("input[name='provincia").val());
	$("select[name='clid_codigoVia']").val($("select[name='codigoVia").val());
	$("input[name='clid_direccion']").val($("input[name='direccion").val());
	$("input[name='usuario_clid']").val($("input[name='usuario").val());
	$("input[name='preftel_clid']").val($("input[name='preftel_cli").val());
	$("input[name='tele_clid']").val($("input[name='telefono").val());
}



/*
 * Acutaliza el label a mostrar junto con el input dni
 */
function labelDniReload() {

	reloadPrefix('preftel_cli', 'pais');

	let lang = $('#select__1__pais').val();
	let tipo_usuario = $("#pri_emp").val();
	$('.labelDni').hide();

	if (lang === 'ES' && tipo_usuario === 'F') {
		$('.nif').show();
		viaRequired(true);

	}
	else if (lang === 'ES' && tipo_usuario !== 'F') {
		$('.cif').show();
		viaRequired(true);
	}
	else if (lang !== 'ES' && tipo_usuario === 'F') {
		$('.passport').show();
		viaRequired(false);
	}
	else if (lang !== 'ES' && tipo_usuario !== 'F') {
		$('.vat').show();
		viaRequired(false);
	}

	$('input[type="text"][id*=nif]').each(function () {
		$(this).attr('placeholder', $(this).parent('label')
			.find('span:not([style*=display]):not([style*=none])')
			.text()
			.trim()
		);
	});
}

function reloadPrefix(input, select) {
	$(`input[name='${input}']`).val(prefix[$(`select[name='${select}']`).val()]);
}

function viaRequired(isRequired) {

	if (isRequired) {
		$('.via').show();
		$("select[name='codigoVia']").prop('id', 'select__1__codigoVia');
		return
	}

	$('.via').hide();
	$("select[name='codigoVia']").prop('id', 'select__0__codigoVia');
}




/*
 * Rellena placeholders de inputs y textareas
 * En el caso del nif buscamos el label con una caracteristica style concreta
 */
function reloadPlaceholders() {

	$('input[type="text"]').each(function () {
		$(this).attr('placeholder', $(this).parent('label').text().trim());
	});

	$('input[type="text"][id*=nif]').each(function () {
		$(this).attr('placeholder', $(this).parent('label')
			.find('span:not([style*=display]):not([style*=none])')
			.text()
			.trim()
		);
	});

	$('input[type="password"]').each(function () {
		$(this).attr('placeholder', $(this).parent('label').text());
	});

	//$("textarea[name='obscli']").attr('placeholder', $("textarea[name='obscli']").parent('label')[1].innerHTML);
}




$(document).ready(function () {

	$('[name="pri_emp"]').on('change', function () {
		if (this.value === 'F') {
			particular();
			return;
		}
		empresa();
	});

	$('input[name="preftel_cli"],input[name="preftel_clid"]').removeAttr("onfocus");

	$('select[name="pais"]').change(labelDniReload);
	$('select[name="clid_pais"]').change(function(){

		reloadPrefix('preftel_clid', 'clid_pais');

		if($(this).val() == 'ES'){
			$('.clid-via').show();
			$('input[name="clid_codigoVia"]').prop('id', 'select__1__clid_codigoVia');
			return;
		}
		$('.clid-via').hide();
		$('select[name="clid_codigoVia"]').prop('id', 'select__0__clid_codigoVia');

	});

	reloadPlaceholders();
	reloadPrefix('preftel_cli', 'pais');
	reloadPrefix('preftel_clid', 'clid_pais');

	$('input[name="shipping_address"]').on('change', function (event) {
		const $colapse = $('#collapse_d');
		const $this = $(this);
		const name = $this.attr('name');

		if ($this.is(':checked') && $this.val() === '1') {
			$(`input[name="${name}"]`).prop('checked', false);
			$this.prop('checked', true);
			clidNotRequired();
			$colapse.hide("slow");
		}
		else if($this.is(':checked') && $this.val() === '2') {
			$(`input[name="${name}"]`).prop('checked', false);
			$this.prop('checked', true);
			clidRequired();
			$colapse.show("slow");
		}
		else {
			$this.prop('checked', true);
		}
	});

});
