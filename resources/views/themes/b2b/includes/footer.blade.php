<footer class="py-5">
    <div class="container">
		<div class="row pb-5 border-1 border-bottom gy-5">

			<div class="col-6 col-lg-4 order-1">
                <h5>{{ trans($theme . '-app.foot.enterprise') }}</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}"
                            title="{{ trans($theme . '-app.foot.about_us') }}">{{ trans($theme . '-app.foot.about_us') }}</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.contact') }}"
                            title="{{ trans($theme . '-app.foot.contact') }}">{{ trans($theme . '-app.foot.contact') }}</a>
                    </li>
                </ul>
            </div>

            <div class="col-6 col-lg-4 order-2">
                <h5>{{ trans($theme . '-app.foot.term_condition') }}</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.term_condition') }}"
                            title="{{ trans($theme . '-app.foot.term_condition') }}">{{ trans($theme . '-app.foot.term_condition') }}</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.privacy') }}"
                            title="{{ trans($theme . '-app.foot.privacy') }}">{{ trans($theme . '-app.foot.privacy') }}</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.cookies') }}"
                            title="{{ trans($theme . '-app.foot.cookies') }}">{{ trans($theme . '-app.foot.cookies') }}</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn btn-link" data-bs-toggle="modal"
                            data-bs-target="#cookiesPersonalize" type="button">
                            {{ trans("$theme-app.cookies.configure") }}
                        </button>
                    </li>
                </ul>
            </div>

			<div class="col-12 col-lg-4 order-0 order-lg-last">
				<div class="d-flex flex-column align-items-center gap-4">
					<img class="logo-company img-fluid" src="/themes/{{ $theme }}/assets/img/logo.png"
						alt="{{ \Config::get('app.name') }}" width="200">


					<ul class="list-unstyled m-0 d-flex gap-4">
						<li>
							<a class="lb-text-primary" href="{{ Config::get('app.facebook') }}" target="_blank">
								<x-icon.boostrap size=32 icon=facebook></x-icon.boostrap>
							</a>
						</li>

						<li>
							<a class="lb-text-primary" href="{{ Config::get('app.instagram') }}" target="_blank">
								<x-icon.boostrap size=32 icon=instagram></x-icon.boostrap>
							</a>
						</li>

						<li>
							<a class="lb-text-primary" href="{{ Config::get('app.twitter') }}" target="_blank">
								<x-icon.boostrap size=32 icon=twitter></x-icon.boostrap>
							</a>
						</li>

						<li>
							<a class="lb-text-primary" href="{{ Config::get('app.pinterest') }}" target="_blank">
								<x-icon.boostrap size=32 icon=pinterest></x-icon.boostrap>
							</a>
						</li>

						<li>
							<a class="lb-text-primary" href="{{ Config::get('app.youtube') }}" target="_blank">
								<x-icon.boostrap size=32 icon=youtube></x-icon.boostrap>
							</a>
						</li>
					</ul>
				</div>
			</div>

		</div>

		<div class="mt-4">
			<p>© {{ now()->format('Y') }} {!! trans($theme . '-app.foot.rights') !!}.</p>
			<p><a class="nav-link" href="{{ trans($theme . '-app.foot.developed_url') }}"
					title="{{ trans($theme . '-app.foot.developedSoftware') }}"
					target="_blank">{{ trans($theme . '-app.foot.developedBy') }}</a></p>
		</div>
    </div>
</footer>

@if (!Cookie::get((new App\Models\Cookies())->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
