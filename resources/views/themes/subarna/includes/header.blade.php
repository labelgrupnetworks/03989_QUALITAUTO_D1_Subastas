<header>
    <div class="col-top">
        <div class="container">
            <div class="menu-nab-top">

                <div class="logo hidden-md hidden-lg">
                  <?php
                        $lang = Config::get('app.locale');
                    ?>
                    <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}"><img class="img-responsive" src="/themes/{{$theme}}/assets/img/logo.jpg"  alt="{{(\Config::get( 'app.name' ))}}"></a>
                </div>

                <div class="len hidden-xs hidden-sm hidden-md">
                    <div class="search-item flag-header d-flex align-items-center" style="padding: 0;">

                        @foreach(Config::get('app.locales') as $key => $value)
                            <div>
								<a title="{{ trans($theme.'-app.head.language_'.$key) }}" href="/{{ $key }}">
									<p style="text-transform: uppercase">{{ $key }}</p>
								</a>
							</div>
							<div>
								<span>|</span>
							</div>
						@endforeach
						<?php
							$google_langs = ['de', 'ca', 'fr']
						?>
						@foreach($google_langs as $value)
							<div>
								<a title="{{ trans($theme.'-app.head.language_'.$value) }}" href="/?#googtrans(es|{{ $value }})">
									<p translate="no" style="text-transform: uppercase">{{ $value }}</p>
								</a>
							</div>

							@if (!$loop->last)
								<div>
									<span>|</span>
								</div>
							@endif
						@endforeach


                    </div>
                    @if (\Config::get( 'app.enable_language_selector' ))
                        <select id="selectorIdioma" actuallang="/{{ \App::getLocale() }}/" name="idioma" class="form-control" style="width:100px; height:27px; font-size:11px;">
                            <option value="es"><?= trans($theme.'-app.head.language_es') ?></option>
                            <option value="en"><?= trans($theme.'-app.head.language_en') ?></option>
                        </select>
                    @elseif(\Config::get( 'app.google_translate' ))
                        <div class="google_translate1" style="display: none">
                            <div id="google_translate_element"></div>
                        </div>
                        <script type="text/javascript">
                            function googleTranslateElementInit() {
                                new google.translate.TranslateElement({pageLanguage: '{{$lang}}', includedLanguages: '{{$lang}},ca,de,fr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                            }
                        </script>
                        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                    @endif
                </div>
                <div class="logo hidden-xs hidden-sm">
                    <?php
                        $lang = Config::get('app.locale');
                    ?>
                    <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}"><img class="img-responsive" src="/themes/{{$theme}}/assets/img/logo.jpg"  alt="{{(\Config::get( 'app.name' ))}}"></a>
                </div>

                <div class="access d-flex flex-direction-column justify-content-center">

					<ul class=" hidden-xs hidden-sm">
						<li><a class="search_btn" title="{{ trans($theme.'-app.head.search_button') }}" href="{{ \Routing::slug('busqueda') }}">{{ trans($theme.'-app.head.search_button') }} <i class="fa fa-search"></i></a></li>
					</ul>
					{{-- buscador original
                    <div class="search" style="margin-bottom: 5px;">
                        <form id="formsearch" role="search" action="{{ \Routing::slug('busqueda') }}" class="navbar-form form-search-header">
                            <div class="form-group">
                                <input class="form-control input-search-custom" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="texto">
                                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
					</div>
					--}}

                        @if(!Session::has('user'))
                        <ul class=" hidden-xs hidden-sm">
                            <li><a class="btn_login_desktop" title="<?= trans($theme.'-app.login_register.login') ?>" href="javascript:;"><?= trans($theme.'-app.login_register.login') ?></a></li>
                            <li><a title="{{ trans($theme.'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans($theme.'-app.login_register.register') }}</a></li>
                        </ul>
                        @else
                        <span class="name_user_logged"></span>
                        <p class="cortar name_user hidden-xs hidden-sm">{{ trans_choice($theme.'-app.user_panel.hello',1,['name'=>mb_convert_case(Session::get('user.name'), MB_CASE_TITLE, "UTF-8")]) }}</p>
                        <ul class=" hidden-xs hidden-sm">

                            <li><a href="{{ \Routing::slug('user/panel/orders') }}" >{{ trans($theme.'-app.login_register.my_panel') }}</a> </li>
                            @if(Session::get('user.admin'))
                                <li><a href="/admin"  target = "_blank"> {{ trans($theme.'-app.login_register.admin') }}</a></li>
                            @endif
                            <li><p><a href="{{ \Routing::slug('logout') }}" >{{ trans($theme.'-app.login_register.logout') }}</a></p></li>
                        </ul>
                        @endif

                    <div class="google_translate2"></div>
                </div>
                <div class="login_desktop" style="display:none;">
                    <div class="login_desktop_title">
                            <?= trans($theme.'-app.login_register.login') ?>
                    </div>
                    <img class="closedd" src="/themes/{{$theme}}/assets/img/shape.png" alt="Close">

                    <form data-toggle="validator" id="accerder-user-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="usuario">{{ trans($theme.'-app.login_register.user') }}</label>
                            <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email" type="text">
                        </div>
                        <div class="form-group">
                            <label for="contraseña">{{ trans($theme.'-app.login_register.contraseña') }}</label>
                            <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20">
                        </div>
                        <p><a onclick="cerrarLogin();" class="c_bordered" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a></p>
                        <p><h5 class="message-error-log text-danger"></h5></p>
                        <button id="accerder-user" class="btn btn-login-desktop" type="button">{{ trans($theme.'-app.login_register.acceder') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

    <nav class="navbar navbar-default">
	<div class="container-fluid">
	  <div class="navbar-header visible-md visible-sm visible-xs">
	    <button id="btnResponsive" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	      <!--<span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>-->
                <span>{{ trans($theme.'-app.head.menu') }}</span>
	    </button>
	  </div>
	  <div id="navbar" class="navbar-collapse collapse">
	    <ul class="nav navbar-nav hidden-xs hidden-sm hidden-md">
                {{--<li><a title="{{ trans($theme.'-app.home.home')}}" href="/{{$lang}}">{{ trans($theme.'-app.home.home')}}</a></li>--}}
				<li><a title="{{ trans($theme.'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.about_us')  ?>">{{ trans($theme.'-app.foot.about_us') }}</a></li>

				<li>
					<a onclick="javascript:$('#menu_desp').toggle('blind',100)" style="cursor: pointer;">
						{{ trans($theme.'-app.foot.how_to_buy') }}
						&nbsp;
						<span class="caret"></span>
					</a>

					<div id="menu_desp">
						<a title="{{ trans($theme.'-app.home.how_to_buy') }}" href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy') }}">{{ trans($theme.'-app.home.how_to_buy') }}</a>
						<a title="{{ trans($theme.'-app.foot.how_to_sell') }}" href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_sell') }}">{{ trans($theme.'-app.foot.how_to_sell') }}</a>
					</div>
				</li>

			@php
				$existHistorica = $global['subastas']->has('H');
				$existPresencial = $global['subastas']->has('S') && $global['subastas']['S']->has('W');

				$urlPresencial="#";
				if($existPresencial && $global['subastas']['S']['W']->count() == 1){
					$subasta = $global['subastas']['S']['W']->flatten()->first();
					$urlPresencial = Routing::translateSeo('info-subasta').$subasta->cod_sub."-".str_slug($subasta->name);
				} elseif($existPresencial && $global['subastas']['S']['W']->count() > 1){
					$urlPresencial = Routing::translateSeo('presenciales');
				}
			@endphp

			@if($existHistorica || $existPresencial)
				<li>
					<a onclick="javascript:$('#menu_sub').toggle('blind',100)" style="cursor: pointer;">
						{{ trans($theme.'-app.foot.presenciales') }}
						&nbsp;
						<span class="caret"></span>
					</a>

					<div id="menu_sub">
						@if ($existPresencial)
							<a href="{{ $urlPresencial }}">{{ trans($theme.'-app.foot.auctions')}}</a>
						@endif
						@if ($existHistorica)
							<a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a>
						@endif
					</div>
				</li>
			@endif

                @if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
                    <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans($theme.'-app.foot.online_auction')}}</a></li>
				@endif

			  	@if($global['subastas']->has('S') && $global['subastas']['S']->has('P'))
				  	<li><a href="{{ route('allCategories', ['typeSub' => 'P']) }}">{{ trans($theme.'-app.foot.online_auction')}}</a></li>
			  	@endif
				@if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))

					@if($global['subastas']['S']['V']->has('VDJ'))
						@php
							$subasta = $global['subastas']['S']['V']['VDJ']->flatten()->first();
							$url_venta = \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions, $subasta->reference)."?only_salable=on";
							unset($global['subastas']['S']['V']['VDJ']);
						@endphp

						<li><a href="{{$url_venta}}">{{ trans($theme.'-app.subastas.jewelry')}}</a></li>
					@endif

					@if($global['subastas']['S']['V']->count() == 1)
						@php
							$subasta = $global['subastas']['S']['V']->flatten()->first();
							$url_venta = \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions, $subasta->reference)."?only_salable=on";
						@endphp
						<li><a href="{{$url_venta}}">{{ trans($theme.'-app.foot.direct_sale')}}</a></li>

					@elseif ($global['subastas']['S']['V']->count() > 1)
						<li><a href="{{route('subastas.venta_directa')}}">{{ trans($theme.'-app.foot.direct_sale')}}</a></li>
					@endif

                @endif

               <?php /*

                *    <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans($theme.'-app.foot.auctions')}}</a></li>

                * */
                  ?>
	      		<li><a title="{{ trans($theme.'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans($theme.'-app.links.contact')) ?>">{{ trans($theme.'-app.foot.contact')}}</a></li>
			  <li><a href="<?=\Routing::translateSeo('valoracion-articulos')?>">{{ trans($theme.'-app.home.free-valuations') }}</a></li>
			  {{-- <li><a href="{{ \Routing::translateSeo('departamentos') }}">{{ trans($theme.'-app.foot.departments') }}</a></li> --}}
			  <li><a href="{{ \Routing::translateSeo('blog')}} ">{{ trans($theme.'-app.blog.blogTitle') }}</a></li>

			</ul>



	</div>
