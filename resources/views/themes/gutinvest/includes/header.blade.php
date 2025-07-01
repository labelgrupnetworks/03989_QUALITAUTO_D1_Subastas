<?php
$fgortsec0 = new App\Models\V5\FgOrtsec0();
$categories = $fgortsec0
    ->GetAllFgOrtsec0('DEP')
    ->get()
    ->toarray();
$sectorInmuebles = collect($categories)->firstWhere('lin_ortsec0', 8);
$empre = new \App\Models\Enterprise();
$empresa = $empre->getEmpre();
?>

<header>
	<div class="pre-header visible-xs" style="background: #2D3031">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 no-padding" style="display: flex;flex-direction: row;justify-content: flex-end;">


					<div class="contact-bar">
						@if (!empty($empresa->tel1_emp))
							<a href="tel:{{ $empresa->tel1_emp }}"><i class="fa fa-phone fa-flip-horizontal"></i> {{ $empresa->tel1_emp }}</a>
						@endif
						@if (!empty($empresa->email_emp))
							<a href="mailto:{{ $empresa->email_emp }}"><i class="fa fa-email fa-flip-horizontal"></i>
								{{ $empresa->email_emp }}</a>
						@endif
					</div>

					@if (Session::has('user'))
						@if (Session::get('user.admin'))
							<div class="admin-item" style="position: relative">
								<a href="/admin" target = "_blank" style="color: white;">
									{{ trans(\Config::get('app.theme') . '-app.login_register.admin') }}</a>
							</div>
						@endif
					@endif
					<div class="login-item" style="position: relative">
						@if (!Session::has('user'))
							<div class="btn_login_respnsive hidden-lg">
								<a title="Login" class="login" href="javascript:;"><i class="fa fa-user fa-lg"></i></a>
							</div>
						@else
							<a href="{{ \Routing::slug('user/panel/orders') }}"><i class="fa fa-user fa-2x hidden-lg"></i><span
									class="visible-lg">{{ trans(\Config::get('app.theme') . '-app.login_register.my_panel') }}</span></a>
						@endif

					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 header-gutinvest no-padding">
				<div class="col-lg-2 col-xs-3 logo-item">
					<?php
					$lang = Config::get('app.locale');
					?>
					<a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
						<img src="{{ Tools::urlAssetsCache("/themes/$theme/assets/img/logo.jpg") }}"
							alt="{{ \Config::get('app.name') }}" class="img-responsive ">
					</a>
				</div>
				<div class="col-lg-7 col-xs-7 visible-lg menu-item">
					<ul>
						<li>
							<a title="{{ trans(\Config::get('app.theme') . '-app.home.home') }}"
								href="{{ $lang == 'es' ? url('/es') : url('/en') }}">{{ trans(\Config::get('app.theme') . '-app.home.home') }}</a>
						</li>
						<?php
						$subastaObj = new \App\Models\Subasta();

						?>




						<li class="open-menu-especial" style="position: relative">
							<a
								href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme') . '-app.foot.sells') }}
								<i class="fa fa-caret-down"></i></a>
							<div class="menu-especial">
								<a
									href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme') . '-app.subastas.next_sell') }}</a>
								<?php

								$has_subasta = $subastaObj->auctionList('S', 'O');

								if (empty($has_subasta) && Session::get('user.admin')) {
								    $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'O'));
								}

								?>
								@if (!empty($has_subasta))
									<a
										href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme') . '-app.foot.online_auction') }}</a>
								@endif

								<?php
								$has_subasta = $subastaObj->auctionList('S', 'W');

								if (empty($has_subasta) && Session::get('user.admin')) {
								    $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'W'));
								}

								?>
								@if (!empty($has_subasta))
									<a
										href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme') . '-app.foot.presenciales') }}</a>
								@endif
								<a
									href="{{ \Routing::translateSeo('busqueda') }}">{{ trans(\Config::get('app.theme') . '-app.foot.search_actives') }}</a>
								<?php
								$has_subasta = $subastaObj->auctionList('H');
								?>
								@if (!empty($has_subasta))
									<a
										href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme') . '-app.foot.historico') }}</a>
								@endif
							</div>
						</li>

						<?php
						$has_subasta = $subastaObj->auctionList('S', 'V');
						if (empty($has_subasta) && Session::get('user.admin')) {
						    $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'V'));
						}
						?>
						@if (!empty($has_subasta))
							<li><a
									href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme') . '-app.foot.direct_sale') }}</a>
							</li>
						@endif
						<?php /*
 *    <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>

                        * */
						?>
						<li class="open-menu-especial" style="position: relative">
							<a>{{ trans(\Config::get('app.theme') . '-app.lot.categories') }} <i class="fa fa-caret-down"></i></a>
							<div class="menu-especial">
								@foreach ($categories as $category)
									{{-- La categoria de sectorInmuebles la quieren a parte --}}
									@if ($category['lin_ortsec0'] == 8)
										@continue
									@endif
									<a href='{{ route('department', ['text' => $category['key_ortsec0']]) }}'>
										{{ $category['des_ortsec0'] }}
									</a>
								@endforeach

							</div>
						</li>
						<li>
							<a title="{{ $sectorInmuebles['des_ortsec0'] }}"
								href="{{ route('department', ['text' => $sectorInmuebles['key_ortsec0']]) }}">{{ $sectorInmuebles['des_ortsec0'] }}</a>
						</li>
						<li>
							<a title="{{ trans(\Config::get('app.theme') . '-app.foot.about_us') }}"
								href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.about_us') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.about_us') }}</a>
						</li>

						<li>
							<a title="{{ trans(\Config::get('app.theme') . '-app.foot.services') }}"
								href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.services') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.services') }}</a>
						</li>
						<li>
							<a title="{{ trans(\Config::get('app.theme') . '-app.foot.valorar_producto') }}"
								href="<?= \Routing::translateSeo('valoracion-articulos') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.valorar_producto') }}</a>
						</li>
						<li>
							<a title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}"
								href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.contact') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}</a>
						</li>

						<li class="item-phone">
							<a href="tel:(+34) 932696282"><i class="fa fa-phone fa-flip-horizontal"></i> (+34) 932696282</a>
						</li>

					</ul>

				</div>

				<div class="col-lg-3 col-xs-9 no-padding group-menu-item">
					<div class="search-item">
						<div class="search-icon-img">
							<img src="/themes/{{ \Config::get('app.theme') }}/assets/img/search.png"
								alt="{{ \Config::get('app.name') }}" class="img-responsive search-icon">
							<img src="/themes/{{ \Config::get('app.theme') }}/assets/img/cancel.png"
								alt="{{ \Config::get('app.name') }}" class="img-responsive cancel-icon" style="display: none;">
						</div>
						<div class="search-input" style="display: none;">
							<form id="formsearch-responsive" role="search" action="{{ \Routing::slug('busqueda') }}">
								<div class="form-group" style="padding-right: 0;">
									<input class="form-control input-custom"
										placeholder="{{ trans(\Config::get('app.theme') . '-app.head.search_label') }}" type="text" name="texto"
										id="textSearch">
									<button type="submit" class="btn btn-custom-search" style="right:3px;">
										<i class="fa fa-search"></i>
									</button>
								</div>
							</form>
						</div>


					</div>
					@if (Session::has('user'))
						@if (Session::get('user.admin'))
							<div class="admin-item hidden-xs" style="position: relative">
								<a href="/admin" target = "_blank"> {{ trans(\Config::get('app.theme') . '-app.login_register.admin') }}</a>
							</div>
						@endif
					@endif
					<div class="login-item hidden-xs" style="position: relative">
						@if (!Session::has('user'))
							<div role="button" class="btn_login_desktop visible-lg">
								<div class="visible-lg"><?= trans(\Config::get('app.theme') . '-app.login_register.login') ?></div>
								<div class="visible-lg">{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}</div>
								<i class="fa fa-user fa-3x hidden-lg"></i>

							</div>
							<div class="btn_login_respnsive hidden-lg">
								<a title="Login" class="login" href="javascript:;"><i class="fa fa-user fa-lg"></i></a>
							</div>
						@else
							<a href="{{ \Routing::slug('user/panel/orders') }}"><i class="fa fa-user fa-3x hidden-lg"></i><span
									class="visible-lg">{{ trans(\Config::get('app.theme') . '-app.login_register.my_panel') }}</span></a>
						@endif
						<div class="login_desktop" style="display: none;">
							<div class="login_desktop_title">
								<?= trans(\Config::get('app.theme') . '-app.login_register.login') ?>
							</div>
							<img class="closedd" src="/themes/{{ \Config::get('app.theme') }}/assets/img/shape.png" alt="Close">
							<form data-toggle="validator" id="accerder-user-form">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="form-group">
									<label for="usuario">{{ trans(\Config::get('app.theme') . '-app.login_register.user') }}</label>
									<input class="form-control" placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.user') }}"
										type="email" name="email" type="text">
								</div>
								<div class="form-group">
									<label for="contraseña">{{ trans(\Config::get('app.theme') . '-app.login_register.contraseña') }}</label>
									<input class="form-control"
										placeholder="{{ trans(\Config::get('app.theme') . '-app.login_register.contraseña') }}" type="password"
										name="password" maxlength="20">
								</div>
								<p><a onclick="cerrarLogin();" class="c_bordered" data-ref="{{ \Routing::slug('password_recovery') }}"
										id="p_recovery"
										data-title="{{ trans(\Config::get('app.theme') . '-app.login_register.forgotten_pass_question') }}"
										href="javascript:;" data-toggle="modal"
										data-target="#modalAjax">{{ trans(\Config::get('app.theme') . '-app.login_register.forgotten_pass_question') }}</a>
								</p>
								<h5 class="message-error-log text-danger"></h5>
								</p>
								<button id="accerder-user" class="btn btn-login-desktop"
									type="button">{{ trans(\Config::get('app.theme') . '-app.login_register.acceder') }}</button>
							</form>
							<a class="btn-register-modal"
								href="{{ \Routing::translateSeo('login') }}">{{ trans(\Config::get('app.theme') . '-app.login_register.register') }}</a>
						</div>
					</div>

					<div class="search-item flag-header" style="padding: 0">

						<?php foreach(Config::get('app.locales') as $key => $value) { ?>
						<div>

							<div>
								<a title="<?= trans(\Config::get('app.theme') . '-app.head.language_' . $key) ?>" href="/<?= $key ?>">
									<img class="img-responsive" src="/themes/{{ \Config::get('app.theme') }}/assets/img/flag_<?= $key ?>.png"
										width="30px" />
								</a>
							</div>

						</div>
						<?php } ?>
					</div>

					<div class="menu-responsive-btn hidden-lg">
						<button id="btnResponsive" type="button" class="navbar-toggle collapsed" data-toggle="collapse"
							data-target="#navbar" aria-expanded="false" aria-controls="navbar">

							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>


					<div class="lang-item">
						@if (\Config::get('app.enable_language_selector'))
							<select id="selectorIdioma" actuallang="/{{ \App::getLocale() }}/" name="idioma" class="form-control"
								style="width:100%; height:100%; font-size:16px;padding: 0;border: 0; -moz-appearance: none;background-position: right 50%;background-repeat: no-repeat;background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAVCAYAAACpF6WWAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTM4IDc5LjE1OTgyNCwgMjAxNi8wOS8xNC0wMTowOTowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QzczMTRFODdCMEM2MTFFNzgwNTdDMDU0RjJCRTNGN0QiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QzczMTRFODhCMEM2MTFFNzgwNTdDMDU0RjJCRTNGN0QiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpDNzMxNEU4NUIwQzYxMUU3ODA1N0MwNTRGMkJFM0Y3RCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpDNzMxNEU4NkIwQzYxMUU3ODA1N0MwNTRGMkJFM0Y3RCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnHXpV0AAACBSURBVHjaYvz//z8DtQETAw3AqKHUByy4JPz8/Ig2ZNOmTQPv/UNA7IZF3AmIT5Nr6EwgXgfE5khi5lCxGSSHKRQsBWJBIN4JxLZADMp+m4G4BYjnkmsoCEwBYmGowX+hhvWQFftooBHqYlYgriQ7SWEBBQOa+BlHi74RbChAgAEAcGMXwkehP00AAAAASUVORK5CYII=');    -webkit-appearance: none;">
								<option value="es">ES</option>
								<option value="en">EN</option>
								<?php /*<option value="es"><?= trans(\Config::get('app.theme').'-app.head.language_es') ?> ?></option>
								<option value="en"><?= trans(\Config::get('app.theme') . '-app.head.language_en') ?></option> */?>
							</select>
						@elseif(\Config::get('app.google_translate'))
							<div class="google_translate">
								<div id="google_translate_element"></div>
							</div>
							<script type="text/javascript">
								function googleTranslateElementInit() {
									new google.translate.TranslateElement({
										pageLanguage: 'es',
										includedLanguages: 'es,bg,cs,da,de,el,et,fi,fr,ga,hr,hu,it,lt,lv,mt,nl,pl,pt,ro,ru,sk,sl,sv,tr',
										layout: google.translate.TranslateElement.InlineLayout.SIMPLE
									}, 'google_translate_element');
								}
							</script>
							<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
							</script>
						@endif
					</div>


				</div>

			</div>
		</div>
	</div>

