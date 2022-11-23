<?php

use App\libs\TradLib as TradLib;
use App\Models\V5\FgCsub;
use App\Http\Controllers\V5\CartController;

    $lang = Config::get('app.locale');

    $registration_disabled = Config::get('app.registration_disabled');
    $fullname = Session::get('user.name');
    if(strpos($fullname, ',')){
        $str = explode(",", $fullname);
        $name = $str[1];
    }else{
        $name = $fullname;
	}

	$cod_cli = Session::get('user.cod');
	$sells = null;

	if($cod_cli){
		$cartController = new CartController();
		$sells =$cartController->getNumElements();
		//$sells = FgCsub::getAdjudicacionesPendientesCount($cod_cli, ['Tienda1', 'Tienda2', 'Tienda3', 'Tienda4', 'T14']);

	}

?>

<div class="lang-selection hidden-xs hidden-sm">
	<div class="container-fluid">
		<div class="row">



			<div class="col-xs-12 text-right d-flex justify-content-flex-end social-lang-selection">

				<?php /*Idioma nativo
				<?php foreach(Config::get('app.locales') as $key => $value) { ?>
				<ul class="ul-format list-lang d-inline-flex">

					@if(\App::getLocale() != $key)
					$ruta = TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), \App::getLocale(), $key);

					?>
					<li>
						<a translate="no" title="<?= trans(\Config::get('app.theme').'-app.head.language_es') ?>"
							class="link-lang color-letter" href="/<?=$key . $ruta;?>">
							<span translate="no">{{ trans(\Config::get('app.theme').'-app.home.' . $key)}}</span>
						</a>
					</li>
					@else
					<li>
						<a translate="no" title="<?= trans(\Config::get('app.theme').'-app.head.language_es') ?>"
							class="link-lang active color-letter">
							<span translate="no">{{ trans(\Config::get('app.theme').'-app.home.' . $key)}}</span>
						</a>
					</li>
					@endif
				</ul>
				<?php } ?>
				*/ ?>


			</div>
		</div>
	</div>
</div>

<div>
	<div class="logo-header">
		<img class="logo-company img-responsive numismatic-logo" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_numismatica.png"  alt="numismática">

		<a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
			<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.jpg"
				alt="{{(\Config::get( 'app.name' ))}}">
		</a>

		<div class="header-enlaces">

			<div class="headers-enlaces-wrapper hidden-xs hidden-sm">
				<div class="google_translate d-flex align-items-center">
					<ul class="m-0">
					<a href="/?#googtrans(es|es)" class="lang-selectors lang-es"><p translate="no">{{ trans(\Config::get('app.theme').'-app.home.es') }}</p></a>
						<span>|</span>
						<a href="/?#googtrans(es|en)" class="lang-selectors"><p translate="no">{{ trans(\Config::get('app.theme').'-app.home.en') }}</p></a>
					</ul>
				</div>

				<div class="google_translate hidden hidden-xs hidden-md">
					<div id="google_translate_element1" class="d-flex align-items-end" style="hei"></div>
				</div>
				<script type="text/javascript"
					src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
				<script type="text/javascript">
					var actualLang = '<?= $lang ?>';
						$(window).bind("load", () => {
							verifyLang(actualLang);
						})

						function googleTranslateElementInit() {
						new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'es,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element1');
						}
				</script>

				<div class="social-links mt-1">
					{{-- <span class="social-links-title">{{trans(\Config::get('app.theme').'-app.foot.follow_us')}}</span> --}}
					<a class="social-link color-letter" href="{{\Config::get('app.facebook')}}"
						target="_blank"><i class="fab fa-2x fa-facebook-square"></i></a>
					&nbsp;
					<a class="social-link color-letter"><i class="fab fa-2x fa-twitter-square"></i></a>
					&nbsp;
					<a class="social-link color-letter" href="{{\Config::get('app.instagram')}}"
						target="_blank"><i class="fab fa-2x fa-instagram"></i></a>
				</div>
			</div>
		</div>
	</div>
</div>

