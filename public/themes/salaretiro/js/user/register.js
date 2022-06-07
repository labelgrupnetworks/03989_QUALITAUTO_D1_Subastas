$(document).ready(function () {

	$('input[name=creditcard]').on('keyup', function(){

		if(this.value.match(/[a-zA-z]+/) || this.value.length < $(this).attr('minlength')){
			$(this).addClass('effect-26').addClass('has-error');
			return;
		}

		$(this).removeClass('effect-26').removeClass('has-error');
	});


	$('input[name=card-expired-month], input[name=card-expired-year]').on('keypress', function(e){
		if(this.value.length == 2){
			return false;
		}
	});

	$('input[name=card-expired-month]').on('blur', function(e){
		if(this.value > 12){
			this.value = 12;
		}
		if(this.value < 10 && this.value.length == 1){
			this.value = `0${this.value}`;
		}
		if(this.value == 0){
			this.value = '01';
		}
	});

	$('input[name=card-expired-year]').on('blur', function(e){

		let actualYear = new Date().getUTCFullYear().toString().substring(2,4);
		if(this.value.length == 2 && this.value < actualYear){
			this.value = actualYear;
		}
	});

	$('input[name=card-expired-month], input[name=creditcard], input[name=card-expired-year]').on('blur', function(e){

		let card = $('input[name=creditcard]').val();
		let month = $('input[name=card-expired-month]').val();
		let year = $('input[name=card-expired-year]').val();
		$('input[name=creditcard_fxcli]').val(`${card} ${month}/${year}`);
	});


});
