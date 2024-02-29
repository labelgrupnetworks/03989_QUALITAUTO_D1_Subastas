@php
    $empre = new \App\Models\Enterprise();
    $empresa = $empre->getEmpre();
    $activeAuctions = $global['subastas']->has('S') ? $global['subastas']['S']->flatten() : collect([]);
@endphp

<footer class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-6 col-lg-2 mb-3">
                <h5>{{ trans($theme . '-app.foot.auctions') }}</h5>

                <ul class="nav flex-column">
                    @foreach ($activeAuctions as $auction)
                        <li class="nav-item mb-2">
                            <a class="nav-link p-0"
                                href="{{ Tools::url_auction($auction->cod_sub, $auction->name, null) }}">
                                {{ $auction->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>

            </div>

            <div class="col-6 col-lg-2 mb-3">
                <h5>{{ trans($theme . '-app.foot.enterprise') }}</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}"
                            title="{{ trans($theme . '-app.foot.about_us') }}">
							{{ trans($theme . '-app.foot.about_us') }}
						</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0" href="https://cristinamoriones.com/cita-subastas-inmobiliarias/"
                            title="{{ trans("$theme-app.foot.schedule_consulting") }}" target="_blank">
                            {{ trans("$theme-app.foot.schedule_consulting") }}</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0" href="{{ route('contact_page') }}"
                            title="{{ trans($theme . '-app.foot.contact') }}">{{ trans($theme . '-app.foot.contact') }}</a>
                    </li>
                </ul>
            </div>

            <div class="col-6 col-lg-3 mb-3">
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
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.legal_warning') }}"
                            title="{{ trans($theme . '-app.foot.legal_warning') }}">{{ trans($theme . '-app.foot.legal_warning') }}</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.cookies') }}"
                            title="{{ trans($theme . '-app.foot.cookies') }}">{{ trans($theme . '-app.foot.cookies') }}</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn btn-link text-capitalize" data-bs-toggle="modal"
                            data-bs-target="#cookiesPersonalize" type="button">
                            {{ trans("$theme-app.cookies.configure") }}
                        </button>
                    </li>
                </ul>
            </div>

            <div class="col-12 col-lg-2 order-last order-lg-4 mb-3">
                <img class="logo-company img-fluid" src="/themes/{{ $theme }}/assets/img/logo.png"
                    alt="{{ \Config::get('app.name') }}" width="200">
            </div>

            <div class="col-6 col-lg-3 mb-3 order-5 order-lg-last text-lg-end">
                <h5>{{ $empresa->nom_emp ?? '' }}</h5>
                <a class="nav-link"
                    href="https://www.google.com/maps/dir//C.+de+Ferraz,+2,+Moncloa+-+Aravaca,+28008+Madrid/data=!4m6!4m5!1m1!4e2!1m2!1m1!1s0xd42286fe7e7cb7d:0x75aedbce67571564?sa=X&ved=2ahUKEwjQpOWj4tWDAxXFT6QEHaTqDgsQwwV6BAgQEAA"
                    target="_blank">
                    <p>
                        {{ $empresa->dir_emp ?? '' }}
                    </p>
                    <p>
                        {{ trans("$theme-app.foot.address_expansion") }}
                    </p>
                    <p>{{ $empresa->cp_emp ?? '' }} {{ $empresa->pob_emp ?? '' }},
                        {{ $empresa->pais_emp ?? '' }}</p>
                </a>
                <p>
					<a class="nav-link" href="tel:{{ $empresa->tel1_emp ?? '' }}">{{ $empresa->tel1_emp ?? '' }}</a>
                </p>
                <p>
					<a class="nav-link" href="tel:+34910289301">+34 910 289 301</a>
                </p>
                <p><a class="nav-link"
                        href="mailto:{{ $empresa->email_emp ?? '' }}">{{ $empresa->email_emp ?? '' }}</a></p>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
            <div>
                <p>© {{ now()->format('Y') }} {!! trans($theme . '-app.foot.rights') !!}.</p>
            </div>


            <ul class="list-unstyled d-flex">

                <li class="ms-3">
                    <a class="lb-text-primary"
                        href="https://instagram.com/cristinamoriones.subastas?igshid=YTQwZjQ0NmI0OA%3D%3D&utm_source=qr"
                        target="_blank">
                        <svg class="bi" width="24" height="24" fill="currentColor">
                            <use xlink:href="/bootstrap-icons.svg#instagram"></use>
                        </svg>
                    </a>
                </li>

                <li class="ms-3">
                    <a class="lb-text-primary" href="http://www.tiktok.com/@inmueblesdesubasta" target="_blank">
                        <svg class="bi" width="24" height="24" fill="currentColor">
                            <use xlink:href="/bootstrap-icons.svg#tiktok"></use>
                        </svg>
                    </a>
                </li>

                <li class="ms-3">
                    <a class="lb-text-primary"
                        href="https://www.linkedin.com/in/cristinamoriones?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app"
                        target="_blank">
                        <svg class="bi" width="24" height="24" fill="currentColor">
                            <use xlink:href="/bootstrap-icons.svg#linkedin"></use>
                        </svg>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</footer>

@if (!Cookie::get((new App\Models\Cookies())->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
