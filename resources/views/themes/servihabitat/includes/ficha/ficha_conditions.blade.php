<div class="no-padding desc-lot-profile-content lot-conditions">
	<p><a data-toggle="modal" data-target="#generalTermsModal">{{ trans("$theme-app.subastas.general_conditions") }}</a></p>

	@if(!empty($obsdet_hces1))
		<p><a href="javascript:window.scrollTo(0, document.getElementById('obs').offsetTop - 115)">{{ trans("$theme-app.subastas.auction_conditions") }}</a></p>
	@endif
</div>
