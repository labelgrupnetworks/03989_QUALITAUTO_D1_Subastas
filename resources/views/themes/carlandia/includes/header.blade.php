<?php
use App\libs\TradLib as TradLib;

	$isCedente = false;
	if(Session::has('user')) {
		$isCedente = \App\Models\V5\FxCli::isCedente(Session::get('user.cod'));
	}

    $lang = Config::get('app.locale');

    $registration_disabled = Config::get('app.registration_disabled');
    $fullname = Session::get('user.name');
    if(strpos($fullname, ',')){
        $str = explode(",", $fullname);
        $name = $str[1];
    }else{
        $name = $fullname;
	}
?>
@if(count(Config::get('app.locales')) > 1)
<div class="lang-selection">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 text-right d-flex justify-content-flex-end">

				@foreach(Config::get('app.locales') as $key => $value)
					<ul class="ul-format list-lang d-inline-flex">
						<?php
							if(\App::getLocale() != $key){
								#Obtener la ruta en el idioma contrario segun las tablas seo y/o traducciones links
								$ruta ="/$key". TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), \App::getLocale(), $key);
							}else{
								$ruta ="";
							}
						?>
						<li>
							<a translate="no" title="<?= trans($theme.'-app.head.language_es') ?>" class="link-lang  color-letter {{ empty($ruta)? 'active': '' }} " {{ empty($ruta)? "": "href=$ruta" }} >

							<span translate="no">{{ trans($theme.'-app.home.' . $key)}}</span>
							</a>
						</li>
					</ul>
				@endforeach

            </div>
        </div>
    </div>
</div>
@endif

