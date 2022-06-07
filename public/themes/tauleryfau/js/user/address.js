$( document ).ready(function() {

	const prefix = JSON.parse($('.js-prefix').val());

	reloadPrefix();
	$('select[name="clid_pais"]').on('change', reloadPrefix);

	/*$('.selectpicker').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
		reloadPrefix();
	});*/


	$('.js-title-collapse').on('show.bs.collapse', function (e) {
		$(`[data-target^='#${e.target.id}'] i.fa.fa-caret-right`).removeClass('fa-caret-right').addClass('fa-caret-down');
	});

	$('.js-title-collapse').on('hide.bs.collapse', function (e) {
		$(`[data-target^='#${e.target.id}'] i.fa.fa-caret-down`).removeClass('fa-caret-down').addClass('fa-caret-right');
	});

	$('.js-addresses').on('show.bs.collapse', function (e) {

		$(e.target).parent().find('button').prop('disabled', false);
		$(e.target).parent().find('span.edit_address-span').hide();
		$(e.target).parent().find('button').show();
	});

	$('.js-addresses').on('hide.bs.collapse', function (e) {
		$(e.target).parent().find('button').prop('disabled', true);
		$(e.target).parent().find('span.edit_address-span').show();
		$(e.target).parent().find('button').hide();
	});

	$(".save_address_shipping").submit(function(event) {
		event.preventDefault();
		$.when(submit_shipping_addres(event,this)).done(function (data) {
			$('.button_modal_confirm').on('click', function(){
				location.reload();
			});
		 });
	});

	$(".delete-address").click(function(){
		$.when(delete_shipping_addres(this)).done(function (data) {
			$('#modalMensaje .button_modal_confirm').on('click', function(){
				location.reload();
			});
		 });
	});

	$(".fav-address").click(function() {
		$.when(fav_addres(this)).done(function (data) {
			$('.button_modal_confirm').on('click', function(){
				location.reload();
			});
		 });
	});

	$("input[name=clid_cpostal]").blur(function(){

		var thisPanel = this.parentNode.closest('.panel-body');
		var country = thisPanel.querySelector("select[name='clid_pais'").value;
		var zip = $(this).val();
		if(country!=''){
			$.ajax({
				type: "POST",
				data: {zip : zip,country:country},
				url: '/api-ajax/cod-zip',
			   success: function( msg ) {
				   if(msg.status == 'success'){
						thisPanel.querySelector("input[name=clid_provincia]").value = msg.des_prv;
						thisPanel.querySelector("input[name=clid_poblacion]").value = msg.pob;
				   }
			   }
			});
		}
	});

	$("input[name=fav_address]").on('click', function(){
		  $(this).parent().find('a').trigger('click');
	});

	function reloadPrefix() {
		$(`input[name='preftel_clid']`).each(function(index, element){

			//if(this.value != '') return;

			let panelBody = this.parentNode.closest('.panel-body');
			this.value = prefix[panelBody.querySelector("select[name='clid_pais'").value];
		});
	}
});
