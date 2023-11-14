<header>
        <div class="navbar-top">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="navbar-top-wrapper">
                        <ul>
                            @if(!Session::has('user'))
                                <li class="hidden-xs hidden-sm"><a class="btn-color" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a></li>
                                <li class="hidden-xs hidden-sm"><a class="btn_login_desktop btn-color" title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>" href="javascript:;"><?= trans(\Config::get('app.theme').'-app.login_register.login') ?></a></li>
                                <li class="hidden-lg hidden-md sesion-responsive"><a title="Login" class="login" href="javascript:;"><i class="fa fa-user fa-2x fa-lg"></i></a></li>
                            @else
                                <p style="margin-top:7.5px;margin-right:7.5px">{{Session::get('user.name')}}</p>
                                <li class="hidden-xs hidden-sm"><a class="btn-color" href="{{ \Routing::slug('user/panel/orders') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</a></li>
                                <li class="hidden-lg hidden-md sesion-responsive"><a href="{{ \Routing::slug('user/panel/orders') }}" ><i class="fa fa-2x fa-user fa-lg"></i></a></li>

                            @if(Session::get('user.admin'))
                                <li class="hidden-xs hidden-sm"><a class="btn-color" href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a> /</li>
                                <li class="hidden-lg hidden-md sesion-responsive"><a href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a></li>
                            @endif
                            <li class="hidden-xs hidden-sm"><a class="btn-color" href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a></p></li>
                            @endif

                            <div class="login_desktop" style="display:none;">
                                <div class="login_desktop_title">
                                    <?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
                                </div>
                                <img class="closedd" src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png" alt="Close">
                                <form data-toggle="validator" id="accerder-user-form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                    <label for="usuario">{{ trans(\Config::get('app.theme').'-app.login_register.user') }}</label>
                                        <input id="usuario" class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.user') }}" type="email" name="email" type="text" autocomplete="username">
                                    </div>
                                    <div class="form-group">
                                        <label for="contraseña">{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}</label>
                                        <input id="contraseña" class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}" type="password" name="password" autocomplete="current-password" maxlength="20">
                                    </div>
                                    <p>
                                        <a
                                        onclick="cerrarLogin();"
                                        class="c_bordered"
                                        data-ref="{{ \Routing::slug('password_recovery') }}"
                                        id="p_recovery"
                                        data-title="{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}"
                                        href="javascript:;"
                                        data-toggle="modal"
                                        data-target="#modalAjax"
                                        >
                                                {{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}
                                        </a>
                                    </p>
                                    <h5 class="message-error-log text-danger"></h5>
                                    <button id="accerder-user" class="btn btn-login-desktop btn-color" type="button"><span>{{ trans(\Config::get('app.theme').'-app.login_register.acceder') }}</span><div style="display: none;" class="loader mini"></div></button>
                                    @if(!empty(\Config::get('app.coregistroSubalia')) && \Config::get('app.coregistroSubalia'))
                                    <br>
                                    <p style="margin-top:1rem;"><a class="subalia-button" href="/{{\Config::get('app.locale')}}/login/subalia">{{ trans(\Config::get('app.theme').'-app.login_register.register_subalia') }} {{ trans(\Config::get('app.theme').'-app.login_register.here') }}</a></p>
                                    <br>
                                    @endif
                                </form>
                            </div>

                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="header-content">
                    <div class="logo">
                        <?php
                            $lang = Config::get('app.locale');
                        ?>
                        <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}"></a>
                    </div>
                    <div class="menu-access">
                        <div class="languaje">
                            @if (\Config::get( 'app.enable_language_selector' ))
                            <select
                                id="selectorIdioma"
                                actuallang="/{{ \App::getLocale() }}/"
                                name="idioma"
                                class="form-control"
                                style="width:100px; height:27px; font-size:11px;"
                            >
                                <option value="es"><?= trans(\Config::get('app.theme').'-app.head.language_es') ?></option>
                                <option value="en"><?= trans(\Config::get('app.theme').'-app.head.language_en') ?></option>
                            </select>
                            @elseif(\Config::get( 'app.google_translate' ))
                                <div class="google_translate1">
                                    <div id="google_translate_element"></div>
                                </div>
                                <script type="text/javascript">
                                    function googleTranslateElementInit() {
                                        new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'en,es,ca,de,fr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                                    }
                                </script>
                                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                            @endif
                        </div>


                            <div class="search-component hidden-xs">
                                <form role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form">
                                    <div class="form-group">
                                        <input class="form-control input-custom" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" />
                                    </div>
                                    <button type="submit" class="btn btn-custom-search"><i class="fa fa-search"></i><div style="display: none;top:0;" class="loader mini"></div></button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </header>
    <nav class="navbar navbar-default">
            <div class="container">
                <div class="col-xs-12">
                    <div class="navbar-header visible-md visible-sm visible-xs">
                        <ul class="nav-responsive-wrapper">
                            <button id="btnResponsive" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <div class="search-component hidden-lg hidden-sm hidden-md">
                                <form role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form">
                                    <div class="form-group">
                                        <input class="form-control input-custom" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" />
                                    </div>
                                    <button type="submit" class="btn btn-custom-search"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </ul>

                    </div>
                </div>


                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav hidden-xs hidden-sm hidden-md">

              <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>

                @if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
                  <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
                @endif

                @if($global['subastas']->has('H'))
                    <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
                @endif

				@if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
                    <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
                @endif

                @if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
                    <li><a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
                @endif

	      <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact')?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a></li>
	    </ul>
        	  </div>
        </div>
    </nav>
    <div id="menuResponsive" class="hidden-lg">
        <div class="me">
            <a id="btnResponsiveClose" title="Cerrar" href="javascript:;">
                <img src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png" alt="Cerrar">
            </a>
        </div>
	<div class="clearfix"></div>
	<ul class="nav navbar-nav navbar-right navbar-responsive">
            <li class="li-color">
            <a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a>
        </li>

        @if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
            <li><a class="li-color" href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
        @endif

		@if($global['subastas']->has('H'))
			<li class="li-color"><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
		@endif

		@if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
            <li class="li-color"><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
        @endif

		@if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
            <li class="li-color"><a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
        @endif
        	      <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact')?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a></li>

    </ul>
</div>
