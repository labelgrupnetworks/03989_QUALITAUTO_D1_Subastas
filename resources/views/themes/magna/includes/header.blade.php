@php
    $registration_disabled = Config::get('app.registration_disabled');
	$locale = Config::get('app.locale');
    $pageName = Route::currentRouteName();
@endphp

<div class="wrapp-info-header d-none d-lg-block border-bottom py-2">
    <div class="container">
        <div class="d-flex">
            <p>Abierto diariamente de 10.00 - 20.00 h</p>
            <p class="ms-auto">
                | +34 91 411 11 11 | <a href="mailto:magnasubastas@ejemplo.com">magnasubastas@ejemplo.com</a>
            </p>
        </div>
    </div>
</div>

<header>

    <div class="container">

        <div class="select-searching">

            <button class="navbar-toggler flex-center" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                type="button" aria-expanded="false" aria-label="Menú" aria-controls="navbarHeader"
                aria-expanded="false"></button>


            <div class="search-component">
                <form action="{{ Routing::translateSeo('subasta-actual') }}#grid-lots">
                    <input name="description" type="search"
                        placeholder="{{ trans("$theme-app.global.write_search") }}">
                    <span class="icon flex-center">
                        <x-icon.fontawesome version=6 icon=magnifying-glass></x-icon.fontawesome>
                    </span>
                </form>

            </div>

        </div>

        <a class="logo-link" href="{{ '/' . $locale }}" title="{{ Config::get('app.name') }}">
			<x-icon.logo />
        </a>


        <div class="login-wrapper d-flex">
            @if (!Session::has('user'))
                <a class="btn btn-link" href="{{ \Routing::slug('register') }}"
                    title="{{ trans("$theme-app.login_register.register") }}">
                    {{ trans("$theme-app.login_register.register") }}
                </a>

                <button class="btn btn_login btn-outline-lb-primary rounded-5">
                    <svg width="20" height="20" viewBox="0 0 18 21" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.639 6.00943C4.639 3.52379 6.65401 1.50878 9.13965 1.50878C11.6253 1.50878 13.6403 3.52379 13.6403 6.00943C13.6403 8.49507 11.6253 10.5101 9.13965 10.5101C6.65401 10.5101 4.639 8.49507 4.639 6.00943ZM9.13965 0.491241C6.09204 0.491241 3.62146 2.96182 3.62146 6.00943C3.62146 9.05704 6.09204 11.5276 9.13965 11.5276C12.1873 11.5276 14.6578 9.05704 14.6578 6.00943C14.6578 2.96182 12.1873 0.491241 9.13965 0.491241ZM4.13088 12.7812C2.12076 12.7812 0.491226 14.4107 0.491226 16.4208V20.1779H1.50876V16.4208C1.50876 14.9727 2.68273 13.7987 4.13088 13.7987H14.1497C15.5979 13.7987 16.7718 14.9727 16.7718 16.4208V20.1779H17.7894V16.4208C17.7894 14.4107 16.1598 12.7812 14.1497 12.7812H4.13088Z" />
                    </svg>
                    <span class="d-none d-lg-inline">{{ trans("$theme-app.login_register.generic_name") }}</span>
                </button>
            @else
                <a class="btn" href="{{ \Routing::slug('user/panel/orders') }}">
                    <svg width="20" height="20" viewBox="0 0 18 21" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.639 6.00943C4.639 3.52379 6.65401 1.50878 9.13965 1.50878C11.6253 1.50878 13.6403 3.52379 13.6403 6.00943C13.6403 8.49507 11.6253 10.5101 9.13965 10.5101C6.65401 10.5101 4.639 8.49507 4.639 6.00943ZM9.13965 0.491241C6.09204 0.491241 3.62146 2.96182 3.62146 6.00943C3.62146 9.05704 6.09204 11.5276 9.13965 11.5276C12.1873 11.5276 14.6578 9.05704 14.6578 6.00943C14.6578 2.96182 12.1873 0.491241 9.13965 0.491241ZM4.13088 12.7812C2.12076 12.7812 0.491226 14.4107 0.491226 16.4208V20.1779H1.50876V16.4208C1.50876 14.9727 2.68273 13.7987 4.13088 13.7987H14.1497C15.5979 13.7987 16.7718 14.9727 16.7718 16.4208V20.1779H17.7894V16.4208C17.7894 14.4107 16.1598 12.7812 14.1497 12.7812H4.13088Z" />
                    </svg>
                    <span>{{ trans("$theme-app.login_register.my_panel") }}</span>
                </a>
            @endif

            @if (Session::get('user.admin'))
                <a class="btn" href="/admin" target="_blank">
                    <svg class="bi bi-person-gear" xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                        fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                    </svg>
                    <span>{{ trans("$theme-app.login_register.admin") }}</span>
                </a>
            @endif
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg header-navbar">
    <div class="container">
        <div class="navbar-collapse collapse" id="navbarHeader" style="">
            <ul class="navbar-nav mb-2 mb-lg-0 fw-bold">

                @if ($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
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
                    <a href="" @class(['nav-link', 'lb-text-primary' => $pageName === 'comprar'])>
                        <span>Comprar</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="" @class(['nav-link', 'lb-text-primary' => $pageName === 'comprar'])>
                        <span>Vender</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale')]) }}"
                        title="" @class(['nav-link', 'lb-text-primary' => $pageName === 'valoracion'])>
                        <span> {{ trans($theme . '-app.home.free-valuations') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                        href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}"
                        title="{{ trans($theme . '-app.foot.about_us') }}">
                        <span>{{ trans($theme . '-app.foot.about_us') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('contact_page') }}" title="{{ trans($theme . '-app.foot.contact') }}"
                        @class([
                            'nav-link',
                            'lb-text-primary' => $pageName === 'contact_page',
                        ])><span>{{ trans($theme . '-app.foot.contact') }}</span></a>
                </li>
            </ul>

        </div>
    </div>
</nav>

<div class="login_desktop container-fluid" style="display: none">
    <div class="h-100 d-flex justify-content-center align-content-center">
        <div class="login_desktop_content m-auto">
            <div class="only-login bg-white position-relative">
                <div class="text-center">
                    <button class="btn-close closedd" type="button" aria-label="Close"></button>

                    <p class="login_desktop_title h1">{{ trans($theme . '-app.login_register.login') }}</p>

                    <form id="accerder-user-form" data-toggle="validator">
                        @csrf

                        <div class="form-floating">
                            <input class="form-control" id="floatingInput" name="email" type="email"
                                placeholder="email@example.com">
                            <label for="floatingInput">{{ trans("$theme-app.login_register.ph_user") }}</label>
                        </div>

                        <div class="form-floating input-group">
                            <input class="form-control" id="floatingPassword" name="password" type="password"
                                placeholder="contraseña">
                            <label for="floatingPassword">{{ trans("$theme-app.login_register.password") }}</label>
                            <span class="input-group-text view_password">
                                <img class="eye-password"
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                            </span>
                        </div>

                        <p class="message-error-log text-danger d-none"></p>

                        <button class="btn btn-lb-primary btn-fluid" id="accerder-user" type="submit">
                            <span class="text">{{ trans($theme . '-app.login_register.acceder') }}</span>
                            <div class="spinner spinner-1 m-auto"></div>
                        </button>

                    </form>

                    <div class="d-flex flex-column gap-3">

                        <a class="c_bordered fs-16" id="p_recovery"
                            data-ref="{{ \Routing::slug('password_recovery') }}"
                            data-title="{{ trans($theme . '-app.login_register.forgotten_pass_question') }}"
                            data-bs-toggle="modal" data-bs-target="#modalAjax" data-toggle="modal"
                            data-target="#modalAjax" href="javascript:;" onclick="cerrarLogin();">
                            {{ trans($theme . '-app.login_register.forgotten_pass_question') }}
                        </a>

                        <div class="login-separator" data-content=""></div>

                        <p class="text-center mb-2 fs-16">{{ trans($theme . '-app.login_register.not_account') }}</p>

                        <div>
                            @if (empty($registration_disabled))
                                <a class="btn btn-lb-primary btn-medium" href="{{ \Routing::slug('register') }}"
                                    title="{{ trans("$theme-app.login_register.register") }}">
                                    {{ trans("$theme-app.login_register.register") }}
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
</div>
