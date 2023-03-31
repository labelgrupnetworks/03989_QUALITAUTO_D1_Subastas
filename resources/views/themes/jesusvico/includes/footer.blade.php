<section class="container-fluid contact-banner">
	<img src="/themes/jesusvico/assets/img/2-web-contacto.webp" alt="contact background image" loading="lazy">
    <h3 class="no-decoration fs-40pt fw-lighter">
		{!! trans("$theme-app.foot.banner_contact") !!}
    </h3>
    <a class="btn btn-lb-secondary" href="{{ Routing::translateSeo('contact') }}">{{ trans("$theme-app.foot.contact") }}</a>
</section>

<footer class="py-5">
    <div class="container">
        <div class="row">

            <div class="col-12 col-lg-4 order-lg-1 mb-4 mb-lg-0">
                @include('includes.newsletter')
            </div>

            <div class="col-12 col-lg-8 order-lg-0">

                <div class="row">
                    <div class="col-6 col-lg-3 mb-3">
						@if(($global['subastas']->has('S') && $global['subastas']['S']->has('W')) || $global['subastas']->has('H'))
                        <h5>{{ trans("$theme-app.subastas.auctions") }}</h5>
                        <ul class="nav flex-column">

							@if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
								<li class="nav-item"><a class="nav-link" href="{{ \Routing::translateSeo('presenciales') }}">{{ trans("$theme-app.subastas.auctions") }}</a></li>
							@endif

							@if($global['subastas']->has('H'))
								<li class="nav-item">
									<a class="nav-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans("$theme-app.foot.historico") }}</a>
								</li>
							@endif
                        </ul>
						@endif

						<h5 class="mt-3">
							<a class="nav-link" title="{{ trans("$theme-app.foot.direct_sale") }}" href="{{ Routing::translateSeo('venta-directa') }}">{{ trans($theme.'-app.foot.direct_sale') }}</a>
						</h5>

						<h5 class="mt-3">
							<a class="nav-link" title="{{ trans("$theme-app.foot.how_to_buy") }}" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.buy_coins") }}">{{ trans("$theme-app.foot.how_to_buy") }}</a>
						</h5>

						<h5 class="mt-3">
							<a class="nav-link" title="{{ trans("$theme-app.foot.how_to_sell") }}" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.where_sell_coins") }}">{{ trans("$theme-app.foot.how_to_sell") }}</a>
						</h5>
                    </div>


                    <div class="col-6 col-lg-3 mb-3">
                        <h5>{{ trans("$theme-app.login_register.empresa") }}</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.foot.about_us") }}"
									href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.about_us") }}">
									{{ trans("$theme-app.foot.about_us") }}
								</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.foot.team") }}"
									href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.team") }}">
									{{ trans("$theme-app.foot.team") }}
								</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.foot.press") }}"
									href="{{ Routing::translateSeo('blog') . trans("$theme-app.links.press")}}">
									{{ trans("$theme-app.foot.press") }}
								</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.foot.ethical_code") }}"
									href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.ethical_code") }}">
									{{ trans("$theme-app.foot.ethical_code") }}
								</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.blog.museum-pieces") }}"
									href="{{ Routing::translateSeo('mosaic-blog') }}">
									{{ trans("$theme-app.blog.museum-pieces") }}
								</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.blog.events") }}"
									href="{{ Routing::translateSeo('events') }}">
									{{ trans("$theme-app.blog.events") }}
								</a>
							</li>
                        </ul>
                    </div>

                    <div class="col-6 col-lg-3 mb-3">
						<h5>{{ trans("$theme-app.foot.service") }}</h5>
						<ul class="nav flex-column">
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.foot.laboratory") }}"
									href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.laboratory") }}">
									{{ trans("$theme-app.foot.laboratory") }}
								</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.foot.legal_advice") }}"
									href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.legal_advice") }}">
									{{ trans("$theme-app.foot.legal_advice") }}
								</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link" title="{{ trans("$theme-app.home.free-valuations") }}"
									href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale')]) }}">
									{{ trans("$theme-app.home.free-valuations") }}
								</a>
							</li>
						</ul>


                        <h5 class="mt-3">{{ trans($theme . '-app.foot.term_condition') }}</h5>
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
                                    href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.cookies') }}"
                                    title="{{ trans($theme . '-app.foot.cookies') }}">{{ trans($theme . '-app.foot.cookies') }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-6 col-lg-3 mb-3">
                        <h5><a class="nav-link" href="{{ trans("$theme-app.links.blog") }}" target="_blank">{{ trans("$theme-app.home.blog") }}</a></h5>

						<h5 class="mt-4"><a class="nav-link" href="{{ Routing::translateSeo('contacto') }}">{{ trans($theme.'-app.foot.contact') }}</a></h5>

						<h5 class="mt-4">{{ trans($theme.'-app.subastas.inf_subasta_horario') }}</h5>
						<p>{!! trans("$theme-app.foot.schedule") !!}</p>
                    </div>

                </div>

            </div>
        </div>

		<p class="text-center mt-5 small">
			<span>© {!! trans("$theme-app.foot.rights") !!} | </span>
			<span>
				<a href="{{ trans("$theme-app.foot.developed_url") }}" title="{{ trans($theme . '-app.foot.developedSoftware') }}" target="_blank">
					{{ trans($theme . '-app.foot.developedBy') }}
				</a>
			</span>
		</p>
    </div>
</footer>

@if (!Cookie::get('cookie_config'))
    @include('includes.cookie')
@endif

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
