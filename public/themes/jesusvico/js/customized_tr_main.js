function customResponseDesign_W(data) {
	$('#value-view').html(numeral(data.siguiente).format('0,0' ) + " €");
}
