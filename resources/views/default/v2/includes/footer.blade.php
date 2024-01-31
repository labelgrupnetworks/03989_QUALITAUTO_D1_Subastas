<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();

	$settings = $page_settings->getSettings();
 ?>

@if (count($settings) > 0)
	<div id="admin_settings_box" class="">
		<div class="gestor_buttons">
			@foreach ($settings as $setting)
				<a class="btn btn-lb-primary w-100 mb-1" href="{{ $setting['url'] }}" title="{{ $setting['name'] }}" target="_blank">{{ trans("$theme-app.foot.admin_" . $setting['name']) }}</a>
			@endforeach
		</div>
		<div class="desplegable">
			<div data-id="right">
				<svg width="20" height="20" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8"/>
				</svg>
			</div>
			<div data-id="left" class="hidden">
				<svg width="20" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"/>
				</svg>
			</div>
		</div>
	</div>
@endif

<footer class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-6 col-lg-2 mb-3">
				<h5>{{ trans($theme.'-app.foot.auctions') }}</h5>
				<ul class="nav flex-column">
					@if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('presenciales') }}"
							class="nav-link p-0 text-muted">{{ trans($theme.'-app.foot.auctions')}}</a></li>
					@endif

					@if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('subastas-online') }}"
							class="nav-link p-0 text-muted">{{ trans($theme.'-app.foot.online_auction')}}</a></li>
					@endif

					@if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('venta-directa') }}"
							class="nav-link p-0 text-muted">{{ trans($theme.'-app.foot.direct_sale')}}</a></li>
					@endif

					@if($global['subastas']->has('H'))
					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('subastas-historicas') }}"
							class="nav-link p-0 text-muted">{{ trans($theme.'-app.foot.historico')}}</a></li>
					@endif
				</ul>
			</div>

			<div class="col-6 col-lg-2 mb-3">
				<h5>{{ trans($theme.'-app.foot.enterprise') }}</h5>
				<ul class="nav flex-column">
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.about_us') }}"
							title="{{ trans($theme.'-app.foot.about_us') }}" class="nav-link p-0 text-muted">{{
							trans($theme.'-app.foot.about_us') }}</a>
					</li>
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.contact') }}"
							title="{{ trans($theme.'-app.foot.contact') }}" class="nav-link p-0 text-muted">{{
							trans($theme.'-app.foot.contact') }}</a>
					</li>
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo(trans($theme.'-app.links.faq')) }}"
							title="{{ trans($theme.'-app.foot.faq') }}" class="nav-link p-0 text-muted">{{
							trans($theme.'-app.foot.faq') }}</a>
					</li>
				</ul>
			</div>

			<div class="col-6 col-lg-3 mb-3">
				<h5>{{ trans($theme.'-app.foot.term_condition')}}</h5>
				<ul class="nav flex-column">
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition') }}"
							title="{{ trans($theme.'-app.foot.term_condition') }}" class="nav-link p-0 text-muted">{{
							trans($theme.'-app.foot.term_condition') }}</a>
					</li>
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.privacy') }}"
							title="{{ trans($theme.'-app.foot.privacy') }}" class="nav-link p-0 text-muted">{{
							trans($theme.'-app.foot.privacy') }}</a>
					</li>
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.cookies') }}"
							title="{{ trans($theme.'-app.foot.cookies') }}" class="nav-link p-0 text-muted">{{
							trans($theme.'-app.foot.cookies') }}</a>
					</li>
					<li class="nav-item">
						<button class="nav-link btn btn-link text-muted" type="button" data-bs-toggle="modal" data-bs-target="#cookiesPersonalize">
							{{ trans("$theme-app.cookies.configure") }}
						</button>
					</li>
				</ul>
			</div>

			<div class="col-12 col-lg-2 order-last order-lg-4 mb-3">
				<img width="200" class="logo-company img-fluid" src="/themes/{{$theme}}/assets/img/logo_footer.png"
					alt="{{(\Config::get( 'app.name' ))}}">
			</div>

			<div class="col-6 col-lg-3 mb-3 order-5 order-lg-last text-lg-end">
				<h5>{{ $empresa->nom_emp ?? ''}}</h5>
				<p class="text-muted">{{ $empresa->dir_emp ?? ''}}</p>
				<p class="text-muted">{{ $empresa->cp_emp ?? ''}} {{ $empresa->pob_emp ?? ''}}, {{ $empresa->pais_emp ?? ''}}</p>
				<p class="text-muted"><a class="nav-link" href="tel:{{ $empresa->tel1_emp ?? ''}}">{{ $empresa->tel1_emp ?? ''}}</a></p>
				<p class="text-muted"><a class="nav-link" href="mailto:{{ $empresa->email_emp ?? ''}}">{{ $empresa->email_emp ?? ''}}</a></p>
			</div>
		</div>

		<div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
			<div>
				<p>© {{ now()->format('Y') }} {!! trans($theme.'-app.foot.rights') !!}.</p>
				<p><a class="nav-link" title="{{ trans($theme.'-app.foot.developedSoftware') }}"
						href="{{ trans($theme.'-app.foot.developed_url') }}" target="_blank">{{
						trans($theme.'-app.foot.developedBy') }}</a></p>
				<p><a href="https://storyset.com/people">People illustrations by Storyset</a></p>
			</div>


			<ul class="list-unstyled d-flex">
				<li class="ms-3">
					<a class="lb-text-primary" href="{{ Config::get('app.twitter') }}" target="_blank">
						<svg class="bi" width="24" height="24" fill="currentColor">
							<use xlink:href="/bootstrap-icons.svg#twitter"></use>
						</svg></a></li>

				<li class="ms-3">
					<a class="lb-text-primary" href="{{ Config::get('app.instagram') }}" target="_blank">
						<svg class="bi" width="24" height="24" fill="currentColor">
							<use xlink:href="/bootstrap-icons.svg#instagram"></use>
						</svg>
					</a>
				</li>

				<li class="ms-3">
					<a class="lb-text-primary" href="{{ Config::get('app.facebook') }}" target="_blank">
						<svg class="bi" width="24" height="24" fill="currentColor">
							<use xlink:href="/bootstrap-icons.svg#facebook"></use>
						</svg>
					</a>
				</li>
				<li class="ms-3">
					<a class="lb-text-primary" href="{{ Config::get('app.pinterest') }}" target="_blank">
						<svg class="bi" width="24" height="24" fill="currentColor">
							<use xlink:href="/bootstrap-icons.svg#pinterest"></use>
						</svg>
					</a>
				</li>
			</ul>
		</div>
	</div>
</footer>

@if (!Cookie::get((new App\Models\Cookies)->getCookieName()))
	@include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')

