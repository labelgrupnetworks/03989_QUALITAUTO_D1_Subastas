<footer class="py-5">
    <div class="container">
        <div class="row">

            <div class="coo-12 col-lg-3 mb-3">
                <x-icon.logo />

                <div class="footer-address">
                    <p>{{ $global['company']->dir_emp ?? '' }}</p>
                    <p>{{ $global['company']->cp_emp ?? '' }} {{ $global['company']->pob_emp ?? '' }}, {{ $global['company']->pais_emp ?? '' }}</p>
                    <p><a href="tel:{{ $global['company']->tel1_emp ?? '' }}">{{ $global['company']->tel1_emp ?? '' }}</a></p>
                    <p><a href="mailto:{{ $global['company']->email_emp ?? '' }}">{{ $global['company']->email_emp ?? '' }}</a></p>
                </div>
            </div>

            <div class="col-lg-2"></div>
            <div class="coo-12  col-lg-2 mb-3">
                <h5>{{ trans($theme . '-app.foot.auctions') }}</h5>
                <ul class="nav flex-column">
                    @if ($global['auctionTypes']->where('tipo_sub', 'W')->value('count'))
                        <li class="nav-item"><a class="nav-link"
                                href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme . '-app.foot.auctions') }}</a>
                        </li>
                    @endif

					<li class="nav-item">
						<a class="nav-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">
							<span>{{ trans($theme . '-app.foot.historico') }}</span>
						</a>
					</li>
                </ul>

            </div>

            <div class="coo-12 col-lg-2 mb-3">
                <h5>{{ trans($theme . '-app.foot.enterprise') }}</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}"
                            title="{{ trans($theme . '-app.foot.about_us') }}">{{ trans($theme . '-app.foot.about_us') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact_page') }}"
                            title="{{ trans($theme . '-app.foot.contact') }}">{{ trans($theme . '-app.foot.contact') }}</a>
                    </li>
                </ul>
            </div>

            <div class="coo-12 col-lg-3 mb-3">
                <h5>{{ trans($theme . '-app.foot.terms_and_conditions') }}</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.term_condition') }}"
                            title="{{ trans($theme . '-app.foot.term_condition') }}">{{ trans($theme . '-app.foot.term_condition') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.privacy') }}"
                            title="{{ trans($theme . '-app.foot.privacy') }}">{{ trans($theme . '-app.foot.privacy') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.legal_advice') }}"
                            title="{{ trans($theme . '-app.foot.legal_advice') }}">{{ trans($theme . '-app.foot.legal_advice') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
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



        </div>

        <div class="row">
            <div class="share-links">
                <a class="share-icon" href="{{ config('app.facebook') }}" title="facebook" target="_blank">
                    <svg width="8" height="13" viewBox="0 0 8 13" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.50313 0.00266311L5.84335 0C3.97864 0 2.77358 1.23635 2.77358 3.14993V4.60226H1.10474C0.960528 4.60226 0.84375 4.71917 0.84375 4.86338V6.96764C0.84375 7.11185 0.960661 7.22863 1.10474 7.22863H2.77358V12.5383C2.77358 12.6826 2.89035 12.7993 3.03456 12.7993H5.21192C5.35613 12.7993 5.47291 12.6824 5.47291 12.5383V7.22863H7.42417C7.56838 7.22863 7.68516 7.11185 7.68516 6.96764L7.68596 4.86338C7.68596 4.79414 7.65839 4.72783 7.60953 4.67883C7.56066 4.62983 7.49408 4.60226 7.42484 4.60226H5.47291V3.3711C5.47291 2.77936 5.61392 2.47896 6.38476 2.47896L7.50287 2.47856C7.64694 2.47856 7.76372 2.36165 7.76372 2.21758V0.263648C7.76372 0.119707 7.64708 0.00292943 7.50313 0.00266311Z"
                            fill="currentColor" />
                    </svg>
                </a>
                <a class="share-icon" href="{{ config('app.twitter') }}" title="twitter" target="_blank">
                    @include('components.x-icon', ['sizeX' => '12', 'sizeY' => '10'])
                </a>
                <a class="share-icon" href="{{ config('app.youtube') }}" title="youtube" target="_blank">
                    <svg width="14" height="11" viewBox="0 0 14 11" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.2195 0.68431C9.31305 0.554184 5.06703 0.554713 3.16327 0.68431C1.10189 0.825016 0.85909 2.07021 0.84375 5.34769C0.85909 8.61936 1.09977 9.86984 3.16327 10.0111C5.06756 10.1407 9.31305 10.1412 11.2195 10.0111C13.2808 9.87037 13.5236 8.62517 13.539 5.34769C13.5236 2.07602 13.283 0.825545 11.2195 0.68431ZM5.60446 7.46356V3.23182L9.8362 5.34399L5.60446 7.46356Z"
                            fill="currentColor" />
                    </svg>
                </a>
                <a class="share-icon" href="{{ config('app.linkedin') }}" title="linkedin" target="_blank">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M3.13068 1.60508C3.13068 2.23819 2.62181 2.75118 1.99375 2.75118C1.36569 2.75118 0.856825 2.23819 0.856825 1.60508C0.856825 0.972436 1.36569 0.458984 1.99375 0.458984C2.62181 0.458984 3.13068 0.972436 3.13068 1.60508ZM3.13985 3.66806H0.847656V11.0031H3.13985V3.66806ZM6.79911 3.66806H4.52159V11.0031H6.79957V7.15265C6.79957 5.01174 9.56349 4.83662 9.56349 7.15265V11.0031H11.8502V6.35863C11.8502 2.74614 7.76 2.87771 6.79911 4.65599V3.66806Z"
                            fill="currentColor" />
                    </svg>
                </a>
                <a class="share-icon" href="{{ config('app.instagram') }}" title="instagram" target="_blank">
                    <svg width="13" height="12" viewBox="0 0 13 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6.76819 1.06788C8.35002 1.06788 8.53762 1.0738 9.16265 1.10244C10.7682 1.17551 11.5181 1.93729 11.5912 3.53097C11.6198 4.1555 11.6252 4.34311 11.6252 5.92493C11.6252 7.50725 11.6193 7.69437 11.5912 8.3189C11.5176 9.9111 10.7697 10.6744 9.16265 10.7474C8.53762 10.7761 8.351 10.782 6.76819 10.782C5.18636 10.782 4.99876 10.7761 4.37422 10.7474C2.76475 10.6739 2.01876 9.90863 1.9457 8.31841C1.91706 7.69387 1.91114 7.50676 1.91114 5.92444C1.91114 4.34261 1.91755 4.1555 1.9457 3.53047C2.01926 1.93729 2.76722 1.17501 4.37422 1.10195C4.99925 1.0738 5.18636 1.06788 6.76819 1.06788ZM6.76819 0C5.15921 0 4.95778 0.00691185 4.32584 0.0355466C2.17428 0.134287 0.978531 1.32806 0.87979 3.4816C0.850662 4.11403 0.84375 4.31546 0.84375 5.92444C0.84375 7.53342 0.850662 7.73534 0.879297 8.36728C0.978037 10.5188 2.17181 11.7146 4.32535 11.8133C4.95778 11.842 5.15921 11.8489 6.76819 11.8489C8.37717 11.8489 8.57909 11.842 9.21103 11.8133C11.3606 11.7146 12.5593 10.5208 12.6566 8.36728C12.6857 7.73534 12.6926 7.53342 12.6926 5.92444C12.6926 4.31546 12.6857 4.11403 12.6571 3.48209C12.5603 1.33251 11.3651 0.134781 9.21153 0.0360403C8.57909 0.00691185 8.37717 0 6.76819 0V0ZM6.76819 2.88224C5.08812 2.88224 3.72599 4.24437 3.72599 5.92444C3.72599 7.60451 5.08812 8.96713 6.76819 8.96713C8.44826 8.96713 9.81039 7.60501 9.81039 5.92444C9.81039 4.24437 8.44826 2.88224 6.76819 2.88224ZM6.76819 7.89925C5.6776 7.89925 4.79338 7.01552 4.79338 5.92444C4.79338 4.83385 5.6776 3.94963 6.76819 3.94963C7.85878 3.94963 8.743 4.83385 8.743 5.92444C8.743 7.01552 7.85878 7.89925 6.76819 7.89925ZM9.93085 2.05134C9.53787 2.05134 9.21943 2.36978 9.21943 2.76227C9.21943 3.15476 9.53787 3.4732 9.93085 3.4732C10.3233 3.4732 10.6413 3.15476 10.6413 2.76227C10.6413 2.36978 10.3233 2.05134 9.93085 2.05134Z"
                            fill="currentColor" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

<div class="bg-gold text-white text-center">
    <p>© {{ now()->format('Y') }} {!! trans($theme . '-app.foot.rights') !!}.</p>
    <p>
        <a class="nav-link" href="{{ trans($theme . '-app.foot.developed_url') }}"
            title="{{ trans($theme . '-app.foot.developedSoftware') }}"
            target="_blank">{{ trans($theme . '-app.foot.developedBy') }}
        </a>
    </p>
</div>

@if (!Cookie::get((new App\Models\Cookies())->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
