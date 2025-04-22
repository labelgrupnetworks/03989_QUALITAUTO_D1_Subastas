@php

    $lang = config('app.locale');
    $registration_disabled = Config::get('app.registration_disabled');

    $categories = [];//(new App\Models\V5\FgOrtsec0())->getAllFgOrtsec0()->whereNotNull('key_ortsec0')->get()->toarray();
    $searchAction = config('app.gridLots', false) == 'new' ? route('allCategories') : \Routing::slug('busqueda');
    $pageName = Route::currentRouteName();
	$isHome = $pageName === 'home';
@endphp

<header>

    <div class="first-header-wrapper d-none d-md-block">
        <div class="container-fluid gx-lg-5 py-1">
            <div class="d-flex align-items-center gap-3">

				@if($isHome)
                <h1 class="me-auto" style="font-size: 1em; font-family: var(--bs-font-sans-serif); font-weight: inherit;">
					{{ Config::get('app.name') }}
				</h1>
				@else
				<p class="me-auto">
					{{ Config::get('app.name') }}
				</p>
				@endif

                @yield('header-extend-buttons')

                <a href="{{ route('contact_page') }}" title="{{ trans($theme . '-app.foot.contact') }}">
                    {{ trans($theme . '-app.foot.contact') }}
                </a>
                <span class="vertical-separator-line">|</span>

                <a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}"
                    title="{{ trans($theme . '-app.foot.about_us') }}">
                    {{ trans($theme . '-app.foot.about_us') }}
                </a>
                <span class="vertical-separator-line">|</span>

                <a href="{{ Routing::translateSeo(trans("$theme-app.links.faq")) }}"
                    title="{{ trans("$theme-app.foot.faq") }}">
                    Ayuda
                </a>
                <span class="vertical-separator-line">|</span>

                @if (!Session::has('user'))
                    <button class="btn btn-link btn_login">{{ trans($theme . '-app.login_register.login') }}</button>
                @else
                    <a class="btn btn-link"
                        href="{{ \Routing::slug('user/panel/orders') }}">{{ trans($theme . '-app.login_register.my_panel') }}</a>
                    <span class="vertical-separator-line">|</span>

                    @if (Session::get('user.admin'))
                        <a class="btn btn-link" href="/admin" target = "_blank">
                            {{ trans($theme . '-app.login_register.admin') }}</a>
                        <span class="vertical-separator-line">|</span>
                    @endif

                    <a class="btn btn-link"
                        href="{{ \Routing::slug('logout') }}">{{ trans($theme . '-app.login_register.logout') }}</a>
                @endif

                {{-- @include('includes.header.language_selector') --}}
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid gx-lg-5">
            <a class="navbar-brand" href="/{{ $lang }}" title="{{ \Config::get('app.name') }}">
                <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo_header.png"
                    alt="{{ \Config::get('app.name') }}" width="200">
            </a>

			<div class="links-header align-items-center d-flex ms-auto d-lg-none">
				<a href="{{ $searchAction }}" class="icon-link">
					<x-icon.boostrap icon="search" size="18px" />
				</a>

                @if (Session::has('user'))
                    <a class="icon-link" href="{{ route('panel.favorites', ['lang' => Config::get('app.locale')]) }}">
                        <x-icon.boostrap icon="heart" size="18px" />
                    </a>

                    <a class="icon-link" href="{{ route('panel.allotments', ['lang' => Config::get('app.locale')]) }}">
                        <x-icon.boostrap icon="bag" size="18px" />
                    </a>
                @else
                    <a class="icon-link btn_login" href="#">
                        <x-icon.boostrap icon="person" size="20px" />
                    </a>
                @endif
            </div>

			<button class="navbar-toggler collapsed" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                type="button" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="navbar-collapse collapse pt-2 pt-lg-0" id="navbarHeader">
                <ul class="navbar-nav w-100 justify-content-center mb-2 mb-lg-0">

                    @if (!empty($categories))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="categoriesHeader" data-bs-toggle="dropdown"
                                href="#" aria-expanded="false">{{ trans("$theme-app.lot.categories") }}</a>
                            <ul class="dropdown-menu" aria-labelledby="categoriesHeader">
                                @foreach ($categories as $category)
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('category', ['keycategory' => $category['key_ortsec0']]) }}">{{ $category['des_ortsec0'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    @if ($global['auctionTypes']->where('tipo_sub', 'W')->value('count'))
                        <li class="nav-item">
                            <a href="{{ route('subastas.presenciales') }}" @class([
                                'nav-link',
                                'lb-text-primary' => $pageName === 'subastas.presenciales',
                            ])>
                                <span>{{ trans($theme . '-app.foot.auctions') }}</span>
                            </a>
                        </li>
                    @endif

					<li class="nav-item">
						<a class="nav-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">
							<span>{{ trans($theme . '-app.foot.historico') }}</span>
						</a>
					</li>

                    <li class="nav-item">
                        <a href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale')]) }}"
                            title="" @class(['nav-link', 'lb-text-primary' => $pageName === 'valoracion'])><span>
                                {{ trans($theme . '-app.home.free-valuations') }}</span></a>
                    </li>
                </ul>

            </div>

            <div class="links-header align-items-center justify-content-end d-none d-lg-flex">

				<div class="d-none d-xl-block">
					@include('components.search', ['classes' => 'ms-auto', 'searchAction' => $searchAction])
				</div>
				<div class="d-block d-xl-none">
					<a href="{{ $searchAction }}" class="icon-link">
						<x-icon.boostrap icon="search" size="18px" />
					</a>
				</div>

                @if (Session::has('user'))
                    <a class="icon-link" href="{{ route('panel.favorites', ['lang' => Config::get('app.locale')]) }}">
                        <x-icon.boostrap icon="heart" size="18px" />
                    </a>

                    <a class="icon-link" href="{{ route('panel.allotments', ['lang' => Config::get('app.locale')]) }}">
                        <x-icon.boostrap icon="bag" size="18px" />
                    </a>
                @else
                    <a class="icon-link btn_login" href="#">
                        <x-icon.boostrap icon="heart" size="18px" />
                    </a>

                    <a class="icon-link btn_login" href="#">
                        <x-icon.boostrap icon="bag" size="18px" />
                    </a>
                @endif
            </div>

        </div>
    </nav>

