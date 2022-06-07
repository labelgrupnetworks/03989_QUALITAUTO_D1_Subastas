<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">

				<div class="row">

					<div class="col-xs-6 col-md-3 mb-3 text-sm-center">
						<div class="footer-title">
							<p><b>{{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</b></p>
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link" href="{{ route('allCategories', ['typeSub' => 'P']) }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a>
							</li>
							{{--
							<li>
								<a class="footer-link" href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a>
							</li>
							--}}
						</ul>
					</div>

					<div class="col-xs-6 col-md-3 mb-3 text-sm-center">
						<div class="footer-title">
							<p><b>{{ trans(\Config::get('app.theme').'-app.foot.enterprise') }}</b></p>
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link" href="{{ \Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us') }}">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a>
							</li>
							<li>
								<a class="footer-link" href="{{ \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) }}">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a>
							</li>
							<li>
								<a class="footer-link" href="{{ \Routing::translateSeo('valoracion-articulos') }}">{{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-6 col-md-3 mb-3 text-sm-center">
						<div class="footer-title">
							<p><b>{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</b></p>
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link" href="{{ \Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy') }}">{{ trans(\Config::get('app.theme').'-app.foot.legal') }}</a>
							</li>
							<li>
								<a class="footer-link" href="{{ \Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition') }}">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-6 col-md-3 mb-3 text-sm-center">
						<?php
							$empre= new \App\Models\Enterprise;
							$empresa = $empre->getEmpre();
						 ?>
						 <div class="footer-title">
							<p><b>{{ $empresa->nom_emp ?? '' }}</b></p>
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<span class="footer-link">{{ $empresa->dir_emp ?? '' }}</span>
							</li>
							<li>
								<span class="footer-link">{{ $empresa->cp_emp ?? '' }}</span> <span class="footer-link">{{ $empresa->pob_emp ?? '' }}</span>
							</li>
							<li>
								<span class="footer-link">{{ $empresa->tel1_emp ?? '' }}</span> <a href="mailto:{{ $empresa->email_emp ?? '' }}" class="footer-link">{{ $empresa->email_emp ?? '' }}</span>
							</li>
						</ul>
					</div>

				</div>


			</div>

		</div>

	</div>

	<div id="cookies-message" class="cookies-message d-flex align-items-center justify-content-space-between"
		style="display: none">
		<div>
			{!! trans(\Config::get('app.theme').'-app.msg_neutral.cookie_law') !!}
		</div>
		<button class="cookies-btn" id="cookies-btn">{{ trans(\Config::get('app.theme').'-app.home.confirm') }}</button>
	</div>
</footer>

<div class="copy">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<p>&copy; <?= trans(\Config::get('app.theme').'-app.foot.rights') ?>  </p>
			</div>
			<div class="col-xs-12">
				<a class="color-letter" role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a>
			</div>
		</div>
	</div>
</div>



<script>
	if(localStorage.cookies !== 'true'){
        $("#cookies-message").show()
    }
    $('#cookies-btn').click(function(){
        $('#cookies-message').hide();
        localStorage.cookies = 'true'
    });
</script>
