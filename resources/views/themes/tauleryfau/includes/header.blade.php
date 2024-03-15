@php
use App\libs\TradLib;

$lang = Config::get('app.locale');
$flagsLanguage = [
    'es' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALCAMAAABBPP0LAAAAflBMVEX/AAD9AAD3AADxAADrAAD/eXn9bGz8YWH8WVn6UVH5SEj5Pz/3NDT0Kir9/QD+/nL+/lT18lDt4Uf6+j/39zD39yf19R3n5wDxflXsZ1Pt4Y3x8zr0wbLs1NXz8xPj4wD37t3jmkvsUU/Bz6nrykm3vJ72IiL0FBTyDAvhAABEt4UZAAAAX0lEQVR4AQXBQUrFQBBAwXqTDkYE94Jb73+qfwVRcYxVQRBRToiUfoaVpGTrtdS9SO0Z9FR9lVy/g5c99+dKl30N5uxPuviexXEc9/msC7TOkd4kHu/Dlh4itCJ8AP4B0w4Qwmm7CFQAAAAASUVORK5CYII=',
    'en' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALCAMAAABBPP0LAAAAt1BMVEWSmb66z+18msdig8La3u+tYX9IaLc7W7BagbmcUW+kqMr/q6n+//+hsNv/lIr/jIGMnNLJyOP9/fyQttT/wb3/////aWn+YWF5kNT0oqz0i4ueqtIZNJjhvt/8gn//WVr/6+rN1+o9RKZwgcMPJpX/VFT9UEn+RUX8Ozv2Ly+FGzdYZrfU1e/8LS/lQkG/mbVUX60AE231hHtcdMb0mp3qYFTFwNu3w9prcqSURGNDaaIUMX5FNW5wYt7AAAAAjklEQVR4AR3HNUJEMQCGwf+L8RR36ajR+1+CEuvRdd8kK9MNAiRQNgJmVDAt1yM6kSzYVJUsPNssAk5N7ZFKjVNFAY4co6TAOI+kyQm+LFUEBEKKzuWUNB7rSH/rSnvOulOGk+QlXTBqMIrfYX4tSe2nP3iRa/KNK7uTmWJ5a9+erZ3d+18od4ytiZdvZyuKWy8o3UpTVAAAAABJRU5ErkJggg==',
];

$urlToOtherLanguage = TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), $lang, $lang == 'es' ? 'en' : 'es');

function wpLink($code) {
	$wpDomain = 'https://www.tauleryfau.com/';
	return $wpDomain . trans(config('app.theme')."-app.links.$code");
}
@endphp
<header>

    {{-- header mobile --}}
    <div class="header-responsive hidden-desktop d-flex" style="height: auto">

		<div style="flex: 3" class="d-flex align-items-center">
			<a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
				<img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo-footer.png"
				alt="{{ \Config::get('app.name') }}" style="width: 300px">
			</a>
		</div>
		<div style="flex: 2">
			<div class="hamburguer d-flex justify-content-end">
				<i class="fa fa-bars"></i>

				<svg class="close-menu close-dims" style="display: none">
					<svg viewBox="0 0 43 43" id="close" xmlns="https://www.w3.org/2000/svg" width="100%" height="100%">
						<path fill-rule="evenodd"
							d="M42.997 5.724L26.546 21.511l16.355 15.765-4.126 5.728L21.5 26.353 4.148 43.004.003 37.276l16.451-15.787L.099 5.724 4.225-.004 21.5 16.647 38.852-.004l4.145 5.728z">
						</path>
					</svg>
				</svg>

			</div>
		</div>



    </div>

	{{-- hedader descktop --}}
    <div class="container header-container hidden-mobile hidden-tablet">

        <div class="row h-100 header-wrapper d-flex align-items-center">

            <div class="logo" style="flex:1">
                <a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
                    <img class="img-responsive" width="250" height="40"
                        src="/themes/{{ $theme }}/assets/img/logo-web.png"
                        alt="{{ \Config::get('app.name') }}">
                </a>
            </div>

            <div class="session-language p-1" style="flex:1">

                <div class="session">
                    <ul class="panel-principal flex">
                        @if (!Session::has('user'))
                            <li class="prueba session-start">
                                <a title="<?= trans($theme . '-app.login_register.login') ?>"
                                    class="btn btn-color flex valign" data-toggle="modal"
                                    data-target="#modalLogin"><?= trans($theme . '-app.login_register.login') ?></a>
                            </li>
                        @else
                            <li class="prueba myAccount">
                                <a href="{{ \Routing::slug('user/panel/orders') }}"
                                    class="btn btn-color btn-account flex">{{ trans($theme . '-app.login_register.my_panel') }}</a>
                            </li>
                            @if (Session::get('user.admin'))
                                <li class="prueba admin">
                                    <a class="btn btn-color" href="/admin" target="_blank">
                                        {{ trans($theme . '-app.login_register.admin') }}</a>
                                </li>
                            @endif

                        @endif
                    </ul>
                </div>


                <div class="lenguaje">
                    <div class="selector" onclick="javascript:$('#selector_lenguaje').toggle();">

                        <img src="{{ $flagsLanguage[config('app.locale')] }}"
                            alt="{{ \Config::get('app.locales')[\Config::get('app.locale')] }}" width="16"
                            height="11" style="width: 16px; height: 11px;">
                        {{ \Config::get('app.locales')[\Config::get('app.locale')] }}
                        <i class="fa fa-sort-down"></i>
                    </div>

                    <div id="selector_lenguaje">

                        @foreach (Config::get('app.locales') as $key => $value)
                            @if ($key != \Config::get('app.locale'))
                                <a title="{{ trans("$theme-app.head.language_es") }}"
                                    href="{{ "/$key" . $urlToOtherLanguage }}">

                                    <div class="d-flex align-items-center justify-content-center">
                                        <img src="{{ $flagsLanguage[$key] }}" alt="{{ $key }}" width="16"
                                            height="11" style="width: 16px; height: 11px;">
                                        {{ \Config::get('app.locales')[$key] }}
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>


        </div>

    </div>
