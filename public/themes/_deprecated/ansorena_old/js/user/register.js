$(document).on('ready', function () {

	//name de inputs que no puedan ni copiar ni pegar información
	let noCopyInputs = ['email', 'confirm_email', 'password', 'confirm_password'];

	for (const inputName of noCopyInputs) {

		$(`[name="${inputName}"]`).on('copy', function(e){
			e.preventDefault();
		});
		$(`[name="${inputName}"]`).on('paste', function(e){
			e.preventDefault();
		})

	}


});