</nav>
<div id="menuResponsive" class="hidden-lg">
	<div class="me">
	  <a id="btnResponsiveClose" title="Cerrar" href="javascript:;">
	    <img src="/themes/{{$theme}}/assets/img/shape.png" alt="Cerrar">
	  </a>
	</div>
	<div class="clearfix"></div>
	<ul class="nav navbar-nav navbar-right navbar-responsive">

		<li class="">
			<ul class="items_top_responsive hidden-md">
				@if(!Session::has('user'))
				<li><a title="Login" class="login" href="javascript:;"><i class="fa fa-2x fa-user-circle" style="margin-right: 5px;"></i>{{ trans($theme.'-app.login_register.login') }}</a></li>
			@else
				<li><a href="{{ \Routing::slug('user/panel/orders') }}" ><i class="fa fa-2x fa-user-circle fa-lg mr-1" style="margin-right: 5px;"></i>{{ trans($theme.'-app.login_register.perfil') }}</a></li>
				@if(Session::get('user.admin'))
					<li><a href="/admin"  target = "_blank"> {{ trans($theme.'-app.login_register.admin') }}</a></li>
				@endif
			@endif
			</ul>

		</li>
        {{--<li><a title="{{ trans($theme.'-app.home.home')}}" href="/">{{ trans($theme.'-app.home.home')}}</a></li>--}}
        <li><a title="{{ trans($theme.'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.about_us')  ?>">{{ trans($theme.'-app.foot.about_us') }}</a></li>
        <li><a title="{{ trans($theme.'-app.home.how_to_buy') }}" href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy') }}">{{ trans($theme.'-app.home.how_to_buy') }}</a></li>
		<li><a title="{{ trans($theme.'-app.foot.how_to_sell') }}" href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_sell') }}">{{ trans($theme.'-app.foot.how_to_sell') }}</a></li>

                @if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
                  <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme.'-app.foot.auctions')}}</a></li>
                @endif

                @if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
                    <li>
                        <a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans($theme.'-app.foot.direct_sale')}}</a>
                    </li>
                @endif

                @if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
                    <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans($theme.'-app.foot.online_auction')}}</a></li>
                @endif

				@if($global['subastas']->has('S') && $global['subastas']['S']->has('P'))
				  	<li><a href="{{ route('allCategories', ['typeSub' => 'P']) }}">{{ trans($theme.'-app.foot.online_auction')}}</a></li>
			  	@endif

                @if($global['subastas']->has('H'))
                    <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a></li>
                @endif

                <li><a title="{{ trans($theme.'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans($theme.'-app.links.contact')) ?>">{{ trans($theme.'-app.foot.contact')}}</a></li>

				<li><a href="<?=\Routing::translateSeo('valoracion-articulos')?>">{{ trans($theme.'-app.home.free-valuations') }}</a></li>
				<li><a href="<?= \Routing::translateSeo('departamentos')?>">{{ trans($theme.'-app.foot.departments') }}</a></li>
				<li><a href="{{ \Routing::translateSeo('blog')}} ">{{ trans($theme.'-app.blog.blogTitle') }}</a></li>
				<div class="len">
                    <div class="search-item flag-header d-flex align-items-center" style="padding: 0;">

                        @foreach(Config::get('app.locales') as $key => $value)
                            <div>
								<a title="{{ trans($theme.'-app.head.language_'.$key) }}" href="/{{ $key }}">
									<p style="text-transform: uppercase">{{ $key }}</p>
								</a>
							</div>
							<div>
								<span>|</span>
							</div>
						@endforeach
						<?php
							$google_langs = ['de', 'ca', 'fr']
						?>
						@foreach($google_langs as $value)
							<div>
								<a title="{{ trans($theme.'-app.head.language_'.$value) }}" href="/?#googtrans(es|{{ $value }})">
									<p translate="no" style="text-transform: uppercase">{{ $value }}</p>
								</a>
							</div>

							@if (!$loop->last)
								<div>
									<span>|</span>
								</div>
							@endif
						@endforeach


                    </div>
                    @if (\Config::get( 'app.enable_language_selector' ))
                        <select id="selectorIdioma" actuallang="/{{ \App::getLocale() }}/" name="idioma" class="form-control" style="width:100px; height:27px; font-size:11px;">
                            <option value="es"><?= trans($theme.'-app.head.language_es') ?></option>
                            <option value="en"><?= trans($theme.'-app.head.language_en') ?></option>
                        </select>
                    @elseif(\Config::get( 'app.google_translate' ))
                        <div class="google_translate1" style="display: none">
                            <div id="google_translate_element"></div>
                        </div>
                        <script type="text/javascript">
                            function googleTranslateElementInit() {
                                new google.translate.TranslateElement({pageLanguage: '{{$lang}}', includedLanguages: '{{$lang}},ca,de,fr', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                            }
                        </script>
                        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                    @endif
                </div>
	</ul>
</div>
<script>
                       /* var ventana_ancho = $(window).width();
                           if(ventana_ancho <= '1200'){
                               $(".google_translate2").html($(".google_translate1").html());
                               $(".google_translate1").html('');
                           }*/
</script>

