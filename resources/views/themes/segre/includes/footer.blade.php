
<footer class="py-5 border-top">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-4">
                <x-icon.logo class="img-fluid" width="200" />
                <div class="text-color-light">
                    <p>Segre, 18.</p>
                    <p class="mb-3">28002 Madrid</p>

                    <p><a class="text-decoration-none" href="tel:+34915159584">T 915 159 584</a></p>
                    <p>
                        <a class="text-decoration-none" href="mailto:info@subastassegre.es">info@subastassegre.es</a>
                    </p>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="row">
                    <div class="col-12 col-lg-8 footer-links">
                        <div class="row">
                            <div class="col-4 col-md-6">
                                <h5>
                                    Menú
                                </h5>
                                <ul class="nav flex-column">
                                    @if (data_get($global, 'subastas.S.W', []))
                                        <li class="nav-item mb-2">
                                            <a class="nav-link"
                                                href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme . '-app.foot.auctions') }}
                                            </a>
                                        </li>
                                    @endif
                                    @if (data_get($global, 'subastas.H', []))
                                        <li class="nav-item mb-2">
                                            <a class="nav-link"
                                                href="{{ \Routing::translateSeo('subastas-historicas') }}">
                                                {{ trans($theme . '-app.foot.historico') }}
                                            </a>
                                        </li>
                                    @endif
									<li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ route('contact_page') }}"
                                            title="{{ trans('web.foot.contact') }}">
                                            {{ trans('web.foot.contact') }}
                                        </a>
                                    </li>

                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ Routing::translateSeo('pagina') . trans("$theme-app.segre-enlaces.experts") }}"
                                            title="{{ trans($theme . '-app.foot.experts') }}">
                                            {{ trans('web.foot.experts') }}
                                        </a>
                                    </li>

									<li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale')]) }}"
                                            title="{{ trans("$theme-app.home.free-valuations") }}">
											{{ trans("$theme-app.home.free-valuations") }}
                                        </a>
                                    </li>

									<li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ Routing::translateSeo('pagina') . trans("web.segre-enlaces.other_services") }}"
                                            title="{{ trans('web.foot.other_services') }}">
                                            {{ trans('web.foot.other_services') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-8 col-md-6">
                                <h5>
                                    {{ trans("$theme-app.foot.legal") }}
                                </h5>
                                <ul class="nav flex-column">

                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.general-conditions") }}"
                                            title="{{ trans("$theme-app.foot.general-conditions") }}">
                                            {{ trans("$theme-app.foot.general-conditions") }}
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.legal-warning") }}"
                                            title="{{ trans("$theme-app.foot.legal-warning") }}">
                                            {{ trans("$theme-app.foot.legal-warning") }}
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.privacy_policy") }}"
                                            title="{{ trans("$theme-app.foot.privacy_policy") }}">
                                            {{ trans("$theme-app.foot.privacy_policy") }}
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.cookies_policy") }}"
                                            title="{{ trans("$theme-app.foot.cookies_policy") }}">
                                            {{ trans("$theme-app.foot.cookies_policy") }}
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ trans("$theme-app.links.ethical_code") }}"
                                            title="{{ trans("$theme-app.foot.ethical_code") }}" target="_blank">
                                            {{ trans("$theme-app.foot.ethical_code") }}
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ trans("$theme-app.links.anticorruption_policy") }}"
                                            title="{{ trans("$theme-app.foot.anticorruption_policy") }}" target="_blank">
                                            {{ trans("$theme-app.foot.anticorruption_policy") }}
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.shipping_returns") }}"
                                            title="{{ trans("$theme-app.foot.shipping_returns") }}">
                                            {{ trans("$theme-app.foot.shipping_returns") }}
                                        </a>
                                    </li>
                                    <li class="nav-item mb-2">
                                        <a class="nav-link"
                                            href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.jewelery_watch_cataloging") }}"
                                            title="{{ trans("$theme-app.foot.jewelery_watch_cataloging") }}">
                                            {{ trans("$theme-app.foot.jewelery_watch_cataloging") }}
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <button class="nav-link btn btn-link" data-bs-toggle="modal"
                                            data-bs-target="#cookiesPersonalize" type="button">
                                            {{ trans("$theme-app.cookies.configure") }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mb-3">
                        <h5>
                            Newsletter
                        </h5>

                        @include('includes.newsletter')

                    </div>
                </div>
            </div>

        </div>

        <div class="border-top py-3 d-flex flex-wrap justify-content-between">
            <p>© {{ now()->format('Y') }} {!! trans($theme . '-app.foot.rights') !!}.</p>
            <p class="text-muted">
                <a class="text-decoration-none" href="{{ trans($theme . '-app.foot.developed_url') }}"
                    title="{{ trans($theme . '-app.foot.developedSoftware') }}"
                    target="_blank">{{ trans($theme . '-app.foot.developedBy') }}</a>
            </p>

        </div>
    </div>
</footer>

@if (!Cookie::get((new App\Services\Content\CookieService())->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