</header>


<div id="menuResponsive" class="hidden-lg">
	<div class="me">
		<a id="btnResponsiveClose" title="Cerrar" href="javascript:;">
			<img src="/themes/{{ \Config::get('app.theme') }}/assets/img/shape.png" alt="Cerrar">
		</a>
	</div>
	<div class="clearfix"></div>
	<ul class="nav navbar-nav navbar-right navbar-responsive">
		<li><a title="{{ trans(\Config::get('app.theme') . '-app.home.home') }}"
				href="{{ $lang == 'es' ? url('/es') : url('/en') }}">{{ trans(\Config::get('app.theme') . '-app.home.home') }}</a>
		</li>
		<?php

		$subastaObj = new \App\Models\Subasta();
		$has_subasta = $subastaObj->auctionList('S', 'W');
		if (empty($has_subasta) && Session::get('user.admin')) {
		    $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'W'));
		}

		?>
		@if (!empty($has_subasta))
			<li><a
					href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme') . '-app.foot.auctions') }}</a>
			</li>
		@endif
		<?php
		$has_subasta = $subastaObj->auctionList('H');
		?>
		@if (!empty($has_subasta))
			<li><a
					href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme') . '-app.foot.historico') }}</a>
			</li>
		@endif
		<?php
		$has_subasta = $subastaObj->auctionList('S', 'O');
		if (empty($has_subasta) && Session::get('user.admin')) {
		    $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'O'));
		}
		?>
		@if (!empty($has_subasta))
			<li><a
					href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme') . '-app.foot.online_auction') }}</a>
			</li>
		@endif
		<?php
		$has_subasta = $subastaObj->auctionList('S', 'V');
		if (empty($has_subasta) && Session::get('user.admin')) {
		    $has_subasta = array_merge($has_subasta, $subastaObj->auctionList('A', 'V'));
		}
		?>
		@if (!empty($has_subasta))
			<li><a
					href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme') . '-app.foot.direct_sale') }}</a>
			</li>
		@endif

		<li class="open-menu-especial" style="position: relative">
			<a>{{ trans(\Config::get('app.theme') . '-app.lot.categories') }} <i class="fa fa-caret-down"></i></a>
			<div class="menu-especial">
				@foreach ($categories as $category)
					<p>
						<a href='{{ route('department', ['text' => $category['key_ortsec0']]) }}'>
							{{ $category['des_ortsec0'] }}
						</a>
					</p>
				@endforeach

			</div>
		</li>

		<li>
			<a title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}"
				href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.about_us') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.about_us') }}</a>
		</li>

		<li>
			<a title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}"
				href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.services') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.services') }}</a>
		</li>
		<li>
			<a title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}"
				href="<?= \Routing::translateSeo('valoracion-articulos') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.valorar_producto') }}</a>
		</li>

		<li><a title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}"
				href="<?= \Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.contact') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}</a>
		</li>

	</ul>
</div>
<script>
	var ventana_ancho = $(window).width();
	if (ventana_ancho <= '1200') {
		$(".google_translate2").html($(".google_translate1").html());
		$(".google_translate1").html('');
	}


	$('.search-icon-img').click(function() {
		$('.search-input').toggle()
		$('.search-icon').toggle()
		$('.cancel-icon').toggle()
	})
</script>
