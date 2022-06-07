<header>
<div class="container">
        <div class="row">
                <div class="col-xs-12 col-sm-3 logo">
                <?php
                    $lang = Config::get('app.locale');
                ?>
                        <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}"></a>
                </div>
                <div class="col-xs-12 col-sm-9 header-left">

                        <ul class="items_top wrapper visible-lg">
<li>
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
                                        <div id="google_translate_element">

                                        </div>

                                    </div>
                                    <script type="text/javascript">
                                        function googleTranslateElementInit() {
                                            new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'en,es,ca', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                                        }
                                    </script>
                                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                                    @endif

                                </li>


                                @if(!Session::has('user'))
                                    <li><a title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a></li>
                                    <li><a class="btn_login_desktop" title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>" href="javascript:;"><?= trans(\Config::get('app.theme').'-app.login_register.login') ?></a></li>
                                 @else
                                    <li><a href="{{ \Routing::slug('user/panel/orders') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</a> /</li>
                                    @if(Session::get('user.admin'))
                                        <li><a href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a> /</li>
                                    @endif
                                    <li><a href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a></p></li>
                                @endif

                                <li class="hidden-xs">
                                    <form id="formsearch" role="search" action="{{ \Routing::slug('busqueda') }}" class="navbar-form navbar-right">
                                        <div class="form-group">
                                          <input class="form-control input-custom" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto">
                                        </div>
                                        <button type="submit" class="btn btn-custom-search"><i class="fa fa-search"></i></button>
                                    </form>
                                </li>


                        </ul>


                        <div class="login_desktop" style="display:none;">
                                <div class="login_desktop_title">
                                        <?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
                                </div>
                                <img class="closedd" src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png" alt="Close">

                                 <form data-toggle="validator" id="accerder-user-form">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="form-group">
                                                <label for="usuario">{{ trans(\Config::get('app.theme').'-app.login_register.user') }}</label>
                                                <input class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.user') }}" type="email" name="email" type="text">
                                        </div>
                                        <div class="form-group">
                                                <label for="contraseña">{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}</label>
                                                <input class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20">
                                        </div>
                                        <p><a onclick="cerrarLogin();" class="c_bordered" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}</a></p>
                                        <h5 class="message-error-log text-danger"></h5></p>
                                        <button id="accerder-user" class="btn btn-login-desktop" type="button">{{ trans(\Config::get('app.theme').'-app.login_register.acceder') }}</button>
                                        @if(!empty(\Config::get('app.coregistroSubalia')) && \Config::get('app.coregistroSubalia'))
                                        <br>
                                        <p style="margin-top:1rem;"><a class="subalia-button" href="/{{\Config::get('app.locale')}}/login/subalia">{{ trans(\Config::get('app.theme').'-app.login_register.register_subalia') }} {{ trans(\Config::get('app.theme').'-app.login_register.here') }}</a></p>
                                        <br>
                                        @endif
                                </form>
                        </div>
                        <ul class="items_top_responsive hidden-lg">

                                 @if(!Session::has('user'))
                                    <li><a title="Login" class="login" href="javascript:;"><i class="fa fa-user fa-lg"></i></a></li>
                                 @else
                                    <li><a href="{{ \Routing::slug('user/panel/orders') }}" ><i class="fa fa-user fa-lg"></i></a></li>
                                    @if(Session::get('user.admin'))
                                        <li><a href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a> /</li>
                                    @endif
                                 @endif

                                <li>
                                @if (\Config::get( 'app.enable_language_selector' ))

                                    <select id="selectorIdioma" actuallang="/{{ \App::getLocale() }}/" name="idioma" class="form-control" style="width:100px; height:27px; font-size:11px;">
                                        <option value="es"><?= trans(\Config::get('app.theme').'-app.head.language_es') ?></option>
                                        <option value="en"><?= trans(\Config::get('app.theme').'-app.head.language_en') ?></option>
                                   </select>

                                @elseif(\Config::get( 'app.google_translate' ))
                                    <div class="google_translate2">

                                    </div>

                                    @endif
								</li>




                        </ul>
                        <form id="formsearch-responsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="hidden-lg">
                            <div class="form-group col-xs-12 col-sm-7 col-sm-offset-5 col-md-offset-5 col-lg-5" style="padding-right: 0;">
                                          <input class="form-control input-custom" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" id="textSearch">
                                <button type="submit" class="btn btn-custom-search" style="right:3px;"><i class="fa fa-search"></i></button>

                            </div>
                        </form>


                </div>
        </div>
</div>
    </header>
<nav class="navbar navbar-default">
	<div class="container">
	  <div class="navbar-header visible-md visible-sm visible-xs">
	    <button id="btnResponsive" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">

	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	    </button>
	  </div>
	  <div id="navbar" class="navbar-collapse collapse">
	    <ul class="nav navbar-nav hidden-xs hidden-sm hidden-md">
	    <?php //   <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li> ?>
              <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>
                <?php

                   $subastaObj        = new \App\Models\Subasta();
                   $has_subasta = $subastaObj->auctionList ('S', 'W');
                   if( empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                   }

				?>
				@if(!empty($has_subasta))
					<li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
			  	@endif
				<?php
					$has_subasta = $subastaObj->auctionList ('S', 'O');
					if(empty($has_subasta) && Session::get('user.admin')){
						$has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
					}
					?>
				@if(!empty($has_subasta))
					<li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
				@endif
                <?php
                    $has_subasta = $subastaObj->auctionList ('H');
				?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
                @endif
                <?php
                  $has_subasta = $subastaObj->auctionList ('S', 'O');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
                   }
                ?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
                @endif
                <?php
                  $has_subasta = $subastaObj->auctionList ('S', 'V');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'V'));
                   }
				?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
                @endif
               <?php /*
                *    <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>

                * */
                  ?>

		  <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact'))?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a></li>
		  <li><a href="{{ \Routing::translateSeo('blog')}} ">{{ trans(\Config::get('app.theme').'-app.blog.blogTitle') }}</a></li>
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
            <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>
            <?php

                   $subastaObj        = new \App\Models\Subasta();
                   $has_subasta = $subastaObj->auctionList ('S', 'W');
                   if( empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                   }

                ?>
                @if(!empty($has_subasta))
                  <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
                @endif
				<?php
					$has_subasta = $subastaObj->auctionList ('S', 'O');
					if(empty($has_subasta) && Session::get('user.admin')){
						$has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
					}
				?>
				@if(!empty($has_subasta))
					<li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
				@endif
				<?php
                    $has_subasta = $subastaObj->auctionList ('H');
				?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
                @endif
                <?php
                  $has_subasta = $subastaObj->auctionList ('S', 'V');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'V'));
                   }
                ?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
                @endif
				<li><a title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a></li>
				<li><a href="{{ \Routing::translateSeo('blog')}} ">{{ trans(\Config::get('app.theme').'-app.blog.blogTitle') }}</a></li>

	</ul>
</div>
<script>
                                var ventana_ancho = $(window).width();
                           if(ventana_ancho <= '1200'){
                               $(".google_translate2").html($(".google_translate1").html());
                               $(".google_translate1").html('');
                           }
</script>