</header>

{{-- nav enlaces --}}
<nav class="menu top-bar">

    <div class="container nav-container p-0">

        <div class="nav navbar">
            <ul class="flex valign">

				<div class="hidden-desktop w-100">

					@if (!Session::has('user'))
					<li>
						<i class="fa fa-user-circle"></i>
						<a data-toggle="modal" data-target="#modalLogin">
							{{ trans("$theme-app.login_register.login") }}
						</a>
					</li>
					@elseif(Session::has('user'))
					<li>
						<i class="fa fa-user-circle"></i>
						<a href="{{ \Routing::slug('user/panel/orders') }}">
							{{ trans("$theme-app.login_register.my_panel") }}
						</a>
					</li>
					@endif

					@if (Session::get('user.admin'))
					<li>
						<i class="fab fa-buysellads"></i>
						<a href="/admin">
							{{ trans("$theme-app.login_register.admin") }}
						</a>
					</li>
					@endif

        		</div>


                <li>
                    <a title="{{ trans($theme . '-app.home.home') }}"
                        href="{{ wpLink('wp_home') }}"><i class="icon_house"></i></a>
                </li>

                <li class="auctions">
                    <a
                        href="{{ wpLink('wp_auctions') }}">{{ trans($theme . '-app.foot.auctions') }}</a>
                </li>

				<li>
                    <a href="{{ wpLink('wp_calendar') }}">{{ trans($theme . '-app.services.calendar') }}
                        <span class="sub-arrow"><i class="fas fa-caret-down"></i></span>
                    </a>

					{{-- enlaces desplegables en escritorio --}}
                    <div id="" class="menu_desp hidden-xs hidden-sm">
                        <a href="{{ wpLink('wp_calendar') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.subastas.next_auctions") }}</a>

						<a href="{{ wpLink('wp_events') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.events") }}</a>
                    </div>
                </li>

				{{-- enlaces desplegables en móvil --}}
				<div id="" class="menu_desp-xs hidden-md hidden-lg">
					<li>
						<a href="{{ wpLink('wp_calendar') }}"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">{{ trans("$theme-app.subastas.next_auctions") }}</a>
					</li>
					<li>
						<a href="{{ wpLink('wp_events') }}"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">{{ trans("$theme-app.foot.events") }}</a>
					</li>
				</div>

                <li>
                    <a title="{{ trans($theme . '-app.foot.how_to_sell') }}"
                        href="{{ wpLink('wp_sell_coins') }}">{{ trans($theme . '-app.foot.how_to_sell') }}</a>
                </li>

                <li>
                    <a title="{{ trans($theme . '-app.foot.how_to_buy') }}" href="{{ wpLink('wp_buy_coins') }}">{{ trans($theme . '-app.foot.how_to_buy') }}</a>
                </li>

                <li>
                    <a href="{{ wpLink('wp_services') }}">{{ trans($theme . '-app.services.title') }}
                        <span class="sub-arrow"><i class="fas fa-caret-down"></i></span>
                    </a>

					{{-- enlaces desplegables en escritorio --}}
                    <div id="" class="menu_desp hidden-xs hidden-sm">
                        <a href="{{ wpLink('wp_valuations') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.valuations") }}</a>

						<a href="{{ wpLink('wp_photography') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.photography") }}</a>

						<a href="{{ wpLink('wp_coin_grading') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.coin_grading") }}</a>
                    </div>
                </li>

				{{-- enlaces desplegables en móvil --}}
				<div id="" class="menu_desp-xs hidden-md hidden-lg">
					<li>
						<a href="{{ wpLink('wp_valuations') }}"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">{{ trans("$theme-app.foot.valuations") }}</a>
					</li>
					<li>
						<a href="{{ wpLink('wp_photography') }}"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">{{ trans("$theme-app.foot.photography") }}</a>
					</li>
					<li>
						<a href="{{ wpLink('wp_coin_grading') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.coin_grading") }}</a>
					</li>
				</div>


				@php
					# TEMPORALMENTE OCULTO POR PETICIÓN DE TAULER Y FAU
				@endphp
				{{-- <li>
                    <a href="{{ wpLink('wp_onzas_macuquinas') }}">{{ trans($theme . '-app.foot.onzas_macuquinas') }}
                        <span class="sub-arrow"><i class="fas fa-caret-down"></i></span>
                    </a>

					@php
						# enlaces desplegables en escritorio
					@endphp
                    <div id="" class="menu_desp hidden-xs hidden-sm">
                        <a href="{{ wpLink('wp_onzas_macuquinas') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.database_online") }}</a>

						<a href="{{ wpLink('wp_catalogue_pdf') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.catalogue_pdf") }}</a>
                    </div>
                </li> --}}

				{{-- enlaces desplegables en móvil --}}
				{{-- <div id="" class="menu_desp-xs hidden-md hidden-lg">
					<li>
						<a href="{{ wpLink('wp_onzas_macuquinas') }}"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">{{ trans("$theme-app.foot.database_online") }}</a>
					</li>
					<li>
						<a href="{{ wpLink('wp_catalogue_pdf') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.catalogue_pdf") }}</a>
					</li>
				</div> --}}

                <li>
                    <a href="{{ wpLink('wp_about_us') }}">{{ trans("$theme-app.foot.about_us") }}
                        <span class="sub-arrow"><i class="fas fa-caret-down"></i></span>
                    </a>

					{{-- enlaces desplegables en escritorio --}}
                    <div id="" class="menu_desp hidden-xs hidden-sm">
                        <a href="{{ wpLink('wp_about_us') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.about_us") }}</a>

						<a href="{{ wpLink('wp_faq') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.faq") }}</a>

						<a href="{{ wpLink('wp_term_condition') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.auctions_conditions") }}</a>
                    </div>
                </li>

				{{-- enlaces desplegables en móvil --}}
				<div id="" class="menu_desp-xs hidden-md hidden-lg">
					<li>
						<a href="{{ wpLink('wp_about_us') }}"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">{{ trans("$theme-app.foot.about_us") }}</a>

					</li>
					<li>
						<a href="{{ wpLink('wp_faq') }}"
						class="color-letter flex-display link-header justify-center align-items-center"
						style="cursor: pointer;">{{ trans("$theme-app.foot.faq") }}</a>
					</li>

					<li>
						<a href="{{ wpLink('wp_term_condition') }}"
                            class="color-letter flex-display link-header justify-center align-items-center"
                            style="cursor: pointer;">{{ trans("$theme-app.foot.auctions_conditions") }}</a>
					</li>
				</div>

                <li>
                    <a title="{{ trans($theme . '-app.foot.contact') }}"
                        href="{{ wpLink('wp_contact') }}">{{ trans($theme . '-app.foot.contact') }}</a>
                </li>

				<div class="hidden-lg hidden-md w-100">

					<li >
						<a href="#">
							<img src="{{ $flagsLanguage[config('app.locale')] }}" alt="{{ \Config::get('app.locales')[\Config::get('app.locale')] }}" width="16"
                            	height="11" style="width: 16px; height: 11px; margin-right: 0.3em">

								{{ \Config::get('app.locales')[\Config::get('app.locale')] }}

								<span class="sub-arrow"><i class="fas fa-caret-down"></i></span>
						</a>
					</li>


					{{-- enlaces desplegables en móvil --}}
					<div id="" class="menu_desp-xs">
						@foreach (Config::get('app.locales') as $key => $value)
							@if ($key != \Config::get('app.locale'))
							<li>
								<a title="{{ trans($theme . '-app.head.language_' . $key) }}"
									href="{{ "/$key" . $urlToOtherLanguage }}">

									<img src="{{ $flagsLanguage[$key] }}" alt="{{ \Config::get('app.locales')[$key] }}" width="16"
									height="11" style="width: 16px; height: 11px; margin-right: 0.3em">

									{{ \Config::get('app.locales')[$key] }}
								</a>
							</li>
							@endif
						@endforeach
					</div>

				</div>

            </ul>
        </div>
    </div>
</nav>
