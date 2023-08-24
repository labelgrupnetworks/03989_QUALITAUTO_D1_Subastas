<header>
    <div class="col-top">
        <div class="container">
            <div class="menu-nab-top">

                <div class="logo hidden-md hidden-lg">
                  <?php
                        $lang = Config::get('app.locale');
                    ?>
                    <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}"><img class="img-responsive" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.jpg"  alt="{{(\Config::get( 'app.name' ))}}"></a>
                </div>

                <div class="len hidden-xs hidden-sm hidden-md">
                    <div class="search-item flag-header d-flex align-items-center" style="padding: 0;">

                        @foreach(Config::get('app.locales') as $key => $value)
                            <div>
								<a title="{{ trans(\Config::get('app.theme').'-app.head.language_'.$key) }}" href="/{{ $key }}">
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
								<a title="{{ trans(\Config::get('app.theme').'-app.head.language_'.$value) }}" href="/?#googtrans(es|{{ $value }})">
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
                            <option value="es"><?= trans(\Config::get('app.theme').'-app.head.language_es') ?></option>
                            <option value="en"><?= trans(\Config::get('app.theme').'-app.head.language_en') ?></option>
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
                    <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}"><img class="img-responsive" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.jpg"  alt="{{(\Config::get( 'app.name' ))}}"></a>
                </div>

                <div class="access d-flex flex-direction-column justify-content-center">

					<ul class=" hidden-xs hidden-sm">
						<li><a class="search_btn" title="{{ trans(\Config::get('app.theme').'-app.head.search_button') }}" href="{{ \Routing::slug('busqueda') }}">{{ trans(\Config::get('app.theme').'-app.head.search_button') }} <i class="fa fa-search"></i></a></li>
					</ul>
					{{-- buscador original
                    <div class="search" style="margin-bottom: 5px;">
                        <form id="formsearch" role="search" action="{{ \Routing::slug('busqueda') }}" class="navbar-form form-search-header">
                            <div class="form-group">
                                <input class="form-control input-search-custom" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto">
                                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
					</div>
					--}}

                        @if(!Session::has('user'))
                        <ul class=" hidden-xs hidden-sm">
                            <li><a class="btn_login_desktop" title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>" href="javascript:;"><?= trans(\Config::get('app.theme').'-app.login_register.login') ?></a></li>
                            <li><a title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a></li>
                        </ul>
                        @else
                        <span class="name_user_logged"></span>
                        <p class="cortar name_user hidden-xs hidden-sm">{{ trans_choice(\Config::get('app.theme').'-app.user_panel.hello',1,['name'=>mb_convert_case(Session::get('user.name'), MB_CASE_TITLE, "UTF-8")]) }}</p>
                        <ul class=" hidden-xs hidden-sm">

                            <li><a href="{{ \Routing::slug('user/panel/orders') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</a> </li>
                            @if(Session::get('user.admin'))
                                <li><a href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a></li>
                            @endif
                            <li><p><a href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a></p></li>
                        </ul>
                        @endif

                    <div class="google_translate2"></div>
                </div>
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
                        <p><h5 class="message-error-log text-danger"></h5></p>
                        <button id="accerder-user" class="btn btn-login-desktop" type="button">{{ trans(\Config::get('app.theme').'-app.login_register.acceder') }}</button>
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
                <span>{{ trans(\Config::get('app.theme').'-app.head.menu') }}</span>
	    </button>
	  </div>
	  <div id="navbar" class="navbar-collapse collapse">
	    <ul class="nav navbar-nav hidden-xs hidden-sm hidden-md">
                {{--<li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>--}}
				<li><a title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a></li>

				<li>
					<a onclick="javascript:$('#menu_desp').toggle('blind',100)" style="cursor: pointer;">
						{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}
						&nbsp;
						<span class="caret"></span>
					</a>

					<div id="menu_desp">
						<a title="{{ trans(\Config::get('app.theme').'-app.home.how_to_buy') }}" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy') }}">{{ trans(\Config::get('app.theme').'-app.home.how_to_buy') }}</a>
						<a title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell') }}">{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}</a>
					</div>
				</li>

			@php
				$subastaObj= new \App\Models\Subasta();
				$has_subasta = $subastaObj->auctionList ('H');

				$sub_historica = false;
				if(!empty($has_subasta)){
					$sub_historica = true;
				}

				$has_subasta = $subastaObj->auctionList ('S', 'W');
				if(Session::get('user.admin')){
                    $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                }

				$sub_presencial = false;
                if(!empty($has_subasta) && count($has_subasta)>=2){
					$url_subasta = \Routing::translateSeo('presenciales');
					$sub_presencial = true;
                }elseif(!empty($has_subasta) && count($has_subasta)==1){
					$url_subasta=\Routing::translateSeo('info-subasta').$has_subasta[0]->cod_sub."-".str_slug($has_subasta[0]->name);
					$sub_presencial = true;
                }else{
                    $url_subasta="#";
				}
			@endphp

			@if($sub_presencial || $sub_historica)
				<li>
					<a onclick="javascript:$('#menu_sub').toggle('blind',100)" style="cursor: pointer;">
						{{ trans(\Config::get('app.theme').'-app.foot.presenciales') }}
						&nbsp;
						<span class="caret"></span>
					</a>

					<div id="menu_sub">
						@if ($sub_presencial)
							<a href="{{ $url_subasta }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
						@endif
						@if ($sub_historica)
							<a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a>
						@endif
					</div>
				</li>
			@endif

                @if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
                    <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
				@endif

			  	@if($global['subastas']->has('S') && $global['subastas']['S']->has('P'))
				  	<li><a href="{{ route('allCategories', ['typeSub' => 'P']) }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
			  	@endif
				@if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))

					@if($global['subastas']['S']['V']->has('VDJ'))
						@php
							$subasta = $global['subastas']['S']['V']['VDJ']->first();
							$url_venta = \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions, $subasta->reference)."?only_salable=on";
							unset($global['subastas']['S']['V']['VDJ']);
						@endphp

						<li><a href="{{$url_venta}}">{{ trans(\Config::get('app.theme').'-app.subastas.jewelry')}}</a></li>
					@endif

					@if($global['subastas']['S']['V']->count() == 1)
						@php
							$subasta = $global['subastas']['S']['V']->first()->first();
							$url_venta = \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions, $subasta->reference)."?only_salable=on";
						@endphp
						<li><a href="{{$url_venta}}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>

					@elseif ($global['subastas']['S']['V']->count() > 1)
						<li><a href="{{route('subastas.venta_directa')}}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
					@endif

                @endif

               <?php /*

                *    <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>

                * */
                  ?>
	      		<li><a title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a></li>
			  <li><a href="<?=\Routing::translateSeo('valoracion-articulos')?>">{{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</a></li>
			  {{-- <li><a href="{{ \Routing::translateSeo('departamentos') }}">{{ trans(\Config::get('app.theme').'-app.foot.departments') }}</a></li> --}}
			  <li><a href="{{ \Routing::translateSeo('blog')}} ">{{ trans(\Config::get('app.theme').'-app.blog.blogTitle') }}</a></li>

			</ul>



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

		<li class="">
			<ul class="items_top_responsive hidden-md">
				@if(!Session::has('user'))
				<li><a title="Login" class="login" href="javascript:;"><i class="fa fa-2x fa-user-circle" style="margin-right: 5px;"></i>{{ trans(\Config::get('app.theme').'-app.login_register.login') }}</a></li>
			@else
				<li><a href="{{ \Routing::slug('user/panel/orders') }}" ><i class="fa fa-2x fa-user-circle fa-lg mr-1" style="margin-right: 5px;"></i>{{ trans(\Config::get('app.theme').'-app.login_register.perfil') }}</a></li>
				@if(Session::get('user.admin'))
					<li><a href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a></li>
				@endif
			@endif
			</ul>

		</li>
        {{--<li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>--}}
        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a></li>
        <li><a title="{{ trans(\Config::get('app.theme').'-app.home.how_to_buy') }}" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy') }}">{{ trans(\Config::get('app.theme').'-app.home.how_to_buy') }}</a></li>
		<li><a title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell') }}">{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}</a></li>
            <?php

                   $subastaObj        = new \App\Models\Subasta();
                   $has_subasta = $subastaObj->auctionList ('S', 'W');
                   if( empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta = array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                   }

                ?>
                @if(!empty($has_subasta))
                  <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
                @endif


                <?php
                  $has_subasta = $subastaObj->auctionList ('S', 'V');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta = array_merge($has_subasta,$subastaObj->auctionList ('A', 'V'));
                   }
                ?>
                @if(!empty($has_subasta))
                    <li>
                        <a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a>
                    </li>
                @endif


                <?php
                  $has_subasta = $subastaObj->auctionList ('S', 'O');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta = array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
                   }
                ?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
                @endif
				<?php
				$has_subasta = $subastaObj->auctionList ('S', 'P');
				if(empty($has_subasta) && Session::get('user.admin')){
					 $has_subasta = array_merge($has_subasta,$subastaObj->auctionList ('A', 'P'));
				 }
			  	?>
			  	@if(!empty($has_subasta))
				  	<li><a href="{{ route('allCategories', ['typeSub' => 'P']) }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
			  	@endif

                <?php
                    $has_subasta = $subastaObj->auctionList ('H');
                ?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
                @endif

                <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a></li>

				<li><a href="<?=\Routing::translateSeo('valoracion-articulos')?>">{{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</a></li>
				<li><a href="<?= \Routing::translateSeo('departamentos')?>">{{ trans(\Config::get('app.theme').'-app.foot.departments') }}</a></li>
				<li><a href="{{ \Routing::translateSeo('blog')}} ">{{ trans(\Config::get('app.theme').'-app.blog.blogTitle') }}</a></li>
				<div class="len">
                    <div class="search-item flag-header d-flex align-items-center" style="padding: 0;">

                        @foreach(Config::get('app.locales') as $key => $value)
                            <div>
								<a title="{{ trans(\Config::get('app.theme').'-app.head.language_'.$key) }}" href="/{{ $key }}">
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
								<a title="{{ trans(\Config::get('app.theme').'-app.head.language_'.$value) }}" href="/?#googtrans(es|{{ $value }})">
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
                            <option value="es"><?= trans(\Config::get('app.theme').'-app.head.language_es') ?></option>
                            <option value="en"><?= trans(\Config::get('app.theme').'-app.head.language_en') ?></option>
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

