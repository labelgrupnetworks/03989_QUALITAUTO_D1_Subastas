$(document).ready(function () {
	$('#bool__0__newsletter').on('change', function () {
		if ($(this).is(':checked')) {
			$('[name="families[1]"').val('1');
		} else {
			$('[name="families[1]"').val('0');
		}
	});
});

