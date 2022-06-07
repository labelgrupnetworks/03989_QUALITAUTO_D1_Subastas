$(document).ready(function() {




	getAjaxLots();
var win = $(window);

win.scroll(function() {

endgrid = $("#endLotList").offset();



	// End of the document reached?
	if (endgrid.top - win.height()<= win.scrollTop()) {

		//si no se estan buscando ya unos lotes
		if($('#searchingPage').val() != $('#actualPage').val() && $("#lastLot").val()=="false"){
			getAjaxLots();
		}

	}
});
});

function changeURL(oldpage, oldlot){
	$("#oldpage").val(oldpage);
	$("#oldlot").val(oldlot);
	var url = location.origin + location.pathname+"?"+$("#form_lotlist").serialize() ;
	history.pushState(null, "", url);
}
function getAjaxLots(){

actualPage = parseInt($('#actualPage').val());
oldpage = parseInt($("#oldpage").val())
$.ajax({
			type: "POST",
			url: url_lots,
			data: $('#infiniteScrollForm').serialize(),
			beforeSend: function () {
				$('#loading').show();
				$('#searchingPage').val(actualPage );
				},
			success: function(html) {
				if(html == ""){
					$("#lastLot").val(true);
				}else{
				$('#lotsGrid').append(html);
				$('[data-countdown]').each(function (event) {

					var countdown = $(this);
					if(typeof countdown.data('ini') == 'undefined'){
						countdown.data('ini', new Date().getTime());
						countdown_timer(countdown)
					}

					});
				//	$('#lotsGrid').append("<br> <br> <br> <br>aa");
				}

				//Eloy: solo era necesario en Tauler con scroll infinito, creo que se puede borrar @pendiente
				if(typeof viewVideoBtnEvents != "undefined"){
					viewVideoBtnEvents();
				}

				$('#loading').hide();
				$('#actualPage').val(actualPage +1)
				//si no hemso llegado a la página que ya estábamos, esto se usa al volver atras en el navegador


				if(actualPage < oldpage ){
					$('html,body').animate({
						scrollTop:  $("#endLotList").offset().top
					  },500);
					getAjaxLots();
				}else if(actualPage == oldpage ){
					$('html,body').animate({
						scrollTop:  $("#"+ $("#oldlot").val()).offset().top
					  },500);
				}

			},
			error: function(){
				$('#loading').hide();
				console.log("error");
			}
		});
}



