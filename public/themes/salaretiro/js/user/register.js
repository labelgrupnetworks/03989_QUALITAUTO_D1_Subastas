$(() => {

	$('[name=nif]').attr('id', 'nif__1__nif');

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

comprueba_nif = function (nif, obligatorio) {

    if (nif == "" && obligatorio == 1) {
        return false;
    }

	if (nif == "" && obligatorio == 0) {
        return true;
    }

	if (nif != "" && $("[name='pais']").val() != "ES"){
		return true;
	}

	nif = nif.toUpperCase().replace(/[\s\-]+/g,'');
	if(/^(\d|[XYZ])\d{7}[A-Z]$/.test(nif)) {
		var num = nif.match(/\d+/);
		num = (nif[0]!='Z'? nif[0]!='Y'? 0: 1: 2)+num;
		if(nif[8]=='TRWAGMYFPDXBNJZSQVHLCKE'[num%23]) {
			return true;
		}
	}
	else if(/^[ABCDEFGHJKLMNPQRSUVW]\d{7}[\dA-J]$/.test(nif)) {
		for(var sum=0,i=1;i<8;++i) {
			var num = nif[i]<<i%2;
			var uni = num%10;
			sum += (num-uni)/10+uni;
		}
		var c = (10-sum%10)%10;
		if(nif[8]==c || nif[8]=='JABCDEFGHI'[c]) {
			return true;
		}
	}
	return false;
}
