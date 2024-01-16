$('#edit_multple_deposits').on('submit', function (event) {
	event.preventDefault();

	const formData = new FormData(edit_multple_deposits);
	const isSelectAllDepositsChecked = (document.getElementById('selectAllDeposits')).checked;

	const url = isSelectAllDepositsChecked
		? urlAllSelected.value
		: edit_multple_deposits.action;

	isSelectAllDepositsChecked
		? appendFiltersToFormData(formData)
		: appendIdsToFormData(formData, "desposit_ids");

	updataDepositStatus(url, formData);
});

function updataDepositStatus(url, formData) {

	$.ajax({
		url,
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,

		success: function (result) {
			$('#editMultpleDepositsModal').modal('hide');
			saved(result.message);
			location.reload();
		},
		error: function () {
			error('Error inesperado, refesque la página e intente nuevamente.');
		}
	});
}

$('input[name="desposit_ids"]').on('change', function () {
	const isSelectAllDepositsChecked = (document.getElementById('selectAllDeposits')).checked;

	if (isSelectAllDepositsChecked) {
		document.getElementById('selectAllDeposits').checked = false;
	}
});


