<?php
$empre = new \App\Models\Enterprise();
$empresa = $empre->getEmpre();
?>

<footer>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-md-4">
				<h4 class="footer-title">CONTACTO</h4>
				<div class="footer-content">
					{!! trans($theme.'-app.foot.contact_info') !!}
					<a class="footer-link" href="http://www.subastassegre.es/recibir-catalogos/">Recibir catálogos</a>
				</div>
				<div class="social-link-container">
					@if (\Config::get('app.facebook'))
						<a class="facebook-social-link social-link" href="{{ \Config::get('app.facebook') }}" target="_blank">
							<i class="fa fa-facebook-square social-link-icon" aria-hidden="true"></i>
						</a>
					@endif
					@if (\Config::get('app.twitter'))
						<a class="twitter-social-link social-link" href="{{ \Config::get('app.twitter') }}" target="_blank">
							<i class="fa fa-twitter-square social-link-icon" aria-hidden="true"></i>
						</a>
					@endif
					@if (\Config::get('app.youtube'))
						<a class="youtube-social-link social-link" href="{{ \Config::get('app.youtube') }}" target="_blank">
							<i class="fa fa-youtube-square social-link-icon" aria-hidden="true"></i>
						</a>
					@endif
					@if (\Config::get('app.instagram'))
						<a class="instagram-social-link social-link" href="{{ \Config::get('app.instagram') }}" target="_blank">
							<i class="fa fa-instagram social-link-icon" aria-hidden="true"></i>
						</a>
					@endif
					@if (\Config::get('app.linkedin'))
						<a class="linkedin-social-link social-link" href="{{ \Config::get('app.linkedin') }}" target="_blank">
							<i class="fa fa-linkedin-square social-link-icon" aria-hidden="true"></i>
						</a>

					@endif
				</div>
			</div>

			<div class="col-xs-12 col-md-4">
				<section>
					<h4 class="footer-title">{{ trans($theme.'-app.foot.shcedule_title') }}</h4>
					<div class="footer-content">
						<p>
							{!! trans($theme.'-app.foot.segre_schedule') !!}
						</p>
					</div>
				</section>
				<section>
					<h4 class="footer-title">{{ trans($theme.'-app.foot.download_app') }}</h4>
					<div class="footer-content">
						<a href="{{ trans($theme.'-app.links.segre_app_apple') }}" target="_blank">
							<img class="" src="/themes/{{ \Config::get('app.theme') }}/assets/img/1logoapple200.png" alt=""
								width="200" height="72">
						</a>

						<a href="{{ trans($theme.'-app.links.segre_app_android') }}" target="_blank">
							<img class="" src="/themes/{{ \Config::get('app.theme') }}/assets/img/1logoandroid200.png" alt=""
								width="200" height="72">
						</a>
					</div>
				</section>
			</div>

			<div class="col-xs-12 col-md-4">
				<h4 class="footer-title">{{ trans($theme.'-app.foot.legal') }}</h4>
				<div class="footer-content">
					<ul>
						<li class="footer-list">
							<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.general-conditions') }}" class="footer-link">
								{{ trans($theme.'-app.foot.general-conditions') }}
							</a>
						</li>
						<li class="footer-list">
							<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.legal-warning') }}" class="footer-link">
								{{ trans($theme.'-app.foot.legal-warning') }}
							</a>
						</li>
						<li class="footer-list">
							<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.privacy_policy') }}" class="footer-link">
								{{ trans($theme.'-app.foot.privacy_policy') }}
							</a>
						</li>
						<li class="footer-list">
							<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.cookies_policy') }}" class="footer-link">
								{{ trans($theme.'-app.foot.cookies_policy') }}
							</a>
						</li>
						<li class="footer-list">
							<a href="{{ trans($theme.'-app.links.ethical_code') }}" target="_blank" class="footer-link">
								{{ trans($theme.'-app.foot.ethical_code') }}
							</a>
						</li>
						<li class="footer-list">
							<a href="{{ trans($theme.'-app.links.anticorruption_policy') }}" target="_blank" class="footer-link">
								{{ trans($theme.'-app.foot.anticorruption_policy') }}
							</a>
						</li>
						<li class="footer-list">
							<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.shipping_returns') }}" class="footer-link">
								{{ trans($theme.'-app.foot.shipping_returns') }}
							</a>
						</li>
						<li class="footer-list">
							<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.jewelery_watch_cataloging') }}" class="footer-link">
								{{ trans($theme.'-app.foot.jewelery_watch_cataloging') }}
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	{{-- <div class="row">
		<div class="col-xs-12 col-sm-5 image">
			<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png"  alt="{{(\Config::get( 'app.name' ))}}" width="90%">
		</div>
		<div class="col-xs-12 col-sm-7 enterprise text-justify">
			<div class="row">
				<div class="col-xs-12 col-sm-6 no-padding">
					<b>< ?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?></b> <br>
					< ?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?><br>
					< ?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?> < ?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> , < ?= !empty($empresa->pais_emp)? $empresa->pais_emp : ''; ?></br>
				</div>
				<div class="col-xs-12 col-sm-6">
					<br>< ?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?><br>
					<a title="< ?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>" href="mailto:< ?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>">
						< ?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>
					</a>
				</div>
			</div>
		</div>
	</div> --}}
</footer>








<div class="copy color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<p>&copy; <?= trans(\Config::get('app.theme') . '-app.foot.rights') ?> </p>
			</div>
			<div class="col-xs-12 col-sm-6 social-links">
				{{-- <span class="social-links-title">< ?= trans(\Config::get('app.theme') . '-app.foot.follow_us') ?></span>

				<a class="social-link color-letter"><i class="fab fa-2x fa-facebook-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-twitter-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-instagram"></i></a>
				<br> --}}
			</div>

			<div class="col-xs-12">
				<a class="color-letter" role="button"
					title="{{ trans(\Config::get('app.theme') . '-app.foot.developedSoftware') }}"
					href="{{ trans(\Config::get('app.theme') . '-app.foot.developed_url') }}"
					target="no_blank">{{ trans(\Config::get('app.theme') . '-app.foot.developedBy') }}</a>
			</div>
		</div>
	</div>
</div>

@if (!Cookie::get('cookie_config'))
	@include('includes.cookie')
	<script>
	 cookie_law();
	</script>
@endif
