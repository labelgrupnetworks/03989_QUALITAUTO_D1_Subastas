@php


$lang = config('app.locale');
$registration_disabled = Config::get('app.registration_disabled');

// $categories = (new App\Models\V5\FgOrtsec0())->getAllFgOrtsec0()->whereNotNull('key_ortsec0')->get()->toarray();
$searchAction = config('app.gridLots', false) == "new" ? route('allCategories') : \Routing::slug('busqueda');
$pageName = Route::currentRouteName();
@endphp

<header>

	<div class="first-header-wrapper">
		<div class="container py-1 bk-gray">
			<div class="d-flex justify-content-end align-items-center gap-3">

				@include('components.search', ['classes' => 'me-auto d-none d-xxl-block', 'searchAction' => $searchAction])

				@yield('header-extend-buttons')

				@if(!Session::has('user'))
					<button class="btn btn-sm btn-lb-primary btn_login">{{ trans($theme.'-app.login_register.login') }}</button>
				@else
					<a class="btn btn-sm btn-lb-primary" href="{{ \Routing::slug('user/panel/orders') }}">{{ trans($theme.'-app.login_register.my_panel') }}</a>

					@if(Session::get('user.admin'))
					<a class="btn btn-sm btn-lb-primary" href="/admin"  target = "_blank"> {{ trans($theme.'-app.login_register.admin') }}</a>
					@endif

					<a class="btn btn-sm btn-lb-primary" href="{{ \Routing::slug('logout') }}" >{{ trans($theme.'-app.login_register.logout') }}</a>
				@endif

				@include('includes.header.language_selector')
			</div>
		</div>
	</div>

	<nav class="navbar navbar-expand-xxl">
		<div class="container">
			<a class="navbar-brand" href="/{{$lang}}" title="{{(\Config::get( 'app.name' ))}}">
				<img width="200" class="img-responsive" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
			</a>
			<button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
				data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="navbar-collapse collapse" id="navbarHeader" style="">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold">

					@if (!empty($categories))
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="categoriesHeader" data-bs-toggle="dropdown" aria-expanded="false">{{ trans("$theme-app.lot.categories") }}</a>
						<ul class="dropdown-menu" aria-labelledby="categoriesHeader">
							@foreach ($categories as $category)
								<li>
									<a class="dropdown-item" href="{{ route("category", ["keycategory" => $category["key_ortsec0"]]) }}">{{$category["des_ortsec0"]}}</a>
								</li>
							@endforeach
						</ul>
					</li>
					@endif

					@if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
					<li class="nav-item">
						<a @class(['nav-link', 'lb-text-primary' => $pageName === 'subastas.presenciales'])
							href="{{ route('subastas.presenciales') }}">
							<span>{{ trans($theme.'-app.foot.auctions')}}</span>
						</a>
					</li>
					@endif
					@if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
					<li class="nav-item">
						<a @class(['nav-link', 'lb-text-primary' => $pageName === 'subastas.online'])
							href="{{ route('subastas.online') }}">
							<span>{{ trans($theme.'-app.foot.online_auction') }}</span>
						</a>
					</li>
					@endif
					@if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
					<li class="nav-item">
						<a class="nav-link" href="{{ \Routing::translateSeo('venta-directa') }}">
							<span>{{ trans($theme.'-app.foot.direct_sale')}}</span>
						</a>
					</li>
					@endif
					@if($global['subastas']->has('H'))
					<li class="nav-item">
						<a class="nav-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">
							<span>{{ trans($theme.'-app.foot.historico')}}</span>
						</a>
					</li>
					@endif

					{{-- <li class="nav-item">
						<a @class(['nav-link', 'lb-text-primary' => $pageName === 'calendar']) title="" href="{{ route('calendar') }}"><span>{{ trans($theme.'-app.foot.calendar')}}</span></a>
					</li> --}}
					<li class="nav-item">
						<a @class(['nav-link', 'lb-text-primary' => $pageName === 'valoracion']) title="" href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale')]) }}"><span> {{ trans($theme.'-app.home.free-valuations') }}</span></a>
					</li>
					<li class="nav-item">
						<a @class(['nav-link', 'lb-text-primary' => $pageName === 'contact_page']) title="{{ trans($theme.'-app.foot.contact')}}" href="{{ route('contact_page') }}"><span>{{ trans($theme.'-app.foot.contact')}}</span></a>
					</li>
				</ul>

				@include('components.search', ['classes' => 'me-auto d-xxl-none'])
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
							<input class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email" autocomplete="email">
						</div>

						<div class="input-group mb-0">
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
						<a class="btn btn-outline-lb-secondary w-100" title="{{ trans($theme.'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">
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
