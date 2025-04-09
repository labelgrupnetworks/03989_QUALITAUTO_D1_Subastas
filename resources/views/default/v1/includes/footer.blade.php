@include('includes.admin_shortcut')

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
							@if($global['auctionTypes']->where('tipo_sub', 'W')->value('count'))
							<li>
								<a class="footer-link"
									href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme.'-app.foot.auctions')}}</a>
							</li>
							@endif
							@if($global['auctionTypes']->where('tipo_sub', 'O')->value('count'))
							<li>
								<a class="footer-link"
									href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans($theme.'-app.foot.online_auction')}}</a>
							</li>
							@endif
							@if($global['auctionTypes']->where('tipo_sub', 'V')->value('count'))
							<li><a class="footer-link"
									href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans($theme.'-app.foot.direct_sale')}}</a>
							</li>
							@endif

							<li>
								<a class="footer-link"
									href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a>
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
									href="<?= \Routing::translateSeo(trans($theme.'-app.links.faq')) ?>"><span>{{ trans($theme.'-app.foot.faq')}}</span></a>
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
							<li>
								<button class="footer-link footer-link-button" type="button" data-toggle="modal" data-target="#cookiesPersonalize">
									{{ trans("$theme-app.cookies.configure") }}
								</button>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-lg-5">
				<div class="row footer-title">
					<div class="col-xs-12 col-sm-5 image">
						<img class="logo-company" src="/themes/{{$theme}}/assets/img/logo_footer.png"
							alt="{{(Config::get( 'app.name' ))}}" width="195" height="84">
					</div>
					<div class="col-xs-12 col-sm-7 enterprise text-justify">
						<div class="row">
							<div class="col-xs-12 col-sm-6 no-padding">
								<b>{{ $global['company']->nom_emp ?? ''}}</b> <br>
								{{ $global['company']->dir_emp ?? ''}}<br>
								{{ $global['company']->cp_emp ?? ''}} {{ $global['company']->pob_emp ?? ''}}, {{ $global['company']->pais_emp ?? ''}}<br>
							</div>
							<div class="col-xs-12 col-sm-6">
								<br>{{ $global['company']->tel1_emp ?? ''}}<br>
								<a title="{{ $global['company']->email_emp ?? ''}}"
									href="mailto:{{ $global['company']->email_emp ?? ''}}">
									{{ $global['company']->email_emp ?? ''}}
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

@if (!Cookie::get((new App\Models\Cookies)->getCookieName()))
	@include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
