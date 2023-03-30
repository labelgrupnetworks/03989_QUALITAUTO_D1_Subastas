@php
use App\libs\TradLib as TradLib;
$lang = config('app.locale');
$registration_disabled = Config::get('app.registration_disabled');

$categories = (new App\Models\V5\FgOrtsec0())->getAllFgOrtsec0()->whereNotNull('key_ortsec0')->get()->toarray();
$searchAction = config('app.gridLots', false) == "new" ? route('allCategories') : \Routing::slug('busqueda');
$pageName = Route::currentRouteName();
@endphp

<header>

	<div class="first-header-wrapper">
		<div class="container py-1 bk-gray">
			<div class="d-flex justify-content-end align-items-center">

				@if(count(Config::get('app.locales')) > 1)
				{{-- Con enlaces --}}
				<div class="lang-wrapper d-flex gap-1">
					@foreach(Config::get('app.locales') as $key => $value)
					@php
						$route = '';
						if($key != $lang){
							$route = "/$key". TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), App::getLocale(), $key);
						}
					@endphp

					<a @class(['selected' => $key == $lang]) translate="no" title="{{ trans("$theme-app.head.language_$key") }}" href="{{$route}}">
						<span translate="no">{{ $key }}</span>
					</a>

					@if($loop->first)
					<span>|</span>
					@endif
				@endforeach
				</div>
				@endif

				<ul class="list-unstyled d-flex m-0 ps-4 gap-2">
					<li>
						<a href="{{ Config::get('app.facebook') }}" target="_blank">
							<svg class="bi" width="18" height="18" fill="currentColor">
								<use xlink:href="/bootstrap-icons.svg#facebook"></use>
							</svg>
						</a>
					</li>
					<li>
						<a href="{{ Config::get('app.twitter') }}" target="_blank">
							<svg class="bi" width="18" height="18" fill="currentColor">
								<use xlink:href="/bootstrap-icons.svg#twitter"></use>
							</svg>
						</a>
					</li>
					<li>
						<a href="{{ Config::get('app.instagram') }}" target="_blank">
							<svg class="bi" width="18" height="18" fill="currentColor">
								<use xlink:href="/bootstrap-icons.svg#instagram"></use>
							</svg>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<nav class="navbar navbar-expand-xxl">
		<div class="container-fluid gap-xxl-3">

			<img class="img-fluid" width="80" src="/themes/{{$theme}}/assets/img/logo_numismatica.png"  alt="numismática">

			<a class="navbar-brand crop-img" href="/{{$lang}}" title="{{(\Config::get( 'app.name' ))}}">
				<img class="img-fluid" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
			</a>

			<button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
				data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="navbar-collapse collapse align-self-stretch align-items-center" id="navbarHeader" style="">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold align-self-stretch align-items-xxl-center w-100 justify-content-evenly">

					<li class="nav-item">
						<a class="nav-link" title="{{ trans("$theme-app.home.home")}}" href="/{{$lang}}"><span>{{ trans("$theme-app.home.home")}}</span></a>
					</li>

					@if(($global['subastas']->has('S') && $global['subastas']['S']->has('W')) || $global['subastas']->has('H'))
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						  {{ trans("$theme-app.subastas.auctions") }}
						</a>
						<ul class="dropdown-menu">
							@if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
							<li><a class="dropdown-item" href="{{ \Routing::translateSeo('presenciales') }}">{{ trans("$theme-app.subastas.auctions") }}</a></li>
							@endif

							@if($global['subastas']->has('H'))
							<li><a class="dropdown-item" href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans("$theme-app.foot.historico") }}</a></li>
							@endif
						</ul>
					</li>
					@endif


					<li class="nav-item">
						<a class="nav-link" href="{{ \Routing::translateSeo('venta-directa') }}">
							<span style="white-space: nowrap;">{{ trans($theme.'-app.foot.direct_sale')}}</span>
						</a>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						  {{ trans("$theme-app.login_register.empresa") }}
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.about_us") }}">{{ trans("$theme-app.foot.about_us") }}</a></li>
							<li><a class="dropdown-item" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.team") }}">{{ trans("$theme-app.foot.team") }}</a></li>
							<li><a class="dropdown-item" href="{{ Routing::translateSeo('blog') . trans("$theme-app.links.press") }}">{{ trans("$theme-app.foot.press") }}</a></li>
							<li><a class="dropdown-item" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.ethical_code") }}">{{ trans("$theme-app.foot.ethical_code") }}</a></li>
							<li><a class="dropdown-item" href="{{ Routing::translateSeo('mosaic-blog') }}">{{ trans("$theme-app.blog.museum-pieces") }}</a></li>
							<li><a class="dropdown-item" href="{{ Routing::translateSeo('events') }}">{{ trans("$theme-app.blog.events") }}</a></li>
						</ul>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							{{ trans($theme.'-app.foot.service') }}
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.laboratory") }}">{{ trans("$theme-app.foot.laboratory") }}</a></li>
							<li><a class="dropdown-item" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.legal_advice") }}">{{ trans("$theme-app.foot.legal_advice") }}</a></li>
							<li><a class="dropdown-item" href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale')]) }}">{{ trans("$theme-app.home.free-valuations") }}</a></li>
						</ul>
					</li>
					<li class="nav-item">
						<a class='nav-link' href="{{ Routing::translateSeo('contacto') }}"><span>{{ trans($theme.'-app.foot.contact') }}</span></a>
					</li>
					<li class="nav-item">
						<a class='nav-link' href="{{ trans("$theme-app.links.blog") }}" target="_blank"><span>{{ trans($theme.'-app.home.blog') }}</span></a>
					</li>

					<li class="nav-item dropdown">
						@if(!Session::has('user'))
						<button class="btn border-0 btn_login">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="29" height="29" viewBox="0 0 29 29">
								<defs>
								  <clipPath id="clip-path">
									<rect id="Rectángulo_19" data-name="Rectángulo 19" width="29" height="29" fill="#b9b13c"/>
								  </clipPath>
								</defs>
								<g id="Grupo_25" data-name="Grupo 25" clip-path="url(#clip-path)">
								  <path id="Trazado_1" data-name="Trazado 1" d="M14.5,29A14.5,14.5,0,1,1,29,14.5,14.517,14.517,0,0,1,14.5,29M5.81,24.823a13.475,13.475,0,0,0,17.378,0l-.6-1.543a8.679,8.679,0,0,0-16.178,0ZM14.5,16.75a9.619,9.619,0,0,1,9.021,6.17l.46,1.183a13.5,13.5,0,1,0-18.961,0l.459-1.181A9.619,9.619,0,0,1,14.5,16.75m0-1.875a4.656,4.656,0,1,1,4.656-4.656A4.661,4.661,0,0,1,14.5,14.875m0-8.312a3.656,3.656,0,1,0,3.656,3.656A3.66,3.66,0,0,0,14.5,6.563" fill="#b9b13c"/>
								</g>
							  </svg>
						</button>
						@else
						<button class="btn border-0 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="29" height="29" viewBox="0 0 29 29">
								<defs>
								  <clipPath id="clip-path">
									<rect id="Rectángulo_19" data-name="Rectángulo 19" width="29" height="29" fill="#b9b13c"/>
								  </clipPath>
								</defs>
								<g id="Grupo_25" data-name="Grupo 25" clip-path="url(#clip-path)">
								  <path id="Trazado_1" data-name="Trazado 1" d="M14.5,29A14.5,14.5,0,1,1,29,14.5,14.517,14.517,0,0,1,14.5,29M5.81,24.823a13.475,13.475,0,0,0,17.378,0l-.6-1.543a8.679,8.679,0,0,0-16.178,0ZM14.5,16.75a9.619,9.619,0,0,1,9.021,6.17l.46,1.183a13.5,13.5,0,1,0-18.961,0l.459-1.181A9.619,9.619,0,0,1,14.5,16.75m0-1.875a4.656,4.656,0,1,1,4.656-4.656A4.661,4.661,0,0,1,14.5,14.875m0-8.312a3.656,3.656,0,1,0,3.656,3.656A3.66,3.66,0,0,0,14.5,6.563" fill="#b9b13c"/>
								</g>
							  </svg>
						</button>
						@endif

						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="{{ Routing::slug('user/panel/orders') }}">{{ trans("$theme-app.login_register.my_panel") }}</a></li>
							@if(Session::get('user.admin'))
							<li><a class="dropdown-item" href="/admin" target="_blank">{{ trans("$theme-app.login_register.admin") }}</a></li>
							@endif
							<li><a class="dropdown-item" href="{{ Routing::slug('logout') }}">{{ trans("$theme-app.login_register.logout") }}</a></li>
						</ul>
					</li>
				</ul>

			</div>
		</div>
	</nav>

