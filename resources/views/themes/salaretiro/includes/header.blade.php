<div class="login_desktop" style="display:none;">
	<div class="login-desktop-container">
		<div class="login-desktop-wrapper">
			<div class="signup">
				<div class="signup-content text-center">
					<div class="signup-title">
						<h2 style="color:white; font-weight: 900;">
							<?= trans($theme.'-app.login_register.register') ?></h2>
					</div>
					<a href="{{ \Routing::slug('login') }}"
						class="btn btn-color"><?= trans($theme.'-app.login_register.crear_cuenta') ?></a>
				</div>
			</div>
			<div class="signin">
				<div class="login_desktop_title">
					<?= trans($theme.'-app.login_register.login') ?>
				</div>
				<img class="closedd" src="/themes/{{$theme}}/assets/img/shape.png" alt="Close">
				<form data-toggle="validator" id="accerder-user-form">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label for="usuario">{{ trans($theme.'-app.login_register.user') }}</label>
						<input class="form-control"
							placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email"
							name="email" type="text">
					</div>
					<div class="form-group">
						<label
							for="contraseña">{{ trans($theme.'-app.login_register.contraseña') }}</label>
						<input class="form-control"
							placeholder="{{ trans($theme.'-app.login_register.contraseña') }}"
							type="password" name="password" maxlength="20">
					</div>
					<p>
						<a onclick="cerrarLogin();" class="c_bordered"
							data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery"
							data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}"
							href="javascript:;" data-toggle="modal"
							data-target="#modalAjax">{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a>
					</p>
					<h5 class="message-error-log text-danger"></h5>
					</p>
					<button id="accerder-user" class="btn btn-login-desktop"
						type="button">{{ trans($theme.'-app.login_register.acceder') }}</button>
					@if(!empty(\Config::get('app.coregistroSubalia')) && \Config::get('app.coregistroSubalia'))
					<br>
					<p style="margin-top:1rem;"><a class="subalia-button"
							href="/{{\Config::get('app.locale')}}/login/subalia"><?= trans($theme.'-app.login_register.register_subalia_in_login') ?>
							{{ trans($theme.'-app.login_register.here') }}</a></p>
					<br>
					@endif
				</form>
			</div>
		</div>
	</div>
</div>

<header>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="header-content">
					<div class="logo">
						<?php
                            $lang = Config::get('app.locale');
                        ?>
						<a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}"><img
								src="/themes/{{$theme}}/assets/img/logo.png"
								style="max-height: 95px;" alt="{{(\Config::get( 'app.name' ))}}"></a>
					</div>
					<div class="menu-access">
						<ul class="items_top wrapper hidden-xs hidden-sm">
							@if(!Session::has('user'))
							<li style="min-width: 124px;">
								<a class="btn_login_desktop"
									title="<?= trans($theme.'-app.login_register.login') ?>"
									href="javascript:;"><?= trans($theme.'-app.login_register.login') ?></a>
							</li>
							<li>
								<a title="{{ trans($theme.'-app.login_register.register') }}"
									href="{{ \Routing::slug('register') }}">{{ trans($theme.'-app.login_register.register') }}</a>
							</li>
							@else
							<li>
								<a
									href="{{ \Routing::slug('user/panel/orders') }}">{{ trans($theme.'-app.login_register.my_panel') }}</a>
							</li>
							@if(Session::get('user.admin'))
							<li>
								<a href="/admin" target="_blank">
									{{ trans($theme.'-app.login_register.admin') }}</a>
							</li>
							@endif
							<li>
								<a
									href="{{ \Routing::slug('logout') }}">{{ trans($theme.'-app.login_register.logout') }}</a>
							</li>
							@endif
						</ul>
						<ul class="items_top_responsive hidden-md hidden-lg">
							@if(Session::get('user.admin'))
							<li style="margin: 0;"><a
									style="margin:  0;background: #154360;color: white;padding:  5px 10px;"
									href="/admin" target="_blank">
									{{ trans($theme.'-app.login_register.admin') }}</a></li>
							@endif

							@if(!Session::has('user'))
							<li><a title="Login" class="login" href="javascript:;"><i
										class="fa fa-2x fa-user fa-lg"></i></a></li>
							@else
							<li><a href="{{ \Routing::slug('user/panel/orders') }}"><i
										class="fa fa-2x fa-user fa-lg"></i></a></li>
							@endif
						</ul>
						<div class="search-component hidden-md visible-lg">
							<form id="formsearch" role="search" action="{{ \Routing::slug('busqueda') }}"
								class="search-component-form">
								<div class="form-group">
									<input class="form-control input-custom"
										placeholder="{{ trans($theme.'-app.head.search_label') }}"
										type="text" name="texto" />
								</div>
								<button type="submit" class="btn btn-custom-search"><i class="fa fa-search"></i>
									<div class="loader mini" style="display: none;"></div>
								</button>
							</form>
						</div>
					</div>
					<div class="languaje">
						@if (!\Config::get( 'app.enable_language_selector' ))
						<select id="selectorIdioma" actuallang="/{{ \App::getLocale() }}/" name="idioma"
							class="form-control" style="width:100px; height:27px; font-size:11px;">
							<option value="es"><?= trans($theme.'-app.head.language_es') ?></option>
							<option value="en"><?= trans($theme.'-app.head.language_en') ?></option>
						</select>
						@elseif(\Config::get( 'app.google_translate' ))
						<div class="google_translate1">
							<div id="google_translate_element"></div>
						</div>
						<script type="text/javascript">
							function googleTranslateElementInit() {
                                        new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'en,es,de,fr,ru,zh-CN', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                                    }
						</script>
						<script type="text/javascript"
							src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

