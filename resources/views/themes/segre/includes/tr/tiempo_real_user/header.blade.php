<link rel="stylesheet" href="{{ Tools::urlAssetsCache('/css/tiempo_real/tiempo_real.css') }}" />
<link rel="stylesheet" href="{{ Tools::urlAssetsCache('/themes/'. $theme.'/css/tiempo_real/tiempo_real.css') }}" />
<header>
    <nav class="nav_tr">
        <div class="language hidden-xs hidden-sm">
            <select class="selectIdioma" id="selectorIdioma" actuallang="/{{ \App::getLocale() }}/" name="idioma" class="form-control">
                <option value="es"><?= trans($theme . '-app.head.language_es') ?></option>
                <option value="en"><?= trans($theme . '-app.head.language_en') ?></option>
            </select>
        </div>
        <div class="header_content @if(!Session::has('user')) {{"header_nologin"}} @endif">

            <div class="header_logo hidden-xs hidden-sm">
                <img class="img-responsive" src="{{ $img_url }}/logo.png" >
            </div>
            <div class="subasta header_title">
                <h2 class="auction_number text-center">{{ $data['name'] }}</h2>
            </div>
            <div class="menu header_session">

                <ul>
                    @if(!Session::has('user'))
                    <li>
                        <button class="btn button open_own_box" data-ref="login">{{ trans($theme.'-app.login_register.generic_name') }}</button>
                    </li>
                    <li>
                        <a href="https://www.subastassegre.com/default/customer/account/create/" class="btn button btn-register">{{ trans($theme.'-app.login_register.register') }}</a>
                    </li>
                    @else
                    <li class="group user_session">
                        <a href="{{ \Routing::slug('logout') }}/tr" class="btn button btn-danger" >{{ trans($theme.'-app.login_register.logout') }}</a>
                    </li>
                    <li class="group user_session hidden-xs">
                        <a onclick="toggleFullScreen()" class="btn button" >{{ trans($theme.'-app.sheet_tr.full_screen') }}</a>
                    </li>
                    <li class="group user_verified">
                        <div>
                            <span class="img_verified">
                                <span>
                                    <i class="fa fa-check"></i>
                                </span>
                            </span>
                        </div>
                        <div class="u_data">
                            <div class="u_name">{{ Session::get('user.name') }}</div>
                            <div class="u_verified">{{ trans_choice($theme.'-app.sheet_tr.verified_bidders', 1) }} <span>{{ $data['js_item']['user']['cod_licit'] }}</span></div>
                        </div>
                    </li>
                    @endif
                </ul>

                <div class="close-menu hidden-lg">
                    <i class="fa fa-close"></i>
                </div>
            </div>
            <div class="menu-responsive hidden-lg">
                <div class="icon-responsive-live">
                    <i class="fa fa-bars"></i>
                </div>
            </div>
        </div>

    </nav>
</header>
