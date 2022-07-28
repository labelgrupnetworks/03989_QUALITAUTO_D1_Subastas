<?php

use App\libs\TradLib as TradLib;

$lang = Config::get('app.locale');

$registration_disabled = Config::get('app.registration_disabled');
$fullname = Session::get('user.name');
if (strpos($fullname, ',')) {
    $str = explode(',', $fullname);
    $name = $str[1];
} else {
    $name = $fullname;
}
?>

@php
/* Idiomas */
@endphp
@if (count(Config::get('app.locales')) > 1)
	<div class="lang-selection">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 text-right d-flex justify-content-flex-end">

					@foreach (Config::get('app.locales') as $key => $value)
						<ul class="ul-format list-lang d-inline-flex">
							<?php
							if (\App::getLocale() != $key) {
							    #Obtener la ruta en el idioma contrario segun las tablas seo y/o traducciones links
							    $ruta = "/$key" . TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), \App::getLocale(), $key);
							} else {
							    $ruta = '';
							}
							?>
							<li>
								<a translate="no" title="<?= trans(\Config::get('app.theme') . '-app.head.language_es') ?>"
									class="link-lang  color-letter {{ empty($ruta) ? 'active' : '' }} " {{ empty($ruta) ? '' : "href=$ruta" }}>

									<span translate="no">{{ trans(\Config::get('app.theme') . '-app.home.' . $key) }}</span>
								</a>
							</li>
						</ul>
					@endforeach


				</div>
			</div>
		</div>
	</div>
@endif

