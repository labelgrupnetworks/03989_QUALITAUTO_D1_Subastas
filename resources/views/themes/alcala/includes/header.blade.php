<?php
    $lang = Config::get('app.locale');
    $registration_disabled = Config::get('app.registration_disabled');
?>


<header>
    <div class="container-fluid top-bar">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-6 text-right">


                    <div class="google_translate">
                            <div id="google_translate_element1"></div>
                        </div>

                        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

                        <script type="text/javascript">

                            //guardamos idoma actual
                            var actualLang = '<?= $lang ?>';
                            $(window).bind("load", () => {

                                //Verificamos si han cambiado el idioma
                                //nativo de label
                                verifyLang(actualLang);
                            })

                            function googleTranslateElementInit() {
                               new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'es,en,zh-TW', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element1');
                            }

                        </script>


            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
                <div class="menu-responsive hidden-lg hidden-md">
                        <div role="button" class=" color-letter ">{{ trans($theme.'-app.head.menu') }}</div>
                    </div>
            <div class="col-xs-12 text-center col-lg-2 col-md-2">
                <div class="logo-alcala">
                        <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
                            <img class="logo-company img-responsive" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
                        </a>
                </div>
            </div>
            <div class="formulario-buscar col-xs-12 col-md-10">
                    <div class=" text-right d-flex justify-content-flex-end">
                            <div class="search-component hidden-sm hidden-xs hidden-lg" >
                                <form role="search" action="/es/busqueda" class="search-component-form">
                                    <div class="form-group">
                                        <input class="form-control input-custom" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="texto">
                                    </div>
                                    <button type="submit" class="btn btn-custom-search-large btn-icon-cente">
										<x-icon.fontawesome icon=search version=5></x-icon.fontawesome>
										<div style="display: none;top:0;" class="loader mini"></div>
									</button>
                                </form>
                            </div>
            	</div>
        	</div>
            <div class="col-xs-10 col-lg-10 col-md-12">
                <div class="formulario-buscar col-xs-12 hidden-md ">
                        <div class=" text-right d-flex justify-content-flex-end">
                                <div class="search-component hidden-sm hidden-xs" >
                                    <form role="search" action="/es/busqueda" class="search-component-form">
                                        <div class="form-group">
                                            <input class="form-control input-custom" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="texto">
                                        </div>
                                        <button type="submit" class="btn btn-custom-search-large btn-icon-center">
											<x-icon.fontawesome icon=search version=5></x-icon.fontawesome>
											<div style="display: none;top:0;" class="loader mini"></div>
										</button>
                                    </form>
                                </div>
                </div>
            </div>
            <div class="menu-principal col-xs-12">
                    <ul class="menu-principal-content d-flex justify-content-space-between align-items-center">

                            <span role="button" class="close-menu-reponsive hidden-lg hidden-md">X</span>


							@if($global['auctionTypes']->where('tipo_sub', 'W')->value('count'))
                              	<li>
                                  	<a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::translateSeo('presenciales') }}">
                                    	{{ trans($theme.'-app.foot.auctions') }}
                                	</a>
                            	</li>
                            @endif

							<li>
								<a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::translateSeo('subastas-historicas') }}">
									{{ trans($theme.'-app.foot.historico')}}
								</a>
							</li>

                            <li>
                                <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans($theme.'-app.foot.how_to_buy') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy')  ?>">{{ trans($theme.'-app.foot.how_to_buy') }}</a>
                            </li>
                            <li>
                                <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans($theme.'-app.foot.how_to_sell') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_sell')  ?>">{{ trans($theme.'-app.foot.how_to_sell')}}</a>
                            </li>

                            <li>
                                <a class="color-letter d-flex link-header justify-content-center align-items-center" title="" href="{{ \Routing::translateSeo('tasaciones') }}">{{ trans($theme.'-app.home.free-valuations') }}</a>
                            </li>
                            <li>
                                <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans($theme.'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans($theme.'-app.links.contact')) ?>">{{ trans($theme.'-app.foot.contact')}}</a>
                            </li>

                            @if(!Session::has('user'))
                            <li>
                                    <div class="hidden-lg hidden-md"><br><br></div>
                                    <a class="color-letter d-flex link-header justify-content-center align-items-center btn_login_desktop btn_login" title="<?= trans($theme.'-app.login_register.login') ?>" onclick="javascript:;">
                                            <b><?= trans($theme.'-app.login_register.login') ?></b>
                                    </a>
                            </li>
                            @else

                            <li class="hidden-lg hidden-md">
                                <br><br><hr>

                                <a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::slug('user/panel/orders') }}" >
                                    <b>{{ trans($theme.'-app.login_register.my_panel') }}</b>
                                </a>
                                <hr>

                                @if(Session::get('user.admin'))
                                    <a class="color-letter d-flex link-header justify-content-center align-items-center" href="/admin"  target = "_blank">
                                        <b>{{ trans($theme.'-app.login_register.admin') }}</b>
                                    </a>
                                    <hr>
                                @endif
                                <a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::slug('logout') }}" >
                                    <b>{{ trans($theme.'-app.login_register.logout') }}</b>
                                </a>
                                <hr>
                            </li>
                            <li class="hidden-xs hidden-sm">
                                <a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::slug('user/panel/orders') }}" >
                                    <b>{{ trans($theme.'-app.login_register.my_panel') }}</b>
                                </a>
                            </li>

                            @if(Session::get('user.admin'))
                                <li class="hidden-xs hidden-sm">
                                    <a class="color-letter d-flex link-header justify-content-center align-items-center" href="/admin"  target = "_blank">
                                        <b>{{ trans($theme.'-app.login_register.admin') }}</b>
                                    </a>
                                </li>
                            @endif

                            <li class="hidden-xs hidden-sm">
                                <a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::slug('logout') }}" >
                                    <b>{{ trans($theme.'-app.login_register.logout') }}</b>
                                </a>
                            </li>
                        @endif



                        </ul>
            </div>
        </div>

    </div>
</div>
</header>


<div class="menu-principal-search hidden-lg hidden-md d-flex align-items-center justify-content-center">
        <form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form flex-inline position-relative">
            <div class="form-group">
                <input class="form-control input-custom br-100" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="texto" />
            </div>
            <button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans($theme.'-app.head.search_button') }}</button>
        </form>
</div>

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
						<div class="input-icon-wrapp">
							<x-icon.fontawesome icon=user version=5></x-icon.fontawesome>
						</div>
                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email" type="text">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="input-login-group">
						<div class="input-icon-wrapp">
							<x-icon.fontawesome icon=key version=6></x-icon.fontawesome>
						</div>

                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20">
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
                    <a onclick="cerrarLogin();" class="c_bordered pass_recovery_login" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" onclick="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a>

                </div>
            </form>
            <div class="login-separator"></div>
            <p class="text-center">{{ trans($theme.'-app.login_register.not_account') }}</p>
            <div class="create-account-link">
                @if(empty($registration_disabled))
                <a class="" title="{{ trans($theme.'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans($theme.'-app.login_register.register') }}</a>
                @else
                <p class="text-center">{!! trans($theme.'-app.login_register.registration_disabled') !!}</p>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>


<script>

</script>