</header>


<div class="login_desktop container-fluid" style="display: none" >
	<div class="h-100 d-flex justify-content-center align-content-center">
		<div class="login_desktop_content m-auto">
			<div class="only-login bg-white p-5 position-relative">
				<div class="login-content-form">
					<img class="closedd" role="button" src="/themes/{{$theme}}/assets/img/shape.png" alt="Close">

					<p class="login_desktop_title h1">{{ trans($theme.'-app.login_register.login') }}</p>

					<form data-toggle="validator" id="accerder-user-form" class="d-flex align-items-center justify-content-center flex-column py-4">
						@csrf

						<div class="input-group mb-3">
							<span class="input-group-text">
								<svg class="bi" width="16" height="16" fill="currentColor">
									<use xlink:href="/bootstrap-icons.svg#person-fill"></use>
								</svg>
							</span>
							<input class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email">
						</div>

						<div class="input-group mb-2">
							<span class="input-group-text">
								<svg class="bi" width="16" height="16" fill="currentColor">
									<use xlink:href="/bootstrap-icons.svg#key-fill"></use>
								</svg>
							</span>
							<input class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20" autocomplete="off">
							<span class="input-group-text view_password">
								<img class="eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
							</span>
						</div>

						<small class="align-self-end">
							<a onclick="cerrarLogin();" class="c_bordered" data-ref="{{ \Routing::slug('password_recovery') }}"
								id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-bs-toggle="modal" data-bs-target="#modalAjax">
								{{ trans($theme.'-app.login_register.forgotten_pass_question')}}
							</a>
						</small>

						<p><span class="message-error-log text-danger seo_h5"></span></p>

						<button id="accerder-user" class="btn btn-lb-primary w-100 mt-4" type="submit">
							<span class="text">{{ trans($theme.'-app.login_register.acceder') }}</span>
							<div class="spinner spinner-1 m-auto"></div>
						</button>

					</form>

					<div class="login-separator" data-content="o"></div>

					<p class="text-center mb-2">{{ trans($theme.'-app.login_register.not_account') }}</p>

					<div class="create-account-link">
						@if(empty($registration_disabled))
						<a class="btn btn-outline-lb-primary w-100" title="{{ trans($theme.'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">
							{{ trans($theme.'-app.login_register.register') }}
						</a>
						@else
						<p class="text-center" style="color: darkred;">{{ trans($theme.'-app.login_register.registration_disabled') }}</p>
						@endif
					</div>
				</div>
			</div>
		</div>

    </div>
</div>
