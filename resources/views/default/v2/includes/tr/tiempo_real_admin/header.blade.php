<header class="enc">
    <nav>
        <div class="nav-content">
            <div class="logo hidden-xs hidden-sm">
                <a class="brand" href="{{ \Routing::slug('/') }}"><img class="img-responsive" src="{{ $img_url }}/logo.png" ></a>
            </div>
            <div class="subasta">
                <h3 class="auction_number">{{ $data['name'] }}</h3>
            </div>
            <div class="menu">
                @if(!Route::current()->parameter('proyector'))
                <ul>
                    @if(!Session::has('user'))
                    <li>
                        <button class="btn btn-primary btn-xs open_own_box" data-ref="login">{{ trans($theme.'-app.login_register.generic_name') }}</button>
                    </li>
                    <li>	
                        <a href="{{ \Routing::slug('login') }}" class="btn btn-primary btn-xs btn-register">{{ trans($theme.'-app.login_register.register') }}</a>
                    </li>
                    @else
                    <li class="group user_session">
                        <a href="{{ \Routing::slug('logout') }}/tr" class="btn btn-danger btn-xs" >{{ trans($theme.'-app.login_register.logout') }}</a>
                    </li>
                    <li class="group user_session hidden-xs">
                        <a onclick="toggleFullScreen()" class="btn btn-primary  btn-xs" >{{ trans($theme.'-app.sheet_tr.full_screen') }}</a>
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
                @endif
                <div class="close-menu hidden-lg hidden-md hidden-sm">
                    <i class="fa fa-close"></i>
                </div>
            </div>
            <div class="menu-responsive hidden-sm hidden-lg hidden-md">
                <div class="icon-responsive-live">
                    <i class="fa fa-bars"></i>
                </div>
            </div>
        </div>
        </div>

        <div class="language">

            <select 
                id="selectorIdioma" 
                actuallang="/{{ \App::getLocale() }}/" 
                name="idioma" 
                class="form-control" 
                style="width: 100px;height: 27px;font-size: 12px;padding: 0;background: rgba(255,255,255,.4);color: black;border: 0;"
                >
                <option value="es"><?= trans($theme . '-app.head.language_es') ?></option>
                <option value="en"><?= trans($theme . '-app.head.language_en') ?></option>
            </select>     
        </div>
    </nav>
</header>