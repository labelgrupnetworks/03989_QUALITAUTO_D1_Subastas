<?php

use App\libs\TradLib as TradLib;

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



               <?php /* GOOGLE TRANSLATE DESCONECTADO
               <div class="google_translate2">
                        <style>
                            div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span:nth-of-type(1),
                            div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value span:nth-of-type(2)
                            {display:none;}
                            div#google_translate_element div.goog-te-gadget-simple {margin:0px; padding:0px; display:inline-block; background-color:#000000; border:1px solid #000;}
                            div#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value::after{
                                content: '<?= trans($theme.'-app.head.more_lang') ?>';
                                padding-right:5px;
                                color: #707070;
                                text-decoration: none;
                                font-family: 'Rubik', sans-serif;
                                font-size: 14px;
                                text-transform: uppercase;
                                }

                        </style>
                        <div id="google_translate_element"></div>
                    </div>
                    <script type="text/javascript">

                    //guardamos idoma actual
                    var actualLang = '<?= $lang ?>'
                    $(window).bind("load", () => {

                        //Verificamos si han cambiado el idioma
                        //nativo de label
                        verifyLang(actualLang)
                    })

                    function googleTranslateElementInit() {
                        const goo =  new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'en,de,fr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                    }



                    </script>
                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                   */ ?>


            </div>
        </div>
    </div>
</div>
@endif
<header>
    <nav class="menu-header">
        <div class="menu-responsive hidden-lg">
            <div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter ">{{ trans($theme.'-app.head.menu') }}</div>
        </div>
        <div class="logo-header">
            <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
                <img width="195" height="84" class="logo-company" src="/themes/{{$theme}}/assets/img/logo.png" alt="{{(\Config::get( 'app.name' ))}}">
            </a>
        </div>
        <div class="menu-principal">

            <ul class="menu-principal-content d-flex  align-items-center">
                <span role="button" class="close-menu-reponsive hidden-lg">{{ trans($theme.'-app.head.close') }}</span>


					{{-- menu inicio
					<li class="flex-display">
                        <a class="color-letter flex-display link-header justify-center align-items-center" title="{{ trans($theme.'-app.home.home')}}" href="/{{$lang}}">
                            <span>{{ trans($theme.'-app.home.home')}}</span>
                        </a>
                    </li>
					--}}


					<li>
						<a class="color-letter d-flex link-header justify-content-center align-items-center category-button"  href="#"><span>{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</span></a>
						<div class="submenuDefault ">
							<div class="  pt-2 pb-2" >
								@php
									$fgortsec0 = new App\Models\V5\FgOrtsec0();
									$categories = $fgortsec0->GetAllFgOrtsec0()->get()->toarray();
								@endphp
								@foreach ($categories as $k => $category)
										<div class="categoryOption"><a href="{{ route("category",array( "keycategory" => $category["key_ortsec0"])) }}">{{$category["des_ortsec0"]}}</a></div>

								@endforeach
							</div>
					  </div>

					</li>
                @if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
                  <li>
                      <a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::translateSeo('presenciales') }}">
                        <span>{{ trans($theme.'-app.foot.auctions')}}</span>
                    </a>
                    </li>
                @endif
                @if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
                    <li>
                        <a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-online') }}"><span>{{ trans($theme.'-app.foot.online_auction')}}</span></a>
                    </li>
                @endif
                @if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
                    <li><a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('venta-directa') }}"><span>{{ trans($theme.'-app.foot.direct_sale')}}</span></a></li>
                @endif
                @if($global['subastas']->has('H'))
                    <li>
                        <a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-historicas') }}"><span>{{ trans($theme.'-app.foot.historico')}}</span>
                        </a>
                    </li>
                @endif
               <?php /*
                *    <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans($theme.'-app.foot.auctions')}}</a></li>

                * */
                  ?>
	      <li>
                    <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('calendar') }}"><span>{{ trans($theme.'-app.foot.calendar')}}</span></a>
                </li>
                 <li>
                    <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('valoracion-articulos', null) }}"><span> {{ trans($theme.'-app.home.free-valuations') }}</span></a>
                </li>
                <li>
                    <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans($theme.'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans($theme.'-app.links.contact')) ?>"><span>{{ trans($theme.'-app.foot.contact')}}</span></a>
                </li>
            </ul>
        </div>
        <div class="search-header-container d-flex justify-content-center align-items-center hidden-xs" role="button" aria-label="Search">
                <div class="search-header d-flex justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.17 29.861">
                    <defs>
                      <style>
                        .cls-1 {
                          fill: #46494f;
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
                                  fill: #46494f;
                                }
                              </style>
                            </defs>
                            <g id="cancel" transform="translate(0 -0.435)">
                              <path id="Path_27" data-name="Path 27" class="close-svg" d="M18.993,17.284,33.238,3.039a1.481,1.481,0,0,0,0-2.144,1.481,1.481,0,0,0-2.144,0L16.849,15.139,2.6.894a1.481,1.481,0,0,0-2.144,0,1.481,1.481,0,0,0,0,2.144L14.7,17.284.459,31.528a1.481,1.481,0,0,0,0,2.144,1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306L16.848,19.428,31.093,33.673a1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306a1.481,1.481,0,0,0,0-2.144Z" transform="translate(0 0)"/>
                            </g>
                          </svg>
                        </div>

        </div>
        <div class="user-account">
                @if(!Session::has('user'))
                    <div class="user-account-login">
                        <a class="flex-display justify-center align-items-center btn_login_desktop btn_login" title="<?= trans($theme.'-app.login_register.login') ?>" href="javascript:;">
                                <?= trans($theme.'-app.login_register.login') ?>
                        </a>
                    </div>
                    @else
                    <div class="my-account color-letter-header">
                        <div class="row">
                            <div class="col-xs-3 text-center">
								{{-- le he quitado la clase logo company por que no veo que tenga que tenerla y fallaba cuando modificaban esa clase,class="logo-company" --}}
                                <img width="25" src="/themes/{{$theme}}/assets/img/user.png"  alt="{{(\Config::get( 'app.name' ))}}">
                            </div>
                            <div class="col-xs-9 text-center">
                        @if(!empty($name))
                                <div class="hidden-xs" style='font-size: 11px'><b><?= $name ?></b></div>
                        @endif
                            <span class="hidden-xs">{{ trans($theme.'-app.login_register.my_panel') }}</span>
                            </div>
                        </div>

                    <div class="mega-menu background-body rigth-0">
                        <a class="color-letter" href="{{ \Routing::slug('user/panel/orders') }}" >
                            {{ trans($theme.'-app.login_register.my_panel') }}

                        </a>
                        @if(Session::get('user.admin'))
                            <a class="color-letter" href="/admin"  target = "_blank"> {{ trans($theme.'-app.login_register.admin') }}</a>
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

<div class="login_desktop" style="display: none" >
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
                        <i class="fa fa-user"></i>
                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email" type="text">
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
                <a class="" title="{{ trans($theme.'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans($theme.'-app.login_register.register') }}</a>
                @else
                <p class="text-center" style="color: darkred;">{{ trans($theme.'-app.login_register.registration_disabled') }}</p>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>