</header>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header visible-md visible-sm visible-xs w-100">
			<div class="navbar-header-mobile">
				<div class="search-component search-component-mobile visible-md visible-sm visible-xs ml-2">
					<form id="formsearch" role="search" action="{{ \Routing::slug('busqueda') }}"
						class="search-component-form">
						<div class="form-group">
							<input class="form-control input-custom"
								placeholder="{{ trans($theme.'-app.head.search_label') }}"
								type="text" name="texto" />
						</div>
						<button type="submit" class="btn btn-custom-search"><i class="fa fa-search"></i>
							<div class="loader mini" style="display: none;"></div>
						</button>
					</form>
				</div>
				<button id="btnResponsive" type="button" class="navbar-toggle collapsed" data-toggle="collapse"
					data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<i class="fa fa-2x fa-bars"></i>
				</button>
			</div>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav hidden-xs hidden-sm hidden-md">
				<?php
                    $subastaObj        = new \App\Models\Subasta();
                    $has_subasta_presenciales = $subastaObj->auctionList ('S', 'W');
                    if( empty($has_subasta_presenciales) && Session::get('user.admin')){
                        $has_subasta_presenciales = array_merge($has_subasta_presenciales,$subastaObj->auctionList ('A', 'W'));
                    }
                    $has_subasta_online = $subastaObj->auctionList ('S', 'O');
                    if(empty($has_subasta_online) && Session::get('user.admin')){
                         $has_subasta_online = array_merge($has_subasta_online,$subastaObj->auctionList ('A', 'O'));
                     }

					 $has_subasta_venta = $subastaObj->auctionList ('S', 'V');
                    if(empty($has_subasta_venta) && Session::get('user.admin')){
                         $has_subasta_venta = array_merge($has_subasta_venta,$subastaObj->auctionList ('A', 'V'));
                     }

                 ?>
				<li class="info <?= empty($has_subasta_online) && empty($has_subasta_presenciales) && empty($has_subasta_venta) ?'hidden':''; ?>">
					<a title="" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
						aria-haspopup="true"
						aria-expanded="false">{{ trans($theme.'-app.foot.auctions')}} <span
							class="caret"></span></a>
					<ul class="dropdown-menu">
						<div class="dropdown-container">
							@if(!empty($has_subasta_presenciales))
							<li class="box">
								<ul>
									<li><a
											href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme.'-app.foot.auctions-presenciales')}}</a>
									</li>
								</ul>
							</li>
							@endif
							@if(!empty($has_subasta_online))
							<li class="box">
								<ul>
									<li><a
											href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans($theme.'-app.foot.online_auction')}}</a>
									</li>
								</ul>
							</li>
							@endif

							@if(!empty($has_subasta_venta))
							<li class="box">
								<ul>
							@php

								$collection = collect($has_subasta_venta);
								$subastas = $collection->mapToGroups(function ($item, $key) {
									return [$item->cod_sub => $item];
									});
							@endphp
								@if(!empty($subastas['VDRESTOS']))
									<li>
										<a href="{{ \Tools::url_auction($subastas['VDRESTOS']->first()->cod_sub,$subastas['VDRESTOS']->first()->name,$subastas['VDRESTOS']->first()->id_auc_sessions,$subastas['VDRESTOS']->first()->reference).'?only_salable=on&order=ref_desc' }}">{{ trans($theme.'-app.foot.direct_sale_jewelry')}}</a>
									</li>
								@elseif(!empty($subastas['ADJDIR21']))
									<li>
										<a href="{{ \Routing::translateSeo('tienda-online', '') }}">{{ trans($theme.'-app.foot.direct_sale_art')}}</a>
									</li>
								@elseif(!empty($subastas['RASTRILL']))
									@php
										$rastrillAuction = $subastas['RASTRILL']->first();
									@endphp
									<li>
										<a href="{{ Tools::url_auction($rastrillAuction->cod_sub, $rastrillAuction->name, $rastrillAuction->id_auc_sessions, $rastrillAuction->reference).'?only_salable=on&order=ref_desc' }}">
											{{ trans("$theme-app.foot.jumble_sale") }}
										</a>
									</li>
								@else
									<li>
										<a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans($theme.'-app.foot.direct_sale')}}
										</a>
									</li>

								@endif


								</ul>
							</li>
							@endif
					</ul>
				</li>
				<?php
					$has_subasta = $subastaObj->auctionList ('H');
				?>
				@if(!empty($has_subasta))
					<li><a
						href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a>
					</li>
				@endif

				<li><a title="{{ trans($theme.'-app.foot.how_to_buy') }}"
						href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy') ?>">{{ trans($theme.'-app.foot.how_to_buy')}}</a>
				</li>
				<li><a title="{{ trans($theme.'-app.foot.how_to_sell') }}"
						href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_sell') ?>">{{ trans($theme.'-app.foot.how_to_sell')}}</a>
				</li>
				<li><a title="{{ trans($theme.'-app.foot.contact')}}"
						href="<?= \Routing::translateSeo('pagina').trans($theme.'-app.links.contact')?>">{{ trans($theme.'-app.foot.contact')}}</a>
				</li>
				<li>
					<a title="{{ trans($theme.'-app.foot.workwithus')}}"
						href="{{ \Routing::translateSeo('workwithus') }}">
						{{ trans("$theme-app.foot.workwithus") }}
					</a>
				</li>
			</ul>
		</div>
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
		<li><a title="{{ trans($theme.'-app.home.home')}}"
				href="/">{{ trans($theme.'-app.home.home')}}</a></li>
		<?php

                   $subastaObj        = new \App\Models\Subasta();
                   $has_subasta = $subastaObj->auctionList ('S', 'W');
                   if( empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                   }

                ?>
		@if(!empty($has_subasta))
		<li><a
				href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme.'-app.foot.auctions')}}</a>
		</li>
		@endif
		<?php
			$has_subasta_venta = $subastaObj->auctionList ('S', 'V');
			if(empty($has_subasta_venta) && Session::get('user.admin')){
				$has_subasta_venta = array_merge($has_subasta_venta,$subastaObj->auctionList ('A', 'V'));
			}
		 ?>

			@if(!empty($has_subasta_venta))
				@php

				$collection = collect($has_subasta_venta);
				$subastas = $collection->mapToGroups(function ($item, $key) {
					return [$item->cod_sub => $item];
					});
				@endphp
				@if(!empty($subastas['VDRESTOS']))
					<li>
						<a href="{{ \Tools::url_auction($subastas['VDRESTOS']->first()->cod_sub,$subastas['VDRESTOS']->first()->name,$subastas['VDRESTOS']->first()->id_auc_sessions,$subastas['VDRESTOS']->first()->reference).'?only_salable=on&order=ref_desc' }}">{{ trans($theme.'-app.foot.direct_sale_jewelry')}}</a>
					</li>
				@elseif(!empty($subastas['ADJDIR21']))
					<li>
						<a href="{{ \Routing::translateSeo('tienda-online', '') }}">{{ trans($theme.'-app.foot.direct_sale_art')}}</a>
					</li>
				@elseif(!empty($subastas['RASTRILL']))
					@php
						$rastrillAuction = $subastas['RASTRILL']->first();
					@endphp
					<li>
						<a href="{{ Tools::url_auction($rastrillAuction->cod_sub, $rastrillAuction->name, $rastrillAuction->id_auc_sessions, $rastrillAuction->reference).'?only_salable=on&order=ref_desc' }}">
							{{ trans("$theme-app.foot.jumble_sale") }}
						</a>
					</li>
				@else
					<li>
						<a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans($theme.'-app.foot.direct_sale')}}
						</a>
					</li>

				@endif


				
			@endif


		<?php
			$has_subasta = $subastaObj->auctionList ('H');
        ?>
		@if(!empty($has_subasta))
		<li><a
			href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a>
		</li>
		@endif

		<?php
                  $has_subasta = $subastaObj->auctionList ('S', 'O');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
                   }
                ?>
		@if(!empty($has_subasta))
		<li><a
				href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans($theme.'-app.foot.online_auction')}}</a>
		</li>
		@endif
		<?php
				  $has_subasta = $subastaObj->auctionList ('S', 'V');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'V'));
				   }
				   $collection = collect($has_subasta);
				   $subastas = $collection->mapToGroups(function ($item, $key) {
					   return [$item->cod_sub => $item];
					});
                ?>
				@if(!empty($has_subasta))
					@if(!empty($subastas['VDRESTOS']))
					<li>
						<a href="{{ \Tools::url_auction($subastas['VDRESTOS']->first()->cod_sub,$subastas['VDRESTOS']->first()->name,$subastas['VDRESTOS']->first()->id_auc_sessions,$subastas['VDRESTOS']->first()->reference).'?only_salable=on&order=ref_desc' }}">{{ trans($theme.'-app.foot.direct_sale_jewelry')}}</a>
					</li>
					@endif
					@if(!empty($subastas['ADJDIR21']))
					<li>
						<a href="{{ \Routing::translateSeo('tienda-online', '') }}">{{ trans($theme.'-app.foot.direct_sale_art')}}</a>
					</li>
					@endif
					@if(!empty($subastas['RASTRILL']))
						@php
							$rastrillAuction = $subastas['RASTRILL']->first();
						@endphp
						<li>
							<a href="{{ Tools::url_auction($rastrillAuction->cod_sub, $rastrillAuction->name, $rastrillAuction->id_auc_sessions, $rastrillAuction->reference).'?only_salable=on&order=ref_desc' }}">
								{{ trans("$theme-app.foot.jumble_sale") }}
							</a>
						</li>
					@endif
				@endif
		<li><a title="{{ trans($theme.'-app.foot.how_to_buy') }}"
				href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy') ?>">{{ trans($theme.'-app.foot.how_to_buy')}}</a>
		</li>
		<li><a title="{{ trans($theme.'-app.foot.how_to_sell') }}"
				href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_sell') ?>">{{ trans($theme.'-app.foot.how_to_sell')}}</a>
		</li>

		<li><a title="{{ trans($theme.'-app.foot.contact')}}"
				href="<?= \Routing::translateSeo('pagina').trans($theme.'-app.links.contact')?>">{{ trans($theme.'-app.foot.contact')}}</a>
		</li>

		<li>
			<a title="{{ trans($theme.'-app.foot.workwithus')}}"
				href="{{ \Routing::translateSeo('workwithus') }}">
				{{ trans("$theme-app.foot.workwithus") }}
			</a>
		</li>

	</ul>
</div>
