$('#edit_multple_deposits').on('submit', function (event) {
	event.preventDefault();

	const formData = new FormData(edit_multple_deposits);
	const isSelectAllDepositsChecked = (document.getElementById('selectAllDeposits')).checked;

	const url = isSelectAllDepositsChecked
		? urlAllSelected.value
		: edit_multple_deposits.action;

	isSelectAllDepositsChecked
		? appendFiltersToFormData(formData)
		: appendIdsToFormData(formData);

	updataDepositStatus(url, formData);
});

function appendFiltersToFormData(formData) {
	const searchParams = new URLSearchParams(window.location.search);
	const params = [...searchParams.entries()];
	const cleanParams = params.filter(param => param[1] !== '');

	// Add params to formData
	cleanParams.forEach((entryParams) => {
		const [key, value] = entryParams;
		formData.append(key, value);
	});
}

function appendIdsToFormData(formData) {
	const ids = selectedCheckItemsByName("desposit_ids");
	ids.forEach(id => formData.append('ids[]', id));
}

function updataDepositStatus(url, formData) {

	$.ajax({
		url,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (result) {
			$('#editMultpleDepositsModal').modal('hide');
			saved('Depósitos actualizados correctamente');
			location.reload();
		},
		error: function () {
			error();
		}
	});
}

$('input[name="desposit_ids"]').on('change', function () {
	const isSelectAllDepositsChecked = (document.getElementById('selectAllDeposits')).checked;

	if (isSelectAllDepositsChecked) {
		document.getElementById('selectAllDeposits').checked = false;
	}
});


