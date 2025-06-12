$(document).ready(function () {
	changeInputsToRequired();
});


function changeInputsToRequired() {
	const requiredInputs = ['usuario', 'last_name', 'nif'];
	requiredInputs.forEach(input => inputRequired(input, true));
}

