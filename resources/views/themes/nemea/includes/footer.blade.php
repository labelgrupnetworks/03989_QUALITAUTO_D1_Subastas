<footer class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-6 col-lg-2 mb-3">
				<h5>{{ trans($theme.'-app.foot.auctions') }}</h5>
				<ul class="nav flex-column">
					@if($global['auctionTypes']->where('tipo_sub', 'W')->value('count'))
					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('presenciales') }}"
							class="nav-link p-0 text-muted">{{ trans('web.foot.auctions')}}</a></li>
					@endif

					@if($global['auctionTypes']->where('tipo_sub', 'O')->value('count'))
					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('subastas-online') }}"
							class="nav-link p-0 text-muted">{{ trans('web.foot.online_auction')}}</a></li>
					@endif

					@if($global['auctionTypes']->where('tipo_sub', 'V')->value('count'))
					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('venta-directa') }}"
							class="nav-link p-0 text-muted">{{ trans('web.foot.direct_sale')}}</a></li>
					@endif

					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('subastas-historicas') }}"
							class="nav-link p-0 text-muted">{{ trans('web.foot.historico')}}</a></li>
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
						<a href="{{ route('contact_page') }}"
							title="{{ trans($theme.'-app.foot.contact') }}" class="nav-link p-0 text-muted">
							{{ trans($theme.'-app.foot.contact') }}</a>
					</li>
					{{-- <li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo(trans($theme.'-app.links.faq')) }}"
							title="{{ trans($theme.'-app.foot.faq') }}" class="nav-link p-0 text-muted">{{
							trans($theme.'-app.foot.faq') }}</a>
					</li> --}}
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
				<img width="200" class="logo-company img-fluid" src="/themes/{{$theme}}/assets/img/logo.png"
					alt="{{(\Config::get( 'app.name' ))}}">
			</div>

			<div class="col-6 col-lg-3 mb-3 order-5 order-lg-last text-lg-end">
				<h5>{{ $global['company']->nom_emp ?? ''}}</h5>
				<p class="text-muted">{{ $global['company']->dir_emp ?? ''}}</p>
				<p class="text-muted">{{ $global['company']->cp_emp ?? ''}} {{ $global['company']->pob_emp ?? ''}}, {{ $global['company']->pais_emp ?? ''}}</p>
				<p class="text-muted"><a class="nav-link" href="tel:{{ $global['company']->tel1_emp ?? ''}}">{{ $global['company']->tel1_emp ?? ''}}</a></p>
				<p class="text-muted"><a class="nav-link" href="mailto:{{ $global['company']->email_emp ?? ''}}">{{ $global['company']->email_emp ?? ''}}</a></p>
			</div>
		</div>

		<div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
			<div>
				<p>© {{ now()->format('Y') }} {!! trans($theme.'-app.foot.rights') !!}.</p>
				<p><a class="nav-link" title="{{ trans($theme.'-app.foot.developedSoftware') }}"
						href="{{ trans($theme.'-app.foot.developed_url') }}" target="_blank">{{
						trans($theme.'-app.foot.developedBy') }}</a></p>
			</div>
		</div>
	</div>
</footer>

@if (!Cookie::get((new App\Services\Content\CookieService)->getCookieName()))
	@include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')

