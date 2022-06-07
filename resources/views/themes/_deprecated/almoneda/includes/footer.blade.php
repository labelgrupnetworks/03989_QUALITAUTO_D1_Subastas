<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-lg-7">
				<div class="row">
					<div class="col-xs-12 col-sm-3 text-center">
						<div class="footer-title">
							{{ trans($theme.'-app.foot.auctions') }}
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link"
									href="{{ route('allCategories') }}">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}
								</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-4 text-center">
						<div class="footer-title">
							{{ trans($theme.'-app.foot.enterprise') }}
						</div>
						<ul class="ul-format footer-ul">
							<li><a class="footer-link"
									title="{{ trans($theme.'-app.foot.about_us') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.about_us')  ?>">{{ trans($theme.'-app.foot.about_us') }}</a>
							</li>
							<li>
								<a class="footer-link" title="{{ trans($theme.'-app.foot.contact')}}"
									href="<?= \Routing::translateSeo(trans($theme.'-app.links.contact')) ?>"><span>{{ trans($theme.'-app.foot.contact')}}</span></a>
							</li>
							<li><a class="footer-link" title="{{ trans($theme.'-app.foot.faq')}}"
									href="<?= \Routing::translateSeo('pagina').trans($theme.'-app.links.faq') ?>"><span>{{ trans($theme.'-app.foot.faq')}}</span></a>
							</li>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-5 text-center">

						<div class="footer-title">
							{{ trans($theme.'-app.foot.term_condition')}}
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
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.privacy')?>">{{ trans($theme.'-app.foot.privacy') }}</a>
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

			<div class="col-xs-12 col-lg-5">
				<div class="row footer-title">
					<div class="col-xs-12 col-sm-5 image">
						<img class="logo-company" src="/themes/{{$theme}}/assets/img/logo_footer.png"
							alt="{{(\Config::get( 'app.name' ))}}" width="90%">
					</div>
					<div class="col-xs-12 col-sm-7 enterprise text-justify">
						<div class="row">
							<div class="col-xs-12 col-sm-6 no-padding">
								<b>{{ $empresa->nom_emp ?? ''}}</b> <br>
								{{ $empresa->dir_emp ?? ''}}<br>
								{{ $empresa->cp_emp ?? ''}} {{ $empresa->pob_emp ?? ''}}, {{ $empresa->pais_emp ?? ''}}<br>
							</div>
							<div class="col-xs-12 col-sm-6">
								<br>{{ $empresa->tel1_emp ?? ''}}<br>
								<a title="{{ $empresa->email_emp ?? ''}}"
									href="mailto:{{ $empresa->email_emp ?? ''}}">
									{{ $empresa->email_emp ?? ''}}
								</a>
							</div>
						</div>
					</div>
				</div>
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

			@if(!empty(\Config::get('app.facebook', '')) || !empty(\Config::get('app.twitter', '')) || !empty(\Config::get('app.instagram', '')) || !empty(\Config::get('app.pinterest', '')))
			<div class="col-xs-12 col-sm-6 social-links">
				<span class="social-links-title"><?= trans($theme.'-app.foot.follow_us') ?></span>

				@if(!empty(\Config::get('app.facebook', '')))
				<a href="{{ (\Config::get('app.facebook')) }}" target="_blank" class="social-link color-letter"><i class="fab fa-2x fa-facebook-square"></i></a>
				&nbsp;
				@endif

				@if(!empty(\Config::get('app.twitter', '')))
				<a href="{{ (\Config::get('app.twitter')) }}" target="_blank" class="social-link color-letter"><i class="fab fa-2x fa-twitter-square"></i></a>
				&nbsp;
				@endif

				@if(!empty(\Config::get('app.instagram', '')))
				<a href="{{ (\Config::get('app.instagram')) }}" target="_blank" class="social-link color-letter"><i class="fab fa-2x fa-instagram"></i></a>
				&nbsp;
				@endif

				@if(!empty(\Config::get('app.pinterest', '')))
				<a href="{{ (\Config::get('app.pinterest')) }}" target="_blank" class="social-link color-letter"><i class="fab fa-2x fa-pinterest"></i></a>
				&nbsp;
				@endif

				<br>
			</div>
			@endif

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

<script>
	let domain = window.location.hostname;
</script>

@if (empty($cookiesState['google']) && empty($cookiesState['all']))
<script>
	deleteGoogleCookies(domain);

	if(domain.includes('www')){
		deleteGoogleCookies(domain.split('www')[1]);
	}
</script>
@endif

@if (empty($cookiesState['facebook']) && empty($cookiesState['all']))
<script>
	deleteFacebookCookies(domain);

	if(domain.includes('www')){
		deleteFacebookCookies(domain.split('www')[1]);
	}
</script>
@endif
