$("#order_selected").change(function(){
	$("#hidden_order").val($("#order_selected").val());
	$("#form_lotlist").submit();
})
$("#total_selected").change(function(){
	$("#hidden_total").val($("#total_selected").val());
	$("#form_lotlist").submit();
})
/* reference es un input hidden y el campo de busqueda de referencia está fuera del form*/
$("#button_reference_selected_js").click(function(){
	$("#reference").val($("#reference_selected_js").val());
	$("#form_lotlist").submit();
})

$("#reference_selected_js").keydown(function(e){
	if (e.keyCode == 13) {
		$("#reference").val($("#reference_selected_js").val());
		$("#form_lotlist").submit();
	}
})
$('#preference_selected').change(function () {
	var preferenceSelectedSplit = $(this).val().split('-')

	if(preferenceSelectedSplit[0] == ''){
		$('#all_categories').prop('checked', true);
	} else {
		$("#category_"+preferenceSelectedSplit[0]).prop('checked', true);
	}

	if($("#section_" + preferenceSelectedSplit[1]).length){
		$("#section_" + preferenceSelectedSplit[1]).prop('checked', true);
	}else{
		$("#form_lotlist").append('<input type="hidden" name="section" id="section_' + preferenceSelectedSplit[1] + '" value="' + preferenceSelectedSplit[1] + '" />');
	}

	var keywords = "";
	if(preferenceSelectedSplit[2] != ""){
		keywords = preferenceSelectedSplit[2];
	}
	if(preferenceSelectedSplit[3] != ""){
		keywords = keywords + " " + preferenceSelectedSplit[3];
	}
	if(preferenceSelectedSplit[4] != ""){
		keywords = keywords + " " + preferenceSelectedSplit[4];
	}

	$("#description").val(keywords);
	$("#form_lotlist").submit();
});



$(".search-btn_js").click(function(){

	$("#form_lotlist").submit();
})


$(".search-input_js").keydown(function(e){
	if (e.keyCode == 13) {
		$("#form_lotlist").submit();
	}
})


$("#top_lots_per_page").change(function(){
	$("#hidden_lotsPerPage").val($("#top_lots_per_page").val());
	$("#form_lotlist").submit();
})

$("#seeHistoricLots_JS").on("click", function(){
	$("#hidden_historic").val(1);
	$("#form_lotlist").submit();
})

$("#seeActiveLots_JS").on("click", function(){
	$("#hidden_historic").val("");
	$("#form_lotlist").submit();
})

$(".filter_lot_list_js").click(function(){
	if ($(this).attr("name") == "category"){
		 $("#all_sections").attr('checked', 'checked');
		 $("#all_subsections").attr('checked', 'checked');
	}else if ($(this).attr("name") == "section"){
		 $("#all_subsections").attr('checked', 'checked');
	}
	 $("#form_lotlist").submit();
})
$(".select_lot_list_js").change(function(){

	 $("#form_lotlist").submit();
})
$(".slider-range").on('slidestop', () => $("#form_lotlist").submit());

/* falta borrar el typo de subastas */

$(".del_filter_category_js").click(function(){

	$("#all_categories").attr('checked', 'checked');
	$("#all_sections").attr('checked', 'checked');
	$("#all_subsections").attr('checked', 'checked');
	$("#form_lotlist").submit();
})
$(".del_filter_section_js").click(function(){
	$("#all_sections").attr('checked', 'checked');
	$("#all_subsections").attr('checked', 'checked');
	$("#form_lotlist").submit();
})

$(".del_filter_subsection_js").click(function(){
	$("#all_subsections").attr('checked', 'checked');
	$("#form_lotlist").submit();
})

 $(".del_filter_auchouse_js").click(function(){
	$("#all_auc_houses").attr('checked', 'checked');
	$("#form_lotlist").submit();
})

$(".del_filter_typeSub_js").click(function(){
	$("#all_typesSub").attr('checked', 'checked');
	$("#form_lotlist").submit();
})

$(".del_filter_js").click(function(){
	filter=$(this).data("del_filter");
  $(filter).val("");
  $("#form_lotlist").submit();
})




$(".js-check-award").click(function(){


	var thisName= $(this).attr("name");
	//recorremos el resto de filtros de estado del lote y los desmarcamos
	$(".js-check-award[name!='" + thisName + "']").each(function(){
	  $(this).attr('checked', false);
	});

	$("#form_lotlist").submit();
})

$(".js-check-my-lots").click(function(){


	var thisName= $(this).attr("name");
	//recorremos el resto de filtros de mis lotes y los desmarcamos
	$(".js-check-my-lots[name!='" + thisName + "']").each(function(){
	  $(this).attr('checked', false);
	});

	$("#form_lotlist").submit();
})


// botón de histórico

function showHistoricLink(){

	$.ajax({
				type: "POST",
				url: "showHistoricLink",
				data: $('#infiniteScrollForm').serialize(),
				success: function(numlots) {
					if(numlots>0){
						$("#seeHistoricLots_JS").removeClass("hidden");
						if(numlots >1){
							var resultadoTxt = messages.neutral.results;
						}else{
							var resultadoTxt = messages.neutral.results;
						}
						$("#seeHistoricLots_JS").removeClass("hidden");
						$("#seeHistoricLots_JS").html(messages.neutral.view + " " + numlots + " " + resultadoTxt + " " + $("#seeHistoricLots_JS").html() );


					}
				},
				error: function(){
				}
			});
}


