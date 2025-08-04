@inject('cookieService', App\Services\Content\CookieService::class)

@php
    $documents = ['bid_order', 'sale_order', 'general_conditions', 'buyer_rules', 'seller_rules'];
@endphp

<footer class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-5">
                <img class="logo-company img-fluid d-block m-auto"
                    src="/themes/{{ $theme }}/assets/img/logo_footer.png" alt="{{ \Config::get('app.name') }}"
                    width="150">
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-3">

            <div class="col mb-3">
				{{-- añadir formulario de contacto --}}

            </div>

            <div class="col mb-3 text-center">
                <h5>{{ trans('web.foot.legal') }}</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans('web.links.legal_notice') }}"
                            title="{{ trans('web.foot.legal_notice') }}">{{ trans('web.foot.legal_notice') }}</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans('web.links.privacy') }}"
                            title="{{ trans('web.foot.privacy') }}">{{ trans('web.foot.privacy') }}</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link p-0"
                            href="{{ Routing::translateSeo('pagina') . trans('web.links.cookies') }}"
                            title="{{ trans('web.foot.cookies') }}">{{ trans('web.foot.cookies') }}</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn btn-link mx-auto" data-bs-toggle="modal"
                            data-bs-target="#cookiesPersonalize" type="button">
                            {{ trans('web.cookies.configure') }}
                        </button>
                    </li>
                </ul>
            </div>

            <div class="col mb-3 text-center">
                <h5>{{ trans('web.foot.documents') }}</h5>
                <ul class="nav flex-column">
                    @foreach ($documents as $document)
                        <li class="nav-item mb-2">
                            <a class="nav-link p-0" href="{{ trans("web.links.$document") }}" title="{{ trans("web.foot.$document") }}"
                                target="_blank">{{ trans("web.foot.$document") }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="py-2 text-center">
            <p>© {{ now()->format('Y') }} {!! trans('web.foot.rights') !!}.</p>
            <p><a class="nav-link" href="{{ trans('web.foot.developed_url') }}"
                    title="{{ trans('web.foot.developedSoftware') }}"
                    target="_blank">{{ trans('web.foot.developedBy') }}</a></p>
        </div>
    </div>
</footer>

@if (!Cookie::get($cookieService->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