<header>
	@php
		/* Barra de búsqueda */
	@endphp
	@if (!empty(\Config::get('app.gridLots')) && \Config::get('app.gridLots') == 'new')
		<div class="menu-principal-search d-flex align-items-center justify-content-center hidden">
			<form id="formsearchResponsive" role="search" action="{{ route('allCategories') }}"
				class="search-component-form flex-inline position-relative">
				<div class="form-group">
					<input class="form-control input-custom br-100"
						placeholder="{{ trans(\Config::get('app.theme') . '-app.head.search_label') }}" type="text"
						name="description" />
				</div>
				<button role="button" type="submit"
					class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans(\Config::get('app.theme') . '-app.head.search_button') }}</button>
			</form>
		</div>
	@else
		<div class="menu-principal-search d-flex align-items-center justify-content-center hidden">
			<form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}"
				class="search-component-form flex-inline position-relative">
				<div class="form-group">
					<input class="form-control input-custom br-100"
						placeholder="{{ trans(\Config::get('app.theme') . '-app.head.search_label') }}" type="text" name="texto" />
				</div>
				<button role="button" type="submit"
					class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans(\Config::get('app.theme') . '-app.head.search_button') }}</button>
			</form>
		</div>
	@endif
	@php
		/* Fin barra de búsqueda */
	@endphp
	<nav class="menu-header">
		<div class="menu-responsive hidden-lg">
			<div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter ">
				{{ trans(\Config::get('app.theme') . '-app.head.menu') }}</div>
		</div>
		<div class="logo-header">
			<a title="{{ \Config::get('app.name') }}" href="/">
				<img class="logo-company" src="/themes/{{ \Config::get('app.theme') }}/assets/img/logo.png"
					alt="{{ \Config::get('app.name') }}">
			</a>
		</div>
		<div class="menu-principal">

			<ul class="menu-principal-content d-flex justify-content-center align-items-center">
				<span role="button"
					class="close-menu-reponsive hidden-lg">{{ trans(\Config::get('app.theme') . '-app.head.close') }}</span>
				<?php //   <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>
				?>
				{{-- <li class="flex-display">
                        <a class="color-letter flex-display link-header justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">
                            <span>{{ trans(\Config::get('app.theme').'-app.home.home')}}</span>
                        </a>
					</li> --}}
				@php
					/* -------------------DESKTOP & MOBILE------------------- */
				@endphp

				{{-- MENÚ CONÓZCANOS --}}
				<li class="open-menu-especial" style="position: relative">

					{{-- Botón de desktop --}}
					<a class="color-letter flex-display link-header justify-center align-items-center hidden-xs hidden-sm hidden-md">
						{{ trans(\Config::get('app.theme') . '-app.foot.know_us') }}
					</a>

					{{-- Botón de móvil --}}
					<a class="color-letter flex-display link-header justify-center align-items-center hidden-lg"
						onclick="javascript:$('#menu_desp_conozcanos').toggle('blind',100)">
						{{ trans(\Config::get('app.theme') . '-app.foot.know_us') }}
					</a>

					<div class="menu-especial" id="menu_desp_conozcanos">
						<p class="item">
							<a href="{{ Routing::translateSeo(trans($theme . '-app.links.contact')) }}">
								{{ trans(\Config::get('app.theme') . '-app.foot.contact_address') }}
							</a>
						</p>
						<p class="item">
							<a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.segre-enlaces.business') }}">
								{{ trans(\Config::get('app.theme') . '-app.foot.business') }}
							</a>
						</p>
						<p class="item">
							<a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.segre-enlaces.experts') }}">
								{{ trans(\Config::get('app.theme') . '-app.foot.experts') }}
							</a>
						</p>
						<p class="item">
							<a href="#">
								{{ trans(\Config::get('app.theme') . '-app.foot.other_services') }}
							</a>
						</p>
						<p class="item">
							<a href="#">
								{{ trans(\Config::get('app.theme') . '-app.foot.featured_cataloging') }}
							</a>
						</p>
						<p class="item">
							<a href="{{ trans(\Config::get('app.theme') . '-app.segre-enlaces.comunic_arte') }}" target="_blank">
								{{ trans(\Config::get('app.theme') . '-app.foot.comunic_arte') }}
							</a>
						</p>
					</div>
				</li>

				@php
					$subastaObj = new \App\Models\Subasta();
					$has_subasta = $subastaObj->auctionList('S', 'W');
					if (count($has_subasta) > 0) {
					$subastaActual = head($has_subasta);
					}

				@endphp

				{{-- MENÚ DE SUBASTA --}}
				{{-- Si no hay subasta no hay opción de menú --}}
				@if (!empty($subastaActual))
					<li class="open-menu-especial" style="position: relative">

						{{-- Botón de desktop --}}
						<a class="color-letter flex-display link-header justify-center align-items-center hidden-xs hidden-sm hidden-md">
							{{ $subastaActual->des_sub }}
						</a>

						{{-- Botón de móvil --}}
						<a class="color-letter flex-display link-header justify-center align-items-center hidden-lg"
							onclick="javascript:$('#menu_desp_subasta').toggle('blind',100)">
							{{ $subastaActual->des_sub }}
						</a>

						<div class="menu-especial" id="menu_desp_subasta">
							<p class="item">
								<a
									href="{{ \Tools::url_auction($subastaActual->cod_sub,$subastaActual->name,$subastaActual->id_auc_sessions,'001') }}?category=1">
									Pintura
								</a>
							</p>
							<p class="item">
								<a
									href="{{ \Tools::url_auction($subastaActual->cod_sub,$subastaActual->name,$subastaActual->id_auc_sessions,'002') }}?category=3">
									Artes Decorativa
								</a>
							</p>
							<p class="item">
								<a
									href="{{ \Tools::url_auction($subastaActual->cod_sub,$subastaActual->name,$subastaActual->id_auc_sessions,'003')}}?category=2">
									Joyas
								</a>
							</p>

							<p class="item">
								<a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.segre-enlaces.telephone_bid') }}">
									{{ trans($theme . '-app.foot.telephone_bid') }}
								</a>
							</p>
							<p class="item">
								<a href="{{ trans($theme . '-app.segre-enlaces.virtual_visit') }}">
									{{ trans($theme . '-app.foot.virtual_visit') }}
								</a>
							</p>

							@if (strtotime($subastaActual->session_start) < strtotime('now'))
								<p class="item">
									<a
										href="{{ \Tools::url_auction($subastaActual->cod_sub,$subastaActual->name,$subastaActual->id_auc_sessions,'001')."?noAward=1&category=1" }}">
										No vendidos Pintura
									</a>
								</p>
								<p class="item">
									<a
										href="{{ \Tools::url_auction($subastaActual->cod_sub,$subastaActual->name,$subastaActual->id_auc_sessions,'002')."?noAward=1&category=3" }}">
										No vendidos Artes Decorativa
									</a>
								</p>
								<p class="item">
									<a
										href="{{ \Tools::url_auction($subastaActual->cod_sub,$subastaActual->name,$subastaActual->id_auc_sessions,'003')."?noAward=1&category=2"}}">
										No vendidos Joyas
									</a>
								</p>
							@endif

							<p class="item">
								<a href="{{ trans($theme . '-app.segre-enlaces.featured_shots') }}">
									{{ trans($theme . '-app.foot.featured_shots') }}
								</a>
							</p>
							<p class="item">
								<a href="{{ trans($theme . '-app.segre-enlaces.videos') }}">
									{{ trans($theme . '-app.foot.videos') }}
								</a>
							</p>

							@php
								$has_subasta = $subastaObj->auctionList('H');
							@endphp

							@if (!empty($has_subasta))
								<p class="item">
									<a href="{{ \Routing::translateSeo('subastas-historicas') }}">
										{{ trans($theme . '-app.foot.previous_auctions') }}
									</a>
								</p>
							@endif

						</div>

					</li>
				@endif

				{{-- MENÚ CATÁLOGOS --}}
				<li class="open-menu-especial" style="position: relative">

					{{-- Botón de desktop --}}
					<a class="color-letter flex-display link-header justify-center align-items-center hidden-xs hidden-sm hidden-md">
						CATÁLOGOS
					</a>

					{{-- Botón de móvil --}}
					<a class="color-letter flex-display link-header justify-center align-items-center hidden-lg"
						onclick="javascript:$('#menu_desp_catalogo').toggle('blind',100)">
						CATALOGOS
					</a>

					<div class="menu-especial" id="menu_desp_catalogo">
						<p class="item">
							<a href="#">
								Recibir Catalogo
							</a>
						</p>
						<p class="item">
							<a href="#">
								Pintura
							</a>
						</p>
						<p class="item">
							<a href="#">
								Artes Decorativas
							</a>
						</p>
						<p class="item">
							<a href="#">
								Joyas
							</a>
						</p>
						<p class="item">
							<a href="#">
								Catálogos por años
							</a>
						</p>
					</div>

				</li>

				{{-- MENÚ COMPRAR VENDER --}}
				<li class="open-menu-especial" style="position: relative">

					{{-- Botón de desktop --}}
					<a class="color-letter flex-display link-header justify-center align-items-center hidden-xs hidden-sm hidden-md">
						{{ trans($theme . '-app.foot.buy_sell') }}
					</a>

					{{-- Botón de móvil --}}
					<a class="color-letter flex-display link-header justify-center align-items-center hidden-lg"
						onclick="javascript:$('#menu_desp_comprar').toggle('blind',100)">
						{{ trans($theme . '-app.foot.buy_sell') }}
					</a>

					<div class="menu-especial" id="menu_desp_comprar">
						<p class="item">
							<a href="#">
								Pujar Subasta Sala
							</a>
						</p>
						<p class="item">
							<a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.segre-enlaces.telephone_bid') }}">
								{{ trans($theme . '-app.foot.telephone_bid') }}
							</a>
						</p>
						<p class="item">
							<a href="#">
								Pujar en directo en web
							</a>
						</p>
						<p class="item">
							<a href="#">
								Pujar Subasta Online
							</a>
						</p>
						<p class="item">
							<a href="#">
								Vender en Segre
							</a>
						</p>
						<p class="item">
							<a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.shipping_returns') }}">
								{{ trans($theme . '-app.foot.shipping_returns') }}
							</a>
						</p>
					</div>

				</li>

				{{-- MENÚ APP SEGRE --}}
				<li class="open-menu-especial" style="position: relative">

					{{-- Botón de desktop --}}
					<a class="color-letter flex-display link-header justify-center align-items-center hidden-xs hidden-sm hidden-md">
						{{ trans($theme . '-app.foot.app_segre') }}
					</a>

					{{-- Botón de móvil --}}
					<a class="color-letter flex-display link-header justify-center align-items-center hidden-lg"
						onclick="javascript:$('#menu_desp_app').toggle('blind',100)">
						{{ trans($theme . '-app.foot.app_segre') }}
					</a>

					<div class="menu-especial" id="menu_desp_app">
						<p class="item">
							<a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.segre-enlaces.download_app_segre') }}">
								{{ trans($theme . '-app.foot.download_app_segre') }}
							</a>
						</p>
					</div>

				</li>
				@php
					/* -------------------END DESKTOP & MOBILE------------------- */
				@endphp

				{{-- @if (!empty($has_subasta))
					<li>
						<a class="color-letter d-flex link-header justify-content-center align-items-center"
							href="{{ \Routing::translateSeo('presenciales') }}">
							<span>{{ trans(\Config::get('app.theme') . '-app.foot.auctions') }}</span>
						</a>
					</li>
				@endif

				< ?php
				$has_subasta = $subastaObj->auctionList('H');
				?>
				@if (!empty($has_subasta))
					<li>
						<a class="color-letter flex-display link-header justify-center align-items-center"
							href="{{ \Routing::translateSeo('subastas-historicas') }}"><span>{{ trans(\Config::get('app.theme') . '-app.foot.historico') }}</span>
						</a>
					</li>
				@endif --}}


				{{-- <li class="li-color">
					<a onclick="javascript:$('#menu_desp').toggle('blind',100)" style="cursor: pointer;">
						{{ trans(\Config::get('app.theme') . '-app.foot.auctions') }}
						&nbsp;
						<span class="caret"></span>
					</a>

					<div id="menu_desp">
						<p><a href="{{ \Routing::translateSeo('presenciales') }}?finished=false"
				 class="item"			>{{ trans(\Config::get('app.theme') . '-app.foot.auctions') }}</a></p>
						<p><a
								href="{{ \Routing::translateSeo('presenciales') }}?finished=true">{{ trans(\Config::get('app.theme') . '-app.foot.auctions-finished') }}</a>
						</p>
					</div>
				</li> --}}


				{{-- <li>
					<a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
				</li>
				<li>
                    <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('calendar') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.calendar')}}</span></a>
                </li>
                 <li>
                    <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('valoracion-articulos') }}"><span> {{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</span></a>
                </li>
                <li>
                    <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="{{ \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
				</li> --}}

			</ul>
		</div>

		@php
			/* Botón de busqueda */
		@endphp
		{{-- <div class="search-header-container  d-flex justify-content-center align-items-center hidden-xs" role="button">
                <div class="search-header d-flex justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.17 29.861">
                    <defs>
                      <style>
                        .cls-1 {
                          fill: #ffffff;
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
                                  fill: #ffffff;
                                }
                              </style>
                            </defs>
                            <g id="cancel" transform="translate(0 -0.435)">
                              <path id="Path_27" data-name="Path 27" class="close-svg" d="M18.993,17.284,33.238,3.039a1.481,1.481,0,0,0,0-2.144,1.481,1.481,0,0,0-2.144,0L16.849,15.139,2.6.894a1.481,1.481,0,0,0-2.144,0,1.481,1.481,0,0,0,0,2.144L14.7,17.284.459,31.528a1.481,1.481,0,0,0,0,2.144,1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306L16.848,19.428,31.093,33.673a1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306a1.481,1.481,0,0,0,0-2.144Z" transform="translate(0 0)"/>
                            </g>
                          </svg>
                        </div>
		</div> --}}

		<div class="user-account">
			@if (!Session::has('user'))
				<div class="user-account-login">
					<a class="flex-display justify-center align-items-center btn_login_desktop btn_login"
						title="<?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>" href="javascript:;">
						<?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>
					</a>
				</div>
			@else
				<div class="my-account color-letter-header">
					<div class="row">
						<div class="col-xs-3 text-center">
							<img width="25px;" class="logo-company" src="/themes/{{ \Config::get('app.theme') }}/assets/img/user.png"
								alt="{{ \Config::get('app.name') }}">
						</div>
						<div class="col-xs-9 text-center">
							@if (!empty($name))
								<div class="hidden-xs" style='font-size: 11px'><b><?= $name ?></b></div>
							@endif
							<span class="hidden-xs">{{ trans(\Config::get('app.theme') . '-app.login_register.my_panel') }}</span>
						</div>
					</div>

					<div class="mega-menu background-body rigth-0">
						{{-- <a class="color-letter" href="{{ \Routing::slug('user/panel/orders') }}" >
                            {{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}

						</a> --}}
						@if (Session::get('user.admin'))
							<a class="color-letter" href="/admin" target="_blank">
								{{ trans(\Config::get('app.theme') . '-app.login_register.admin') }}</a>
						@endif
						<a class="color-letter"
							href="{{ \Routing::slug('logout') }}">{{ trans(\Config::get('app.theme') . '-app.login_register.logout') }}</a>
					</div>
				</div>
			@endif
		</div>
	</nav>
</header>

<div class="login_desktop" style="display: none">
	<div class="login_desktop_content">
		<div class="only-login white-background">
			<div class="login-content-form">
				<img class="closedd" role="button" src="/themes/{{ \Config::get('app.theme') }}/assets/img/shape.png"
					alt="Close">
				<div class="login_desktop_title">
					<?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>
				</div>
				<form data-toggle="validator" id="accerder-user-form"
					class="flex-display justify-center align-items-center flex-column">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<div class="input-login-group">
							<i class="fa fa-user"></i>
							<input class="form-control" placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.user') }}"
								type="email" name="email" type="text">
						</div>
					</div>
					<div class="form-group ">
						<div class="input-login-group">
							<i class="fa fa-key"></i>
							<input class="form-control"
								placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.contraseña') }}" type="password"
								name="password" maxlength="20">
							<img class="view_password eye-password"
								src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
						</div>
					</div>
					<span class="message-error-log text-danger seo_h5"></span></p>
					<div class="pass-login-content">
						<div class="text-center">
							<button id="accerder-user" class="button-principal" type="button">
								<div>{{ trans(\Config::get('app.theme') . '-app.login_register.acceder') }}</div>
							</button>
						</div>
						<a onclick="cerrarLogin();" class="c_bordered pass_recovery_login"
							data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery"
							data-title="{{ trans(\Config::get('app.theme') . '-app.login_register.forgotten_pass_question') }}"
							href="javascript:;" data-toggle="modal"
							data-target="#modalAjax">{{ trans(\Config::get('app.theme') . '-app.login_register.forgotten_pass_question') }}</a>

					</div>
				</form>
				<div class="login-separator"></div>
				<p class="text-center">{{ trans(\Config::get('app.theme') . '-app.login_register.not_account') }}</p>
				<div class="create-account-link">
					@if (empty($registration_disabled))
						<a class="" title="{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}"
							href="https://www.subastassegre.com/default/customer/account/create/">{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}</a>
					@else
						<p class="text-center" style="color: darkred;">
							{{ trans(\Config::get('app.theme') . '-app.login_register.registration_disabled') }}</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>


<script></script>