<header class="container">
    <nav class="menu-header">
        <div class="menu-responsive hidden-lg">
            <div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter "><i class="fa fa-2x fa-bars" aria-hidden="true"></i></div>
        </div>
			<div class="logo-header col-md-3">
				<a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
					<img class="logo-company img-responsive" src="/themes/{{$theme}}/assets/img/logo.png?a=1"  alt="{{(\Config::get( 'app.name' ))}}">
				</a>
				{{-- <span class="subtitle-logo parpadea color-letter">{{ trans("$theme-app.home.subtitle") }}</span> --}}

			</div>

        <div class="menu-principal">

            <ul class="menu-principal-content d-flex  align-items-center">
                <span role="button" class="close-menu-reponsive hidden-lg">{{ trans($theme.'-app.head.close') }}</span>

					<li class="flex-display">
                        <a class="color-letter flex-display link-header justify-center align-items-center pr-2 pl-2 @if(request()->path() == mb_substr(Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy'), 1)) {{'active'}} @endif"
						title="{{ trans($theme.'-app.foot.how_to_buy')}}"
						href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy') }}">
                            <span>{{ trans($theme.'-app.foot.how_to_buy') }}</span>
                        </a>
                    </li>

					<li class="flex-display hidden-lg">
                        <a class="color-letter flex-display link-header justify-center align-items-center pr-2 pl-2"
						href="{{ route('allCategories') }}">
                            <span>{{ trans($theme.'-app.home.buscar') }}</span>
                        </a>
                    </li>

					<li class="flex-display">
                        <a class="color-letter flex-display link-header justify-center align-items-center pr-2 pl-2 @if(request()->path() == mb_substr(Routing::translateSeo('pagina').trans($theme.'-app.links.professionals'), 1)) {{'active'}} @endif"
						title="{{ trans($theme.'-app.foot.professionals')}}"
						href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.professionals') }}">
                            <span>{{ trans($theme.'-app.foot.professionals') }}</span>
                        </a>
                    </li>

                <li>
                    <a class="color-letter d-flex link-header justify-content-center align-items-center pr-2 pl-2 @if(request()->path() == mb_substr(Routing::translateSeo(trans($theme.'-app.links.contact'), ''), 1)) {{'active'}} @endif"
					title="{{ trans($theme.'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans($theme.'-app.links.contact')) ?>"><span>{{ trans($theme.'-app.foot.contact')}}</span></a>
                </li>
            </ul>
        </div>

		<div class="call-icons">
			<a href="tel:900670239" class="phone-icon">
				<i class="fa fa-phone" aria-hidden="true"></i>
			</a>
			<a href="https://api.whatsapp.com/send?phone=900670239&text=" target="_blank" class="whatsapp-icon">
				<i class="fa fa-whatsapp" aria-hidden="true"></i>
			</a>
		</div>
		{{--
        <div class="search-header-container d-flex justify-content-center align-items-center hidden-xs @if(Session::has('user')) init-session @endif" role="button">
                <div class="search-header d-flex justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.17 29.861">
                    <defs>
                      <style>
                        .cls-1 {
                          fill: #fff;
                        }
                      </style>
                    </defs>
                    <g id="magnifying-glass" transform="translate(-7.254)">
                      <path id="Path_1" data-name="Path 1" class="cls-1" d="M36.055,27.715l-6.7-6.7a12.612,12.612,0,1,0-9.441,4.3,12.545,12.545,0,0,0,7.6-2.594l6.765,6.767a1.258,1.258,0,0,0,1.779-1.778ZM9.769,12.661A10.147,10.147,0,1,1,19.916,22.805,10.16,10.16,0,0,1,9.769,12.661Z"/>
                    </g>
                  </svg>
                </div>
                <div class="search-header-close d-flex justify-content-center align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33.697 33.544">
                            <defs>
                              <style>
                                .close-svg {
                                  fill: #fff;
                                }
                              </style>
                            </defs>
                            <g id="cancel" transform="translate(0 -0.435)">
                              <path id="Path_27" data-name="Path 27" class="close-svg" d="M18.993,17.284,33.238,3.039a1.481,1.481,0,0,0,0-2.144,1.481,1.481,0,0,0-2.144,0L16.849,15.139,2.6.894a1.481,1.481,0,0,0-2.144,0,1.481,1.481,0,0,0,0,2.144L14.7,17.284.459,31.528a1.481,1.481,0,0,0,0,2.144,1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306L16.848,19.428,31.093,33.673a1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306a1.481,1.481,0,0,0,0-2.144Z" transform="translate(0 0)"/>
                            </g>
                          </svg>
                        </div>

        </div> --}}
        <div class="user-account @if(Session::has('user')) init-session @endif">
                @if(!Session::has('user'))
                    <div class="user-account-login">
                        <a class="d-flex justify-content-end align-items-center btn_login_desktop btn_login" title="<?= trans($theme.'-app.login_register.login') ?>" href="javascript:;">
							<div class="text-center logo-user-header hidden-lg">
                                <img width="25px;" class="logo-company" src="/themes/{{$theme}}/assets/img/user.png"  alt="{{(\Config::get( 'app.name' ))}}">
                            </div>
							<span class="visible-lg">{{ trans($theme.'-app.login_register.login') }}</span>
                        </a>
                    </div>
                    @else
                    <div class="my-account color-letter-header">
                        <div class="row h-100 d-flex align-items-center">
                            <div class="col-xs-3 text-center logo-user-header visible-xs visible-sm">
                                <img width="25px;" class="logo-company" src="/themes/{{$theme}}/assets/img/user.png"  alt="{{(\Config::get( 'app.name' ))}}">
                            </div>
                            <div class="col-xs-12 hidden-xs hidden-sm {{-- text-right --}} color-letter">
                        @if(!empty($name))
                                <div class="name-login max-line-2">{{ $name }}</div>
                        @endif
                            <span class="my-panel">{{ trans($theme.'-app.login_register.my_panel') }}</span>
                            </div>
                        </div>

                    <div class="mega-menu background-body rigth-0">

						@if($isCedente)
						<a class="color-letter" href="{{ \Routing::slug('user/panel/info') }}" >
                            Mi Perfil
                        </a>
						@else
						<a class="color-letter" href="{{ \Routing::slug('user/panel/orders') }}" >
                            {{ trans($theme.'-app.login_register.my_panel') }}
                        </a>
						@endif


                        @if(Session::get('user.admin'))
							<a class="color-letter" href="{{ route('panel.active-sales', ['lang' => config('app.locale')]) }}">Ventas</a>
                            <a class="color-letter" href="/admin"  target = "_blank"> {{ trans($theme.'-app.login_register.admin') }}</a>
						@elseif($isCedente)
							<a class="color-letter" href="{{ route('panel.active-sales', ['lang' => config('app.locale')]) }}">Mi Panel</a>
                        @endif
                        <a class="color-letter" href="{{ \Routing::slug('logout') }}" >{{ trans($theme.'-app.login_register.logout') }}</a>
                    </div>
                    </div>
            @endif
        </div>
    </nav>
</header>


