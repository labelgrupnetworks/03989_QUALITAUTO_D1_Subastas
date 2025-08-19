
<footer class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-6 col-lg-2 mb-3">
				<h5>{{ trans('web.foot.auctions') }}</h5>
				<ul class="nav flex-column">
					@if($global['auctionTypes']->where('tipo_sub', 'W')->value('count'))
					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('presenciales') }}"
							class="nav-link p-0">{{ trans('web.foot.auctions')}}</a></li>
					@endif

					<li class="nav-item mb-2"><a href="{{ \Routing::translateSeo('subastas-historicas') }}"
							class="nav-link p-0">{{ trans('web.foot.historico')}}</a></li>
				</ul>
			</div>

			<div class="col-6 col-lg-2 mb-3">
				<h5>{{ trans('web.foot.enterprise') }}</h5>
				<ul class="nav flex-column">
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans('web.links.about_us') }}"
							title="{{ trans('web.foot.about_us') }}" class="nav-link p-0">{{
							trans('web.foot.about_us') }}</a>
					</li>
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans('web.links.contact') }}"
							title="{{ trans('web.foot.contact') }}" class="nav-link p-0">{{
							trans('web.foot.contact') }}</a>
					</li>
				</ul>
			</div>

			<div class="col-6 col-lg-3 mb-3">
				<h5>{{ trans('web.foot.term_condition')}}</h5>
				<ul class="nav flex-column">
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans('web.links.legal_notice') }}"
							title="{{ trans('web.foot.legal_notice') }}" class="nav-link p-0">{{
							trans('web.foot.legal_notice') }}</a>
					</li>
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans('web.links.general_conditions') }}"
							title="{{ trans('web.foot.general_conditions') }}" class="nav-link p-0">{{
							trans('web.foot.general_conditions') }}</a>
					</li>
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans('web.links.privacy') }}"
							title="{{ trans('web.foot.privacy') }}" class="nav-link p-0">{{
							trans('web.foot.privacy') }}</a>
					</li>
					<li class="nav-item mb-2">
						<a href="{{ Routing::translateSeo('pagina').trans('web.links.cookies') }}"
							title="{{ trans('web.foot.cookies') }}" class="nav-link p-0">{{
							trans('web.foot.cookies') }}</a>
					</li>
					<li class="nav-item">
						<button class="nav-link btn btn-link" type="button" data-bs-toggle="modal" data-bs-target="#cookiesPersonalize">
							{{ trans("web.cookies.configure") }}
						</button>
					</li>
				</ul>
			</div>

			<div class="col-12 col-lg-2 order-last order-lg-4 mb-3">
				<img width="200" class="logo-company img-fluid" src="/themes/{{$theme}}/assets/img/logo_footer.png"
					alt="{{(\Config::get( 'app.name' ))}}">
			</div>

			<div class="col-6 col-lg-3 mb-3 order-5 order-lg-last text-lg-end">
				<h5>{{ $global['company']->nom_emp ?? ''}}</h5>
				<p>{{ $global['company']->dir_emp ?? ''}}</p>
				<p>{{ $global['company']->cp_emp ?? ''}} {{ $global['company']->pob_emp ?? ''}}, {{ $global['company']->pais_emp ?? ''}}</p>
				<p><a class="nav-link" href="tel:{{ $global['company']->tel1_emp ?? ''}}">{{ $global['company']->tel1_emp ?? ''}}</a></p>
				<p><a class="nav-link" href="mailto:{{ $global['company']->email_emp ?? ''}}">{{ $global['company']->email_emp ?? ''}}</a></p>
			</div>
		</div>

		<div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
			<div>
				<p>© {{ now()->format('Y') }} {!! trans('web.foot.rights') !!}.</p>
				<p><a class="nav-link" title="{{ trans('web.foot.developedSoftware') }}"
						href="{{ trans('web.foot.developed_url') }}" target="_blank">{{
						trans('web.foot.developedBy') }}</a></p>
			</div>


			{{-- <ul class="list-unstyled d-flex">
				<li class="ms-3">
					<a class="lb-text-primary" href="{{ Config::get('app.twitter') }}" target="_blank">
						<x-icon.boostrap size=24 icon=twitter></x-icon.boostrap>
					</a>
				</li>

				<li class="ms-3">
					<a class="lb-text-primary" href="{{ Config::get('app.instagram') }}" target="_blank">
						<x-icon.boostrap size=24 icon=instagram></x-icon.boostrap>
					</a>
				</li>

				<li class="ms-3">
					<a class="lb-text-primary" href="{{ Config::get('app.facebook') }}" target="_blank">
						<x-icon.boostrap size=24 icon=facebook></x-icon.boostrap>
					</a>
				</li>
				<li class="ms-3">
					<a class="lb-text-primary" href="{{ Config::get('app.pinterest') }}" target="_blank">
						<x-icon.boostrap size=24 icon=pinterest></x-icon.boostrap>
					</a>
				</li>
			</ul> --}}
		</div>
	</div>
</footer>

@if (!Cookie::get((new App\Services\Content\CookieService)->getCookieName()))
	@include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')