<header>
	<nav class="menu-header">
		<div class="menu-responsive hidden-lg">
			<div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter ">
				{{ trans(\Config::get('app.theme').'-app.head.menu') }}</div>
		</div>
		<div class="" style="align-self: center"></div>
		<div class="menu-principal">

			<ul class="menu-principal-content d-flex justify-content-center align-items-center position-left">
				<span role="button"
					class="close-menu-reponsive hidden-lg">{{ trans(\Config::get('app.theme').'-app.head.close') }}</span>
				<?php //   <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li> ?>
				<li class="flex-display">
					<a class="color-letter flex-display link-header justify-center align-items-center"
						title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">
						<span>{{ trans(\Config::get('app.theme').'-app.home.home')}}</span>
					</a>
				</li>

				<li>
					<a id="js-menu-subastas"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">
						{{ trans(\Config::get('app.theme').'-app.subastas.auctions')}}
						&nbsp;
						<span class="caret"></span>
					</a>

					<div id="menu_desp_subastas" class="menu_desp">

						@php/*SUBASTAS*/@endphp
						@php
						$subastaObj = new \App\Models\Subasta();
						$has_subasta = $subastaObj->auctionList('S', 'W');

						if( empty($has_subasta) && Session::get('user.admin')){
						$has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
						}
						@endphp

						@if(!empty($has_subasta))
						<a href="{{ \Routing::translateSeo('presenciales') }}"
							class="color-letter flex-display link-header justify-center align-items-center">
							{{ trans(\Config::get('app.theme').'-app.subastas.auctions')}}</a>
						</a>
						@endif

						@php /*HISTORICO */ @endphp

						@php
						$has_subasta = $subastaObj->auctionList ('H');
						@endphp

						@if(!empty($has_subasta))
						<a href="{{ \Routing::translateSeo('subastas-historicas') }}"
							class="color-letter flex-display link-header justify-center align-items-center">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a>
						@endif
					</div>
				</li>



				@php /*VENTA DIRECTA */ @endphp
				<?php
				  $has_subasta = $subastaObj->auctionList ('S', 'V');

                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'V'));
                   }
                ?>
				@if(!empty($has_subasta))
				<li class="hidden"><a class="color-letter flex-display link-header justify-center align-items-center"
						href="{{ \Routing::translateSeo('venta-directa') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</span></a>
				</li>
				@endif

				<li>
					<a id="js-menu-empresa"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">
						{{ trans(\Config::get('app.theme').'-app.login_register.empresa')}}
						&nbsp;
						<span class="caret"></span>
					</a>
					<div id="menu_desp_empresa" class="menu_desp">

						<a href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  }}"
							class="color-letter flex-display link-header justify-center align-items-center"
							style="cursor: pointer;">{{ trans(\Config::get('app.theme').'-app.foot.about_us')}}</a>

						<a href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.team')  }}"
							class="color-letter flex-display link-header justify-center align-items-center"
							style="cursor: pointer;">{{ trans(\Config::get('app.theme').'-app.foot.team')}}</a>

						<a href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.press')  }}"
							class="color-letter flex-display link-header justify-center align-items-center"
							style="cursor: pointer;">{{ trans(\Config::get('app.theme').'-app.foot.press')}}</a>

						<a href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.ethical_code')  }}"
							class="color-letter flex-display link-header justify-center align-items-center"
							style="cursor: pointer;">{{ trans(\Config::get('app.theme').'-app.foot.ethical_code')}}</a>

						<a href="{{ Routing::translateSeo('mosaic-blog') }}"
							class="color-letter flex-display link-header justify-center align-items-center"
							style="cursor: pointer;">{{ trans(\Config::get('app.theme').'-app.blog.museum-pieces')}}</a>

						<a href="{{ Routing::translateSeo('events') }}"
							class="color-letter flex-display link-header justify-center align-items-center"
							style="cursor: pointer;">{{ trans(\Config::get('app.theme').'-app.blog.events')}}</a>

					</div>
				</li>

				<li>
					<a class="color-letter d-flex link-header justify-content-center align-items-center"
						title="{{ trans(\Config::get('app.theme').'-app.foot.service')}}"
						href="{{ Routing::translateSeo('servicios-numismatica') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.service')}}</span></a>
				</li>

				<li>
					<a class="color-letter d-flex link-header justify-content-center align-items-center"
						title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}"
						href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
				</li>

				<li>

					<a class="color-letter d-flex link-header justify-content-center align-items-center"
						title="{{ trans(\Config::get('app.theme').'-app.home.blog')}}"
						href="{{ trans("$theme-app.links.blog") }}" target="_blank"><span>{{ trans(\Config::get('app.theme').'-app.home.blog')}}</span></a>
				</li>




			<div class="header-enlaces">

				<div class="headers-enlaces-wrapper hidden-md hidden-lg">
					<div class="google_translate d-flex align-items-center">
						<ul class="m-0">
						<a href="/?#googtrans(es|es)" class="lang-selectors lang-es"><p translate="no">{{ trans(\Config::get('app.theme').'-app.home.es') }}</p></a>
							<span>|</span>
							<a href="/?#googtrans(es|en)" class="lang-selectors"><p translate="no">{{ trans(\Config::get('app.theme').'-app.home.en') }}</p></a>
						</ul>
					</div>

					<div class="google_translate hidden hidden-xs hidden-md">
						<div id="google_translate_element1" class="d-flex align-items-end" style="hei"></div>
					</div>
					<script type="text/javascript"
						src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
					<script type="text/javascript">
						var actualLang = '<?= $lang ?>';
							$(window).bind("load", () => {
								verifyLang(actualLang);
							})

							function googleTranslateElementInit() {
							new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'es,en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element1');
							}
					</script>

					<div class="social-links ">
						{{-- <span class="social-links-title">{{trans(\Config::get('app.theme').'-app.foot.follow_us')}}</span> --}}
						<a class="social-link" href="{{\Config::get('app.facebook')}}"
							target="_blank"><i class="fa fa-2x fa-facebook-square"></i></a>
						&nbsp;
						<a class="social-link"><i class="fa fa-2x fa-twitter-square"></i></a>
						&nbsp;
						<a class="social-link" href="{{\Config::get('app.instagram')}}"
							target="_blank"><i class="fa fa-2x fa-instagram"></i></a>
					</div>
				</div>
			</div>
			</ul>
		</div>

		<?php /*Posibles buscadores: includes.search_inline, includes.search_classic*/ ?>
		<div class="flex-inline align-items-center account-wrapper">
			{{-- @include('includes.search_inline') --}}

			<div class="user-account">
				@if(!Session::has('user'))
				<div class="user-account-login">
					<button class="flex-display justify-center align-items-center btn_login_desktop btn_login button-principal"
						title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>">
						<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
					</button>
				</div>
				@else
				<div class="my-account color-letter-header">
					<div class="d-flex">
						<div class="col-md-3 hidden-xs text-center">
							<img width="25px;" class="logo-company"
								src="/themes/{{\Config::get('app.theme')}}/assets/img/user.png"
								alt="{{(\Config::get( 'app.name' ))}}">
						</div>
						<div class="col-xs-12 col-md-9 text-center user-acount-panel">
							@if(!empty($name))
							<div><b><?= $name ?></b></div>
							@endif
							<span>{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</span>
						</div>
					</div>



					<div class="mega-menu background-body rigth-0">
						<a class="color-letter" href="{{ \Routing::slug('user/panel/orders') }}">
							{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}

						</a>
						@if(Session::get('user.admin'))
						<a class="color-letter" href="/admin" target="_blank">
							{{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a>
						@endif
						<a class="color-letter"
							href="{{ \Routing::slug('logout') }}">{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a>
					</div>
				</div>

				@endif
			</div>
			@if(Session::has('user') && $sells)
			<div class="sells">
				<a href="{{ route('showShoppingCart', ['lang' => config('app.locale')]) }}">
					<i class="fa fa-2x fa-shopping-cart" aria-hidden="true"></i>
					<span class="badge">{{ $sells }}</span>
				</a>
			</div>
			@endif
		</div>
	</nav>
</header>

{{--Buscador plegado para vista mobile, modificado por icono en menu header
<div class="menu-principal-search d-flex align-items-center justify-content-center">
	<form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}"
class="search-component-form flex-inline position-relative">
<div class="form-group">
	<input class="form-control input-custom br-100"
		placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto"
		autocomplete="off" />
</div>
<button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">
	<i class="fas fa-search"></i>
</button>
</form>
</div>
--}}
<div class="login_desktop" style="display: none">
	<div class="login_desktop_content">
		<div class="only-login white-background">
			<div class="login-content-form">
				<img class="closedd" role="button" src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png"
					alt="Close">
				<div class="login_desktop_title">
					<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
				</div>
				<form data-toggle="validator" id="accerder-user-form"
					class="flex-display justify-center align-items-center flex-column">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<div class="input-login-group">
							<i class="fa fa-user"></i>
							<input class="form-control"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.user') }}"
								type="email" name="email" type="text">
						</div>
					</div>
					<div class="form-group ">
						<div class="input-login-group">
							<i class="fa fa-key"></i>
							<input class="form-control"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}"
								type="password" name="password" maxlength="20">
							<img class="view_password eye-password"
								src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
						</div>
					</div>
					<span class="message-error-log text-danger seo_h5"></span></p>
					<div class="pass-login-content">
						<div class="text-center">
							<button id="accerder-user" class="button-principal" type="button">
								<div>{{ trans(\Config::get('app.theme').'-app.login_register.acceder') }}</div>
							</button>
						</div>
						<a onclick="cerrarLogin();" class="c_bordered pass_recovery_login"
							data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery"
							data-title="{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}"
							href="javascript:;" data-toggle="modal"
							data-target="#modalAjax">{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}</a>

					</div>
				</form>
				<div class="login-separator"></div>
				<p class="text-center">{{ trans(\Config::get('app.theme').'-app.login_register.not_account') }}</p>
				<div class="create-account-link">
					@if(empty($registration_disabled))
					<a class="" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}"
						href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a>
					@else
					<p class="text-center" style="color: darkred;">
						{{ trans(\Config::get('app.theme').'-app.login_register.registration_disabled') }}</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

