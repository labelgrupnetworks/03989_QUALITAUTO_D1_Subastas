@php
    $lang = Config::get('app.locale');
    $languages = Config::get('app.locales');
    $google_langs = ['de', 'ca', 'fr'];

	$isHomePage = Route::currentRouteName() == 'home';

    $hasPresencial = $global['subastas']->has('S') && $global['subastas']['S']->has('W');
    $urlPresencial = Routing::translateSeo('presenciales');
    if ($hasPresencial && $global['subastas']['S']['W']->count() == 1) {
        $subasta = $global['subastas']['S']['W']->flatten()->first();
        $urlPresencial = Routing::translateSeo('info-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name);
    }

    $hasOnline = $global['subastas']->has('S') && $global['subastas']['S']->has('O');
    $hasPermanent = $global['subastas']->has('S') && $global['subastas']['S']->has('P');
    $hasVentaDirecta = $global['subastas']->has('S') && $global['subastas']['S']->has('V');

    $hasJewelryAuction = $hasVentaDirecta && $global['subastas']['S']['V']->has('VDJ');
    $urlJewelryAuction = '';
    if ($hasJewelryAuction) {
        $subasta = $global['subastas']['S']['V']['VDJ']->flatten()->first();
        $urlJewelryAuction =
            Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference) .
            '?only_salable=on';

			$global['subastas']['S']['V']->forget('VDJ');
    }


    $hasVentaDirecta = $hasVentaDirecta && $global['subastas']['S']['V']->count() > 0;
    $urlVentaDirecta = route('subastas.venta_directa');
    if ($hasVentaDirecta && $global['subastas']['S']['V']->count() == 1) {
        $subasta = $global['subastas']['S']['V']->flatten()->first();
        $urlVentaDirecta =
            Tools::url_auction($subasta->cod_sub, $subasta->name, $subasta->id_auc_sessions, $subasta->reference) .
            '?only_salable=on';
    }
@endphp