</header>


<div class="login_desktop container-fluid" style="display: none">
    <div class="h-100 d-flex justify-content-center align-content-center">
        <div class="login_desktop_content m-auto">
            <div class="only-login bg-white p-5 position-relative">
                <div class="login-content-form">
                    <img class="closedd" src="/themes/{{ $theme }}/assets/img/shape.png" alt="Close"
                        role="button">

                    <p class="login_desktop_title h1">{{ trans($theme . '-app.login_register.login') }}</p>

                    <form class="d-flex align-items-center justify-content-center flex-column py-4"
                        id="accerder-user-form" data-toggle="validator">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <svg class="bi" width="16" height="16" fill="currentColor">
                                    <use xlink:href="/bootstrap-icons.svg#person-fill"></use>
                                </svg>
                            </span>
                            <input class="form-control" name="email" type="email"
                                placeholder="{{ trans($theme . '-app.login_register.user') }}" autocomplete="email">
                        </div>

                        <div class="input-group mb-0">
                            <span class="input-group-text">
                                <svg class="bi" width="16" height="16" fill="currentColor">
                                    <use xlink:href="/bootstrap-icons.svg#key-fill"></use>
                                </svg>
                            </span>
                            <input class="form-control" name="password" type="password"
                                placeholder="{{ trans($theme . '-app.login_register.contraseña') }}" maxlength="20"
                                autocomplete="off">
                            <span class="input-group-text view_password">
                                <img class="eye-password"
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                            </span>
                        </div>

                        <small class="align-self-end">
                            <a class="c_bordered" id="p_recovery"
                                data-ref="{{ \Routing::slug('password_recovery') }}"
                                data-title="{{ trans($theme . '-app.login_register.forgotten_pass_question') }}"
                                data-bs-toggle="modal" data-bs-target="#modalAjax" href="javascript:;"
                                onclick="cerrarLogin();">
                                {{ trans($theme . '-app.login_register.forgotten_pass_question') }}
                            </a>
                        </small>

                        <p><span class="message-error-log text-danger seo_h5"></span></p>

                        <button class="btn btn-lb-primary w-100 mt-4" id="accerder-user" type="submit">
                            <span class="text">{{ trans($theme . '-app.login_register.acceder') }}</span>
                            <div class="spinner spinner-1 m-auto"></div>
                        </button>

                    </form>

                    <div class="login-separator" data-content="o"></div>

                    <p class="text-center mb-2">{{ trans($theme . '-app.login_register.not_account') }}</p>

                    <div class="create-account-link">
                        @if (empty($registration_disabled))
                            <a class="btn btn-outline-lb-secondary w-100" href="{{ \Routing::slug('register') }}"
                                title="{{ trans($theme . '-app.login_register.register') }}">
                                {{ trans($theme . '-app.login_register.register') }}
                            </a>
                        @else
                            <p class="text-center" style="color: darkred;">
                                {{ trans($theme . '-app.login_register.registration_disabled') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
