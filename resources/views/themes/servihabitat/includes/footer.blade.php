<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container">
		<div class="row">

			<div class="col-xs-12 col-md-3 mb-2">
				<div class="footer-title">
					{{ trans("$theme-app.foot.corporate") }}
				</div>

				<ul class="ul-format footer-ul">
					<li>
						<a class="footer-link" href="https://www.servihabitat.com/es/corporate" target="_blank">{{ trans("$theme-app.foot.about_servihabitat") }}</a>
					</li>
					<li>
						<a class="footer-link" href="https://inversores.servihabitat.com/es/" target="_blank">{{ trans("$theme-app.foot.professionals") }}</a>
					</li>
					<li>
						<a class="footer-link" href="https://www.servihabitat.com/es/" target="_blank">{{ trans("$theme-app.foot.individuals") }}</a>
					</li>
				</ul>

			</div>

			<div class="col-xs-12 col-md-3 mb-2">

				<div class="footer-title">
					{{ trans($theme.'-app.foot.legal')}}
				</div>
				<ul class="ul-format footer-ul">
					<li>
						<a class="footer-link"
							title="{{ trans($theme.'-app.foot.term_condition') }}"
							href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition')?>">{{ trans($theme.'-app.foot.term_condition') }}</a>
					</li>
					<li>
						<a class="footer-link"
							title="{{ trans($theme.'-app.foot.privacy') }}"
							href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition').'#privacidad'?>">{{ trans($theme.'-app.foot.privacy') }}</a>
					</li>
					<li>
						<a class="footer-link"
							title="{{ trans($theme.'-app.foot.cookies') }}"
							href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.cookies')?>">{{ trans($theme.'-app.foot.cookies') }}</a>
					</li>
				</ul>
			</div>

			<div class="col-xs-12 col-md-3 mb-2">
				<div class="footer-title">
					{{ trans($theme.'-app.foot.contact')}}
				</div>
				<ul class="ul-format footer-ul">
					<li>
						<a class="footer-link"
							href="mailto:procesosventa@servihabitat.com">procesosventa@servihabitat.com</a>
					</li>
				</ul>
			</div>

			<div class="col-xs-12 col-md-3 mb-2">


				@if(!empty(\Config::get('app.facebook', '')) || !empty(\Config::get('app.twitter', '')) || !empty(\Config::get('app.instagram', '')) || !empty(\Config::get('app.pinterest', '')))

				<div class="footer-title">
					{{ trans($theme.'-app.foot.follow_us')}}
				</div>

				<div class="redes">
					@if(!empty(\Config::get('app.twitter', '')))
					<a href="{{ (\Config::get('app.twitter')) }}" target="_blank" class="social-link">
						@include('components.x-icon', ['size' => '32'])
					</a>
					@endif

					@if(!empty(\Config::get('app.facebook', '')))
					<a href="{{ (\Config::get('app.facebook')) }}" target="_blank" class="social-link"><i class="fa fa-facebook fa-2x"></i></a>
					@endif

					@if(!empty(\Config::get('app.instagram', '')))
					<a href="{{ (\Config::get('app.instagram')) }}" target="_blank" class="social-link"><i class="fab fa-2x fa-instagram"></i></a>
					@endif

					@if(!empty(\Config::get('app.pinterest', '')))
					<a href="{{ (\Config::get('app.pinterest')) }}" target="_blank" class="social-link"><i class="fab fa-2x fa-pinterest"></i></a>
					@endif

					@if(!empty(\Config::get('app.youtube', '')))
					<a href="{{ (\Config::get('app.youtube')) }}" target="_blank" class="social-link"><i class="fab fa-2x fa-youtube"></i></a>
					@endif
				</div>
				@endif

			</div>

		</div>
	</div>
</footer>


<div class="copy color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<p>&copy; <?= trans($theme.'-app.foot.rights') ?> </p>
			</div>

			<div class="col-xs-12">
				<a class="color-letter" role="button"
					title="{{ trans($theme.'-app.foot.developedSoftware') }}"
					href="{{ trans($theme.'-app.foot.developed_url') }}"
					target="no_blank">{{ trans($theme.'-app.foot.developedBy') }}</a>
			</div>
		</div>
	</div>
</div>

@if (!Cookie::get("cookie_config"))
	@include("includes.cookie")
@endif
