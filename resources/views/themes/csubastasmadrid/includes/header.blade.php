@inject('auctionService', 'App\Services\Auction\AuctionService')

@php
	use App\libs\TradLib as TradLib;
@endphp

<div class="all-header-sticky">
	<header>
		<div class="navbar-top">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="navbar-top-wrapper">
							<ul class="user-navbar">
								@if (!Session::has('user'))
									{{-- *CUANDO SE HABILITE EL REGISTRO NUEVO, SUBIR TANTO APP como ROUTES* --}}
									{{-- <li class="hidden-xs hidden-sm"><a class="btn-1" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a></li> --}}
									<!-- DESKTOP -->
									<li class="hidden-xs hidden-sm"><a class="btn_login_desktop btn-1"
											title="<?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>"
											href="javascript:;"><?= trans(\Config::get('app.theme') . '-app.login_register.login') ?></a>
									</li>
									<li class="hidden-xs hidden-sm"><a class="btn-1"
											title="{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}"
											href="{{ \Routing::slug('login') }}">{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}</a>
									</li>
									<!-- MOBILE -->
									<li class="hidden-lg hidden-md sesion-responsive"><a title="Login" class="login"
											href="javascript:;"><i class="fa fa-user fa-lg"></i></a></li>
								@else
									<!-- DESKTOP -->
									<li class="hidden-xs hidden-sm session_name parpadea">
										{{ trans(\Config::get('app.theme') . '-app.foot.welcome_mr_mrs') . Session::get('user.name') }}
									</li>

									<li class="hidden-xs hidden-sm"><a class="btn-1"
											href="{{ \Routing::slug('user/panel/orders') }}">{{ trans(\Config::get('app.theme') . '-app.login_register.my_panel') }}</a>
									</li>
									<!-- MOBILE -->
									<li class="hidden-lg hidden-md sesion-responsive"><a
											href="{{ \Routing::slug('user/panel/orders') }}"><i
												class="fa fa-user fa-lg"></i></a></li>

									@if (Session::get('user.admin'))
										<!-- DESKTOP -->
										<li class="hidden-xs hidden-sm"><a class="btn-1" href="/admin"
												target="_blank">
												{{ trans(\Config::get('app.theme') . '-app.login_register.admin') }}</a>
										</li>
										<!-- MOBILE -->
										<li class="hidden-lg hidden-md sesion-responsive"><a href="/admin" target="_blank">
												{{ trans(\Config::get('app.theme') . '-app.login_register.admin') }}</a>
										</li>
									@endif
									<!-- DESKTOP -->
									<li class="hidden-xs hidden-sm"><a class="btn-live"
											href="{{ \Routing::slug('logout') }}">{{ trans(\Config::get('app.theme') . '-app.login_register.logout') }}</a>
										</p>
									</li>
								@endif

								<div class="login_desktop" style="display:none;">
									<div class="login_desktop_title">
										<?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>
									</div>
									<img class="closedd"
										src="/themes/{{ \Config::get('app.theme') }}/assets/img/shape.png" alt="Close">
									<form data-toggle="validator" id="accerder-user-form">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<div class="form-group">
											<label
												for="usuario">{{ trans(\Config::get('app.theme') . '-app.login_register.user') }}</label>
											<input class="form-control"
												placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.user') }}"
												type="email" name="email" type="text">
										</div>
										<div class="form-group">
											<label
												for="contraseña">{{ trans(\Config::get('app.theme') . '-app.login_register.contraseña') }}</label>
											<input class="form-control"
												placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.contraseña') }}"
												type="password" name="password" maxlength="20">
										</div>
										<p>
											<a onclick="cerrarLogin();" class="c_bordered"
												data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery"
												data-title="{{ trans(\Config::get('app.theme') . '-app.login_register.forgotten_pass_question') }}"
												href="javascript:;" data-toggle="modal" data-target="#modalAjax">
												{{ trans(\Config::get('app.theme') . '-app.login_register.forgotten_pass_question') }}
											</a>
										</p>
										<h5 class="message-error-log text-danger"></h5>
										<button id="accerder-user" class="btn btn-2"
											type="button">{{ trans(\Config::get('app.theme') . '-app.login_register.acceder') }}</button>
									</form>
								</div>

							</ul>
							{{-- <div class="search-component hidden-xs">
								<form role="search" action="{{ \Routing::slug('busqueda') }}"
									class="search-component-form formsearch">
									<div class="form-group">
										<input class="form-control input-custom"
											placeholder="{{ trans(\Config::get('app.theme') . '-app.head.search_label') }}"
											type="text" name="texto" />
									</div>
									<button type="submit" class="btn btn-custom-search"><i class="fa fa-search"></i>
										<div class="loader search-loader"
											style="display:none;position: absolute;top: -62.50px;right:-1px;width: 25px;height: 25px;">
										</div>
									</button>

								</form>
							</div> --}}
							<div class="languaje">
								@if (\Config::get('app.enable_language_selector'))
									<select id="selectorIdioma" actuallang="/{{ \App::getLocale() }}/" name="idioma"
										class="form-control" style="width:100px; height:27px; font-size:11px;">
										<option value="es"><?= trans(\Config::get('app.theme') . '-app.head.language_es') ?>
										</option>
										<option value="en"><?= trans(\Config::get('app.theme') . '-app.head.language_en') ?>
										</option>
									</select>
								@elseif(\Config::get('app.google_translate'))
									<div class="google_translate1">
										<div id="google_translate_element"></div>
									</div>
									<script type="text/javascript">
										function googleTranslateElementInit() {
											new google.translate.TranslateElement({
												pageLanguage: '{{config('app.locale')}}',
												includedLanguages: 'ca,de,fr,ru,ja,it,zh-CN',
												layout: google.translate.TranslateElement.InlineLayout.SIMPLE
											}, 'google_translate_element');
										}
									</script>
									<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
									</script>
								@endif
							</div>

							<ul class="ul-format list-lang d-inline-flex">
								@foreach (Config::get('app.locales') as $key => $value)
									@php
										$ruta = '';
										if (\App::getLocale() != $key) {
											#Obtener la ruta en el idioma contrario segun las tablas seo y/o traducciones links
											$ruta = "/$key" . TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), \App::getLocale(), $key);
										}
									@endphp
									<li>
										<a translate="no" title="<?= trans($theme . '-app.head.language_es') ?>"
											class="link-lang {{ empty($ruta) ? 'active' : '' }}"
											{{ empty($ruta) ? '' : "href=$ruta" }}>
											<span translate="no">{{ trans($theme . '-app.home.' . $key) }}</span>
										</a>
									</li>

									@if ($loop->first)
										<li style=""><span>|</span></li>
									@endif
								@endforeach
							</ul>

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="logo">
						<?php
						$lang = Config::get('app.locale');
						?>
						<a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
							{{-- <img class="img-responsive" src="/themes/{{ \Config::get('app.theme') }}/assets/img/logo.svg" --}}
							<img class="img-responsive" src="/themes/{{ \Config::get('app.theme') }}/assets/img/logo_header.png?v=2"
								alt="{{ \Config::get('app.name') }}" style="display: inline-block; margin-top: .5rem;">
						</a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="col-xs-12">
				<div class="navbar-header visible-sm visible-xs">
					<ul class="nav-responsive-wrapper">
						<button id="btnResponsive" type="button" class="navbar-toggle collapsed" data-toggle="collapse"
							data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<div class="search-component hidden-lg hidden-sm hidden-md">
							<form role="search" action="{{ \Routing::slug('busqueda') }}"
								class="search-component-form formsearch">
								<div class="form-group">
									<input class="form-control input-custom"
										placeholder="{{ trans(\Config::get('app.theme') . '-app.head.search_label') }}"
										type="text" name="texto" />
								</div>
								<button type="submit" class="btn btn-custom-search"><i class="fa fa-search"></i>
									<div class="loader search-loader"
										style="display:none;position: absolute;top: -61.50px;right:-0px;width: 30.5px;height: 30.5px;">
									</div>
								</button>
							</form>
						</div>
					</ul>

				</div>
			</div>


			<div id="navbar" class="navbar-collapse collapse">

				<ul class="nav navbar-nav hidden-xs hidden-sm">
					<?php //   <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>
					?>
					<li class="li-color"><a title="{{ trans(\Config::get('app.theme') . '-app.home.home') }}"
							href="/">{{ trans(\Config::get('app.theme') . '-app.home.home') }}</a></li>
					<?php

					$subastaObj = new \App\Models\Subasta();
					$has_subasta = [];
					$has_subasta = $subastaObj->auctionList('S', 'W');

					if (Session::get('user.admin')) {
						$has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'W'));
					}
					$not_finished = false;
					$finished = false;
					?>
					@foreach ($has_subasta as $header_sub)
						@if (strtotime($header_sub->session_end) > time())
							<?php $not_finished = true; ?>
						@elseif(strtotime($header_sub->session_end) < time())
							<?php $finished = true; ?>
						@endif
					@endforeach


					@if (!empty($has_subasta))


						<li class="li-color">
							<a onclick="javascript:$('#menu_desp').toggle('blind',100)" style="cursor: pointer;">
								{{ trans(\Config::get('app.theme') . '-app.foot.auctions') }}
								&nbsp;
								<span class="caret"></span>
							</a>

							<div id="menu_desp">
								@if (!empty($has_subasta) && $not_finished)
									<a href="{{ \Routing::translateSeo('presenciales') }}?finished=false"
										class="item">{{ trans(\Config::get('app.theme') . '-app.foot.auctions') }}</a>
								@endif
								@if (!empty($has_subasta) && $finished)
									<a
										href="{{ \Routing::translateSeo('presenciales') }}?finished=true">{{ trans(\Config::get('app.theme') . '-app.foot.auctions-finished') }}</a>
								@endif
							</div>
						</li>
					@endif

					<?php #SUBASTAS DE VENTA DIRECTA
					$has_subasta_v = $subastaObj->auctionList('S', 'V');
					if (Session::get('user.admin')) {
						$has_subasta_v = array_merge($has_subasta_v, $subastaObj->auctionList('A', 'V'));
					}

					if (count($has_subasta_v) == 1) {
						$firstVdAuction = head($has_subasta_v);

						if ($auctionService->existsAuctionIndex($firstVdAuction->cod_sub, $firstVdAuction->id_auc_sessions)) {
							$url_lotes = \Routing::translateSeo('indice-subasta') . head($has_subasta_v)->cod_sub . '-' . str_slug(head($has_subasta_v)->name) . '-' . head($has_subasta_v)->id_auc_sessions;
						} else {
							$url_lotes = \Routing::translateSeo('subasta') . head($has_subasta_v)->cod_sub . '-' . str_slug(head($has_subasta_v)->name) . '-' . head($has_subasta_v)->id_auc_sessions;
						}
					}

					?>
					@if (!empty($has_subasta_v))
						<li class="li-color"><a
								href="{{ $url_lotes ?? \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme') . '-app.foot.direct_sale') }}</a>
						</li>
					@endif


					<li class="li-color"><a
							href="<?= \Routing::translateSeo('subastas-historicas') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.historico') }}</a>
					</li>
					<?php
					$has_subasta = $subastaObj->auctionList('S', 'O');
					if (empty($has_subasta) && Session::get('user.admin')) {
						$has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'O'));
					}
					?>
					@if (!empty($has_subasta))
						<li class="li-color"><a
								href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme') . '-app.foot.online_auction') }}</a>
						</li>
					@endif
					{{-- <li class="li-color">
						<a
							href="< ?= \Routing::translateSeo('departamentos') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.departments') }}</a>
					</li> --}}
					<li class="li-color">
						<a
							href="<?= \Routing::translateSeo('video-subastas')?>">{{ trans(\Config::get('app.theme') . '-app.foot.auction_videos') }}</a>
					</li>
					<li class="li-color">
						<a
							href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.how_to_buy') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.how_to_buy') }}</a>
					</li>
					<li class="li-color"><a
							title="{{ trans(\Config::get('app.theme') . '-app.foot.how_to_sell') }}"
							href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.how_to_sell') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.how_to_sell') }}</a>
					</li>
					<li class="li-color">

						<a title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}" href="{{ route('contact_page') }}">
							{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</div>
<div id="menuResponsive" class="hidden-lg hidden-md">
    <div class="me">
        <a id="btnResponsiveClose" title="Cerrar" href="javascript:;">
            <img src="/themes/{{ \Config::get('app.theme') }}/assets/img/shape.png" alt="Cerrar">
        </a>
    </div>
    <div class="clearfix"></div>
    <ul class="nav navbar-nav navbar-right navbar-responsive">
        <li class="li-color">
            <a title="{{ trans(\Config::get('app.theme') . '-app.home.home') }}"
                href="/">{{ trans(\Config::get('app.theme') . '-app.home.home') }}</a>
        </li>
        <?php

        $subastaObj = new \App\Models\Subasta();
        $has_subasta = $subastaObj->auctionList('S');
        if (Session::get('user.admin')) {
            $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A'));
        }
        $not_inished = false;
        $finished = false;
        ?>
        @foreach ($has_subasta as $header_sub)
            @if (strtotime($header_sub->session_end) > time())
                <?php $not_finished = true; ?>
            @elseif(strtotime($header_sub->session_end) < time())
                <?php $finished = true; ?>
            @endif
        @endforeach
        @if (!empty($has_subasta) && $not_finished)
            <li class="li-color"><a
                    href="{{ \Routing::translateSeo('presenciales') }}?finished=false">{{ trans(\Config::get('app.theme') . '-app.foot.auctions') }}</a>
            </li>
        @endif

        <?php
        $has_subasta_venta_directa = $subastaObj->auctionList('S', 'V');
        if (empty($has_subasta_venta_directa) && Session::get('user.admin')) {
            $has_subasta_venta_directa = array_merge($has_subasta_venta_directa, $subastaObj->auctionList('A', 'V'));
        }
        ?>
        @if (!empty($has_subasta_venta_directa))
            <li class="li-color"><a
                    href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme') . '-app.foot.direct_sale') }}</a>
            </li>
        @endif

        @if (!empty($has_subasta) && $finished)
            <li class="li-color"><a
                    href="{{ \Routing::translateSeo('presenciales') }}?finished=true">{{ trans(\Config::get('app.theme') . '-app.foot.auctions-finished') }}</a>
            </li>
        @endif
        <?php
        $has_subasta = $subastaObj->auctionList('S', 'O');
        if (empty($has_subasta) && Session::get('user.admin')) {
            $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'O'));
        }
        ?>
        @if (!empty($has_subasta))
            <li class="li-color"><a
                    href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme') . '-app.foot.online_auction') }}</a>
            </li>
        @endif

		<li class="li-color">
			<a
				href="<?= \Routing::translateSeo('video-subastas')?>">{{ trans(\Config::get('app.theme') . '-app.foot.auction_videos') }}</a>
		</li>
        <li class="li-color">
            <a
                href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.how_to_buy') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.how_to_buy') }}</a>
        </li>
        <li class="li-color"><a title="{{ trans(\Config::get('app.theme') . '-app.foot.how_to_sell') }}"
                href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.how_to_sell') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.how_to_sell') }}</a>
        </li>
        <li class="li-color">

            <a title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}"
                href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.contact') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}</a>
        </li>

    </ul>
</div>
