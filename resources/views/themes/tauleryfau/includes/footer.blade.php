<footer>
    <div class="container">

        <div class="d-flex footer-wrapper">

            <div class="footer-logo-wrapper">
                <img class="img-responsive" alt="{{ Config::get('app.name') }}"
                    src="/themes/{{ \Config::get('app.theme') }}/assets/img/logo-footer.png">

                <div class="contacts-wrapper">
                    <ul>
                        <li>
                            <a href="tel:+34914221444">
                                <span>
                                    <i aria-hidden="true" class="fa fa-phone fa-flip-horizontal"></i>
                                </span>
                                <span>+34 91 422 14 44</span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:info@tauleryfau.com">
                                <span>
                                    <i aria-hidden="true" class="fa fa-envelope"></i>
                                </span>
                                <span>info@tauleryfau.com</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://g.page/r/CfmFhuFI7RQlEBA">
                                <span>
                                    <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                                </span>
                                <span>Marqués de Urquijo 34, 2º Ext Dcha 28008 - Madrid, España</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-pages">
                <p class="footer-pages-titles">{{ trans("$theme-app.subastas.inf_subasta_horario") }}</p>
                <p class="divider"></p>

                <ul>
					{!! trans("$theme-app.foot.schedule") !!}
                </ul>
            </div>
            <div class="footer-pages">
                <p class="footer-pages-titles"><a href="{{ wpLink('wp_sell_coins') }}">{{ trans("$theme-app.foot.sale_of_coins") }}</a></p>
                <p class="divider"></p>

                <ul>
                    <li><a href="{{ \Routing::translateSeo('subastas-activas') }}">{{ trans("$theme-app.foot.auctions") }}</a></li>
                    <li><a href="{{ wpLink('wp_calendar') }}">{{ trans("$theme-app.subastas.next_auctions") }}</a></li>
                    <li><a href="{{ wpLink('wp_valuations') }}">{{ trans("$theme-app.foot.free_valuations") }}</a></li>
                    <li><a href="{{ wpLink('wp_sell_coins') }}">{{ trans("$theme-app.foot.consign_coins") }}</a></li>
                    <li><a href="{{ wpLink('wp_contact') }}">{{ trans("$theme-app.foot.contact") }}</a></li>
                </ul>

            </div>
            <div class="footer-pages">
                <p class="footer-pages-titles"><a href="{{ wpLink('wp_services') }}">{{ trans("$theme-app.services.label_services") }}</a></p>
                <p class="divider"></p>
                <ul>
                    <li><a href="{{ wpLink('wp_sell_coins') }}">{{ trans("$theme-app.foot.sell_coins") }}</a></li>
                    <li><a href="{{ wpLink('wp_buy_coins') }}">{{ trans("$theme-app.foot.buy_coins") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_photography') }}">{{ trans("$theme-app.foot.coin_photography") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_coin_grading') }}">{{ trans("$theme-app.foot.coin_grading") }}</a>
                    </li>
                    <li><a
                            href="{{ wpLink('wp_blog') }} ">{{ trans("$theme-app.blog.blogTitle") }}</a>
                    </li>
                </ul>
            </div>
            <div class="footer-pages">
                <p class="footer-pages-titles"><a href="{{ wpLink('wp_term_condition') }}">{{ trans("$theme-app.foot.term_condition") }}</a></p>
                <p class="divider"></p>
                <ul>
                    <li><a href="{{ wpLink('wp_faq') }}">{{ trans("$theme-app.foot.faq") }}</a></li>
                    <li><a
                            href="{{ wpLink('wp_term_condition') }}">{{ trans("$theme-app.foot.auctions_conditions") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_valuations') }}">{{ trans("$theme-app.foot.shipping_terms") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_privacy') }}">{{ trans("$theme-app.foot.privacy") }}</a>
                    </li>
                    <li><a href="{{ wpLink('wp_cookie_policy') }}">{{ trans("$theme-app.cookies.title") }}</a>
                    </li>
                </ul>
            </div>


        </div>

        <div class="row copy-row">

            <div class="col-xs-12"></div>

            <div class="col-xs-12 col-md-6">
                <p class="term-pol">&copy; <?= trans(\Config::get('app.theme') . '-app.foot.rights') ?></p>
            </div>
            <div class="col-xs-12 col-md-6 text-right links-redes">
				<a href="https://www.facebook.com/tauleryfau" target="_blank">
                    <i class="fab fa-facebook-f"></i>
				</a>
                <a href="https://www.instagram.com/tauleryfau_numismatics/?hl=es" target="_blank">
                    <i class="fab fa-instagram"></i>
				</a>
                <a href="https://www.youtube.com/channel/UC-3jokocQPW-OMZMPOu03XQ" target="_blank">
                    <i class="fab fa-youtube"></i>
				</a>
            </div>

        </div>
    </div>
    @if (!Cookie::get('cookie_config'))
        @include('includes.cookie')
    @endif
</footer>

<script>
    let domain = window.location.hostname;
</script>

@if (empty($cookiesState['google']) && empty($cookiesState['all']))
    <script>
        deleteGoogleCookies(domain);

        if (domain.includes('www')) {
            deleteGoogleCookies(domain.split('www')[1]);
        }
    </script>
@endif

@if (empty($cookiesState['facebook']) && empty($cookiesState['all']))
    <script>
        deleteFacebookCookies(domain);

        if (domain.includes('www')) {
            deleteFacebookCookies(domain.split('www')[1]);
        }
    </script>
@endif
