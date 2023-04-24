$(function () {

	if($('#acceptedTermsModal').length > 0){
		$('#acceptedTermsModal').modal();
	}

	$('.menu-principal-content li').on('click', function(event){

		if(event.target.nodeName == 'A'){
			return;
		}

		if($(this).hasClass('active')){
			$(this).removeClass('active');
			return;
		}

		$('.menu-principal-content li').removeClass('active');
		$(this).addClass('active');
	});

});
