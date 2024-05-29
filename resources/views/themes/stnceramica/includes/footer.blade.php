<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container-fluid">
		<div class="row p-1 mb-3 gray-background" style="height: 60px">
			<div class="col-xs-12">
				{{-- <div class="d-flex sn-footer">
					@if(!empty(\Config::get('app.instagram', '')))
						<a href="{{ (\Config::get('app.instagram')) }}" target="_blank" class="footer-sn-icon"><i class="fa fa-instagram" aria-hidden="true"></i></a>
					@endif

					@if(!empty(\Config::get('app.facebook', '')))
						<a href="{{ (\Config::get('app.facebook')) }}" target="_blank" class="footer-sn-icon"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
					@endif

					@if(!empty(\Config::get('app.linkedin', '')))
						<a href="{{ (\Config::get('app.linkedin')) }}" target="_blank" class="footer-sn-icon"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a>
					@endif

					@if(!empty(\Config::get('app.pinterest', '')))
						<a href="{{ (\Config::get('app.pinterest')) }}" target="_blank" class="footer-sn-icon"><i class="fa fa-pinterest-square" aria-hidden="true"></i></a>
					@endif
				</div> --}}
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-6">
				<div class="row">

					<div class="col-xs-12 text-center">

						<div class="footer-title">
							{{ trans($theme.'-app.foot.term_condition')}}
						</div>
						<ul class="ul-format footer-ul">
							{{-- <li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.term_condition') }}"
									href="{{Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition')}}">{{ trans($theme.'-app.foot.term_condition') }}</a>
							</li> --}}
							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.legal-warning') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.legal-warning')?>">{{ trans($theme.'-app.foot.legal-warning') }}</a>
							</li>
							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.cookies') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.cookies')?>">{{ trans($theme.'-app.foot.cookies') }}</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-lg-6">
				<div class="row footer-title">
					<div class="col-xs-12 image text-center">
						<img class="logo-company" src="/themes/{{$theme}}/assets/img/logo_footer.png"
							alt="{{(\Config::get( 'app.name' ))}}" width="90%">

						<p class="mt-1"><a href="mailto:info@tilesales.com" class="footer-link" style="font-weight: 400">info@tilesales.es</a></p>
					</div>


				</div>
			</div>
		</div>
	</div>
</footer>

