$('#edit_multple_deposits').on('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(edit_multple_deposits);

    const isSelectAllDepositsChecked = (document.getElementById('selectAllDeposits')).checked;

    if (isSelectAllDepositsChecked) {
        const urlSelected = document.getElementById('urlAllSelected').value;

		const searchParams = new URLSearchParams(window.location.search);
		for (const param of searchParams) {
			formData.append(param[0], param[1]);
		}

        $.ajax({
            url: urlSelected,
            type: "POST",
			data: formData,
            contentType: false,
            processData: false,

            success: function (result) {

			$('#editMultpleDepositsModal').modal('hide');
			saved('Archivos actualizados correctamente');

			location.reload();

            },
            error: function () {

                error();
            }
        });
    } else {
        const ids = selectedCheckItemsByName("desposit_ids");
        ids.forEach(id => formData.append('ids[]', id));

        $.ajax({
			url: edit_multple_deposits.action,
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,

			success: function (result) {
				$('#editMultpleDepositsModal').modal('hide');
				saved('Archivos actualizados correctamente');

				location.reload();
			},
			error: function () {

				error();
			}
		});
    }
	
});

$('input[name="desposit_ids"]').on('change', function () {
    const isSelectAllDepositsChecked = (document.getElementById('selectAllDeposits')).checked;

    if (isSelectAllDepositsChecked) {
        document.getElementById('selectAllDeposits').checked = false;
    }
});