<header @class(['fixed' => $isHomePage])>

    <nav class="navbar navbar-custom">
        <div class="navbar-custom_first-nav">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                        aria-expanded="false"><span class="caret"></span> Sobre nosotros</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.about_us") }}">
                                {{ trans("$theme-app.foot.about_us") }}
                            </a>
                        </li>
						<li>
                            <a href="{{ Routing::translateSeo('departamentos') }}">
                                Departamentos
                            </a>
                        </li>
                        <li>
                            <a href="{{ Routing::translateSeo('valoracion-articulos') }}">
                                {{ trans("$theme-app.home.free-valuations") }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ Routing::translateSeo(trans("$theme-app.links.contact")) }}">
                        {{ trans("$theme-app.foot.contact") }}
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                        aria-expanded="false"><span class="caret"></span> Vender-comprar</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.how_to_buy") }}"
                                title="{{ trans("$theme-app.home.how_to_buy") }}">
                                {{ trans("$theme-app.home.how_to_buy") }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.how_to_sell") }}"
                                title="{{ trans("$theme-app.foot.how_to_sell") }}">
                                {{ trans("$theme-app.foot.how_to_sell") }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ Routing::translateSeo('blog') }}">{{ trans("$theme-app.blog.blogTitle") }}</a>
                </li>

            </ul>

            <div class="navbar-custom_column">
                <ul class="nav navbar-nav">
                    @if (!Session::has('user'))
                        <li>
                            <button class="btn-link btn_login_desktop">
                                {{ trans("$theme-app.login_register.login") }}
                            </button>
                        </li>

                        <li>|</li>
                        <li>
                            <a href="{{ Routing::slug('register') }}"
                                title="{{ trans("$theme-app.login_register.register") }}">
                                {{ trans("$theme-app.login_register.register") }}
                            </a>
                        </li>
                    @else
                        {{--
						<span class="name_user_logged"></span>
						<p class="cortar name_user">
						{{ trans_choice($theme . '-app.user_panel.hello', 1, ['name' => mb_convert_case(Session::get('user.name'), MB_CASE_TITLE, 'UTF-8')]) }}
						</p>
					--}}
                        <li>
                            <a href="{{ Routing::slug('user/panel/orders') }}">
                                {{ trans("$theme-app.login_register.my_panel") }}
                            </a>
                        </li>

                        @if (Session::get('user.admin'))
                            <li>|</li>
                            <li>
                                <a href="/admin" target = "_blank">
                                    {{ trans("$theme-app.login_register.admin") }}
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ Routing::slug('logout') }}">
                                {{ trans("$theme-app.login_register.logout") }}
                            </a>
                        </li>

                    @endif
                </ul>
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false"><span class="caret"></span> Idioma</a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            @foreach ($languages as $langKey => $language)
                                <li>
                                    <a href="/{{ $langKey }}"
                                        title="{{ trans("$theme-app.head.language_$langKey") }}">
                                        {{ $language }}
                                    </a>
                                </li>
                            @endforeach

                            @foreach ($google_langs as $value)
                                <li>
                                    <a href="/?#googtrans(es|{{ $value }})"
                                        title="{{ trans("$theme-app.head.language_$value") }}" translate="no">
                                        {{ $value }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="login_desktop" style="display:none;">
                <div class="login_desktop_title">
                    {{ trans($theme . '-app.login_register.login') }}
                </div>
                <img class="closedd" src="/themes/{{ $theme }}/assets/img/shape.png" alt="Close">

                <form id="accerder-user-form" data-toggle="validator">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="usuario">{{ trans($theme . '-app.login_register.user') }}</label>
                        <input class="form-control" name="email" type="email" type="text"
                            placeholder="{{ trans($theme . '-app.login_register.user') }}">
                    </div>
                    <div class="form-group">
                        <label for="contraseña">{{ trans($theme . '-app.login_register.contraseña') }}</label>
                        <input class="form-control" name="password" type="password"
                            placeholder="{{ trans($theme . '-app.login_register.contraseña') }}" maxlength="20">
                    </div>
                    <p><a class="c_bordered" id="p_recovery" data-ref="{{ \Routing::slug('password_recovery') }}"
                            data-title="{{ trans($theme . '-app.login_register.forgotten_pass_question') }}"
                            data-toggle="modal" data-target="#modalAjax" href="javascript:;"
                            onclick="cerrarLogin();">{{ trans($theme . '-app.login_register.forgotten_pass_question') }}</a>
                    </p>
                    <p>
                    <h5 class="message-error-log text-danger"></h5>
                    </p>
                    <button class="btn btn-login-desktop" id="accerder-user"
                        type="button">{{ trans($theme . '-app.login_register.acceder') }}</button>
                </form>
            </div>

        </div><!-- /.navbar-first -->

        <div class="navbar-custom_second-nav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" id="btnResponsive" data-toggle="collapse" data-target="#navbar"
                    type="button" aria-expanded="false" aria-controls="navbar">

                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="/{{ $lang }}" title="{{ config('app.name') }}">
                    <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo.png"
                        alt="{{ config('app.name') }}" width="500">
                </a>

                <ul class="nav navbar-nav navbar-icons">
                    <li>
                        <a class="search_btn" href="{{ Routing::slug('busqueda') }}" title="Buscar"><i
                                class="fa fa-search"></i></a>
                    </li>
                    <li>
                        @if (Session::has('user'))
                            <a href="{{ Routing::slug('user/panel/orders') }}">
                                <i class="fa fa-user"></i>
                            </a>
                        @else
                            <button class="btn-link login">
                                <i class="fa fa-user"></i>
                            </button>
                        @endif
                    </li>
                </ul>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="navbar-list">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false"><span class="caret"></span>
                            {{ trans("$theme-app.foot.presenciales") }}
                        </a>

                        <ul class="dropdown-menu">
                            @if ($hasPresencial)
                                <li>
                                    <a href="{{ $urlPresencial }}">
                                        {{ trans("$theme-app.foot.auctions") }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ Routing::translateSeo('subastas-historicas') }}">
                                    {{ trans("$theme-app.foot.historico") }}
                                </a>
                            </li>
                        </ul>
                    </li>

                    @if ($hasPermanent)
                        <li>
                            <a href="{{ route('allCategories', ['typeSub' => 'P']) }}">
                                {{ trans("$theme-app.foot.online_auction") }}
                            </a>
                        </li>
                    @elseif ($hasOnline)
                        <li>
                            <a href="{{ Routing::translateSeo('subastas-online') }}">
                                {{ trans("$theme-app.foot.online_auction") }}
                            </a>
                        </li>
                    @endif

                    @if ($hasJewelryAuction || $hasVentaDirecta)
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                                aria-haspopup="true" aria-expanded="false"><span class="caret"></span>
                                {{ trans("$theme-app.foot.direct_sale") }}
                            </a>

                            <ul class="dropdown-menu">
                                @if ($hasJewelryAuction)
                                    <li>
                                        <a href="{{ $urlJewelryAuction }}">
                                            {{ trans("$theme-app.subastas.jewelry") }}
                                        </a>
                                    </li>
                                @endif
                                @if ($hasVentaDirecta)
                                    <li>
                                        <a href="{{ $urlVentaDirecta }}">
                                            {{ trans("$theme-app.foot.direct_sale") }}
                                        </a>
                                    </li>
                                @endif
                            </ul>

                        </li>
                    @endif

                    <li>
                        <a href="{{ Routing::translateSeo('valoracion-articulos') }}">
                            {{ trans("$theme-app.home.free-valuations") }}
                        </a>
                    </li>
                </ul>

                <form class="navbar-form" action="{{ Routing::slug('busqueda') }}">
                    <div class="form-group">
						<i class="fa fa-search"></i>
                        <input class="form-control" name="texto" type="text" placeholder="Buscar en subarna">
                    </div>
                </form>
            </div><!-- /.navbar-collapse -->
        </div>
    </nav>

