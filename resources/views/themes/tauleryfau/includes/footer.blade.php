@php
    if (!function_exists('wpLink')) {
        function wpLink($code)
        {
            $wpDomain = 'https://www.tauleryfau.com/';
            return $wpDomain . trans(config('app.theme') . "-app.links.$code");
        }
    }
@endphp
<footer class="custom-footer">
    <div class="container">

        <div class="row footer-row">
            <div class="col-xs-12 col-sm-6 col-md-3">
                <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo-footer.png"
                    alt="{{ Config::get('app.name') }}">

                <div class="footer-enterprise">
                    <ul>
                        <li>
                            <a href="tel:+34914221444">
                                <x-icon.fontawesome type="solid" icon="phone-alt" version="5" />
                                <span>+34 91 422 14 44</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://g.page/r/CfmFhuFI7RQlEBA">
                                <x-icon.fontawesome type="solid" icon="map-marked-alt" version="5" />
                                <span>Marqués de Urquijo 34,<br>28008 - Madrid, España</span>
                            </a>
                        </li>

                        @if (Config::get('app.locale') == 'es')
                            <li>
                                <a class="btn-link" href="https://tauleryfau.as.me/schedule.php">
                                    <x-icon.fontawesome type="solid" icon="calendar-alt" version="5" />
                                    <span>{{ trans("$theme-app.foot.order_a_date") }}</span>
                                </a>

                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <p class="footer-pages-titles"><a
                        href="{{ wpLink('wp_subastas') }}">{{ trans("$theme-app.foot.auctions") }}</a></p>
                <p class="divider"></p>

                <ul>
                    <li><a href="{{ wpLink('wp_subastas') }}">{{ trans("$theme-app.foot.auctions-active") }}</a></li>
                    <li><a href="{{ wpLink('wp_calendar') }}">{{ trans("$theme-app.subastas.next_auctions") }}</a></li>
                    <li><a href="{{ wpLink('wp_history_auctions') }}">{{ trans("$theme-app.foot.history_auctions") }}</a></li>
                    <li><a href="{{ wpLink('wp_events') }}">{{ trans("$theme-app.foot.envent_large") }}</a></li>
                    <li><a href="{{ wpLink('wp_blog') }}">{{ trans("$theme-app.foot.blog_large") }}</a></li>
                </ul>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <p class="footer-pages-titles"><a
                        href="{{ wpLink('wp_services') }}">{{ trans("$theme-app.services.label_services") }}</a></p>
                <p class="divider"></p>
                <ul>
                    <li><a href="{{ wpLink('wp_sell_coins') }}">{{ trans("$theme-app.foot.sell_coins") }}</a></li>
                    <li><a href="{{ wpLink('wp_buy_coins') }}">{{ trans("$theme-app.foot.buy_coins") }}</a></li>
					<li><a href="{{ wpLink('wp_valuations') }}">{{ trans('web.foot.valuations') }}</a></li>
                    <li><a href="{{ wpLink('wp_photography') }}">{{ trans("$theme-app.foot.coin_photography_large") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_coin_grading') }}">{{ trans("$theme-app.foot.coin_grading_large") }}</a>
                    </li>
                </ul>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <p class="footer-pages-titles"><a
                        href="{{ wpLink('wp_term_condition') }}">{{ trans("$theme-app.foot.term_condition") }}</a></p>
                <p class="divider"></p>
                <ul>
                    <li><a href="{{ wpLink('wp_faq') }}">{{ trans("$theme-app.foot.faq") }}</a></li>
                    <li><a
                            href="{{ wpLink('wp_term_condition') }}">{{ trans("$theme-app.foot.auctions_conditions") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_shipping_terms') }}">{{ trans("$theme-app.foot.shipping_terms") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_privacy') }}">{{ trans("$theme-app.foot.privacy") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_cookie_policy') }}">{{ trans("$theme-app.cookies.title") }}</a>
                    </li>
                    @if (Config::get('app.locale') == 'es')
                        <li>
                            <a href="{{ wpLink('wp_legal') }}">{{ trans("$theme-app.foot.legal") }}</a>
                        </li>
                    @endif
                    <li>
                        <button class="footer-link footer-link-button" data-toggle="modal"
                            data-target="#cookiesPersonalize" type="button">
                            {{ trans("$theme-app.cookies.configure") }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row pt-2">

            <div class="col-xs-12 d-flex justify-content-space-bettween align-items-center links-redes-wrapper">
                <div class="links-redes">
					{{-- whatsapp --}}
					<a class="btn btn-footer" href="https://api.whatsapp.com/send?phone=34680803167&text=Hola,%20me%20gustaría%20saber%20más%20sobre%20sus%20servicios."
						target="_blank">
						<x-icon.fontawesome type="brands" icon="whatsapp" version="5" />
					</a>
                    <a class="btn btn-footer" href="https://www.instagram.com/tauleryfau_numismatics/?hl=es" target="_blank">
                        <x-icon.fontawesome type="brands" icon="instagram" version="5" />
                    </a>
					<a class="btn btn-footer" href="https://www.facebook.com/tauleryfau" target="_blank">
                        <x-icon.fontawesome type="brands" icon="facebook-f" version="5" />
                    </a>
                    <a class="btn btn-footer" href="https://www.youtube.com/channel/UC-3jokocQPW-OMZMPOu03XQ" target="_blank">
                        <x-icon.fontawesome type="brands" icon="youtube" version="5" />
                    </a>
					{{-- tiktok --}}
					<a class="btn btn-footer" href="https://www.tiktok.com/@tauleryfau" target="_blank">
						<x-icon.fontawesome type="brands" icon="tiktok" version="5" />
					</a>

                </div>

                <a class="btn btn-footer pi-xl" href="https://mailchi.mp/tauleryfau/subscribe" target="_blank">{{ trans("web.foot.inscribete_catalogo") }}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p class="term-pol">&copy; <?= trans($theme . '-app.foot.rights') ?></p>
            </div>
        </div>

    </div>
</footer>

@if (!Cookie::get((new App\Services\Content\CookieService())->getCookieName()))
    @include('includes.cookie', ['style' => 'popover', 'position' => 'right'])
@endif

@include('includes.cookies_personalize')