@if (!empty(\Config::get("app.gridLots")) && \Config::get("app.gridLots") =="new" )
<div class="menu-principal-search d-flex align-items-center justify-content-center">
		<form id="formsearchResponsive" role="search" action="{{ route('allCategories') }}" class="search-component-form flex-inline position-relative">
			<div class="form-group">
				<input class="form-control input-custom br-100" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="description" />
			</div>
			<button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans($theme.'-app.head.search_button') }}</button>
		</form>
</div>
@else
<div class="menu-principal-search d-flex align-items-center justify-content-center">
	<form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form flex-inline position-relative">
		<div class="form-group">
			<input class="form-control input-custom br-100" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="texto" />
		</div>
		<button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans($theme.'-app.head.search_button') }}</button>
	</form>
</div>
@endif

<div class="login_desktop" style="display: none">
    <div class="login_desktop_content">
        <div class="only-login white-background">
            <div class="login-content-form">
            <img class="closedd" role="button" src="/themes/{{$theme}}/assets/img/shape.png" alt="Close">
            <div class="login_desktop_title">
                <?= trans($theme.'-app.login_register.login') ?>
            </div>
            <form data-toggle="validator" id="accerder-user-form" class="flex-display justify-center align-items-center flex-column">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="input-login-group">
                        <i class="fa  fa-envelope"></i>
                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.email') }}" type="email" name="email" type="text">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="input-login-group">
                        <i class="fa fa-key"></i>
                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20" autocomplete="off">
                        <img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                    </div>
                </div>
                <span class="message-error-log text-danger seo_h5"></span></p>
                <div class="pass-login-content">
                    <div class="text-center">
                    <button id="accerder-user" class="button-principal" type="button">
                        <div>{{ trans($theme.'-app.login_register.acceder') }}</div>
                    </button>
                    </div>
                    <a onclick="cerrarLogin();" class="c_bordered pass_recovery_login" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a>

                </div>
            </form>
            <div class="login-separator"></div>
            <p class="text-center">{{ trans($theme.'-app.login_register.not_account') }}</p>
            <div class="create-account-link">
                @if(empty($registration_disabled))
                <a class="js-register" title="{{ trans($theme.'-app.login_register.register') }}" href="{{ \Routing::slug('login-landing') }}">{{ trans($theme.'-app.login_register.register') }}</a>
                @else
                <p class="text-center" style="color: darkred;">{{ trans($theme.'-app.login_register.registration_disabled') }}</p>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>

<div class="ficha_login_desktop" style="display: none">
    <div class="login_desktop_content">
        <div class="only-login">
            <div class="login-content-form">
            <img class="closedd_fichalogin" role="button" src="/themes/{{$theme}}/assets/img/shape.png" alt="Close">

			<div class="login_desktop_title">
				<p>Para enviar tu ofeta al concesionario, danos tus datos, pulsando</p>
				<div>
					@if(empty($registration_disabled))
						<a class="js-register button-principal" title="{{ trans($theme.'-app.login_register.register') }}"
							href="{{ \Routing::slug('register') }}">
							AQUÍ
						</a>
					@else
					<p class="text-center" style="color: darkred;">{{ trans($theme.'-app.login_register.registration_disabled') }}</p>
					@endif
				</div>
            </div>

			<div class="login_desktop_title login-call mt-2">
				<span>¿Prefieres llamarnos?</span>
				<br>
				<span>
					<a href="tel:900670239" class="phone-icon">
						<i class="fa fa-phone fa-2x fa-flip-horizontal" aria-hidden="true"></i>
					</a>
				</span>
            </div>

            <form data-toggle="validator" id="accerder-user-ficha-form" class="flex-display justify-center align-items-center flex-column">
				<p class="bold text-center">Si ya tenemos tus datos, accede <br>(LOGIN)</p>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="input-login-group">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.email') }}" type="email" name="email" type="text">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="input-login-group">
                        <i class="fa fa-key"></i>
                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20" autocomplete="off">
                        <img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                    </div>
                </div>
                <span class="message-error-log text-danger seo_h5"></span></p>
                <div>
                    <div class="text-center mb-2">
						<button id="accerder-ficha-user" class="button-principal" type="button">
							<div>{{ trans($theme.'-app.login_register.acceder') }}</div>
						</button>
                    </div>
                    <a onclick="cerrarFichaLogin();" class="c_bordered mt-2 forgotten-password" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a>
                </div>
            </form>

        </div>
    </div>
    </div>
</div>