</header>

<div class="hidden-lg" id="menuResponsive">
    <div class="me">
        <a id="btnResponsiveClose" href="javascript:;" title="Cerrar">
            <img src="/themes/{{ $theme }}/assets/img/shape.png" alt="Cerrar">
        </a>
    </div>
    <div class="clearfix"></div>
    <ul class="nav navbar-nav navbar-right navbar-responsive">

        <li class="">
            <ul class="items_top_responsive hidden-md">
                @if (!Session::has('user'))
                    <li><a class="login" href="javascript:;" title="Login"><i class="fa fa-2x fa-user-circle"
                                style="margin-right: 5px;"></i>{{ trans($theme . '-app.login_register.login') }}</a>
                    </li>
                @else
                    <li><a href="{{ \Routing::slug('user/panel/orders') }}"><i
                                class="fa fa-2x fa-user-circle fa-lg mr-1"
                                style="margin-right: 5px;"></i>{{ trans($theme . '-app.login_register.perfil') }}</a>
                    </li>
                    @if (Session::get('user.admin'))
                        <li><a href="/admin" target = "_blank">
                                {{ trans($theme . '-app.login_register.admin') }}</a>
                        </li>
                    @endif
                @endif
            </ul>

        </li>
        {{-- <li><a title="{{ trans($theme.'-app.home.home')}}" href="/">{{ trans($theme.'-app.home.home')}}</a></li> --}}
        <li><a href="<?php echo Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us'); ?>"
                title="{{ trans($theme . '-app.foot.about_us') }}">{{ trans($theme . '-app.foot.about_us') }}</a>
        </li>
        <li><a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.how_to_buy') }}"
                title="{{ trans($theme . '-app.home.how_to_buy') }}">{{ trans($theme . '-app.home.how_to_buy') }}</a>
        </li>
        <li><a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.how_to_sell') }}"
                title="{{ trans($theme . '-app.foot.how_to_sell') }}">{{ trans($theme . '-app.foot.how_to_sell') }}</a>
        </li>

        @if ($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
            <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme . '-app.foot.auctions') }}</a>
            </li>
        @endif

        @if ($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
            <li>
                <a
                    href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans($theme . '-app.foot.direct_sale') }}</a>
            </li>
        @endif

        @if ($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
            <li><a
                    href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans($theme . '-app.foot.online_auction') }}</a>
            </li>
        @endif

        @if ($global['subastas']->has('S') && $global['subastas']['S']->has('P'))
            <li><a
                    href="{{ route('allCategories', ['typeSub' => 'P']) }}">{{ trans($theme . '-app.foot.online_auction') }}</a>
            </li>
        @endif

        @if ($global['subastas']->has('H'))
            <li><a
                    href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme . '-app.foot.historico') }}</a>
            </li>
        @endif

        <li><a href="<?= \Routing::translateSeo(trans($theme . '-app.links.contact')) ?>"
                title="{{ trans($theme . '-app.foot.contact') }}">{{ trans($theme . '-app.foot.contact') }}</a></li>

        <li><a
                href="{{ Routing::translateSeo('valoracion-articulos') }}">{{ trans($theme . '-app.home.free-valuations') }}</a>
        </li>
        <li><a href="<?= \Routing::translateSeo('departamentos') ?>">{{ trans($theme . '-app.foot.departments') }}</a>
        </li>
        <li><a href="{{ \Routing::translateSeo('blog') }} ">{{ trans($theme . '-app.blog.blogTitle') }}</a></li>
        <div class="len">
            <div class="search-item flag-header d-flex align-items-center" style="padding: 0;">

                @foreach (Config::get('app.locales') as $key => $value)
                    <div>
                        <a href="/{{ $key }}" title="{{ trans($theme . '-app.head.language_' . $key) }}">
                            <p style="text-transform: uppercase">{{ $key }}</p>
                        </a>
                    </div>
                    <div>
                        <span>|</span>
                    </div>
                @endforeach

                @foreach ($google_langs as $value)
                    <div>
                        <a href="/?#googtrans(es|{{ $value }})"
                            title="{{ trans($theme . '-app.head.language_' . $value) }}">
                            <p style="text-transform: uppercase" translate="no">{{ $value }}</p>
                        </a>
                    </div>

                    @if (!$loop->last)
                        <div>
                            <span>|</span>
                        </div>
                    @endif
                @endforeach


            </div>

        </div>
    </ul>
</div>
