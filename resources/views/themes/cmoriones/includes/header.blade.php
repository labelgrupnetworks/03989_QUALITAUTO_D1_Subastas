@php
    $lang = config('app.locale');
    $registration_disabled = Config::get('app.registration_disabled');

    $fullname = Session::get('user.name');
    $name = $fullname;
    if (strpos($fullname, ',')) {
        $str = explode(',', $fullname);
        $name = $str[1];
    }

    $categories = (new App\Models\V5\FgOrtsec0())
        ->getAllFgOrtsec0()
        ->whereNotNull('key_ortsec0')
        ->get()
        ->toarray();
    $searchAction = config('app.gridLots', false) == 'new' ? route('allCategories') : \Routing::slug('busqueda');
    $pageName = Route::currentRouteName();

    $activeAuctions = $global['subastas']->has('S') ? $global['subastas']['S']->flatten() : collect([]);
@endphp

<header>

    <div class="first-header-wrapper">
        <div class="container py-1 bk-gray">
            <div class="d-flex justify-content-end align-items-center gap-3">

                @include('components.search', ['classes' => 'me-auto d-none d-xxl-block'])

                @if (!Session::has('user'))
                    <button
                        class="btn btn-sm btn-lb-primary btn_login">{{ trans($theme . '-app.login_register.login') }}</button>
                @else
                    <a class="btn btn-sm btn-lb-primary"
                        href="{{ \Routing::slug('user/panel/orders') }}">{{ trans($theme . '-app.login_register.my_panel') }}</a>

                    @if (Session::get('user.admin'))
                        <a class="btn btn-sm btn-lb-primary" href="/admin" target = "_blank">
                            {{ trans($theme . '-app.login_register.admin') }}</a>
                    @endif

                    <a class="btn btn-sm btn-lb-primary"
                        href="{{ \Routing::slug('logout') }}">{{ trans($theme . '-app.login_register.logout') }}</a>
                @endif

                @include('includes.header.language_selector')
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-xxl">
        <div class="container">
            <a class="navbar-brand d-xxl-none" href="/{{ $lang }}" title="{{ \Config::get('app.name') }}">
                <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/logo_white.jpg"
                    alt="{{ \Config::get('app.name') }}" width="200">
            </a>
            <button class="navbar-toggler collapsed" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                type="button" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse collapse" id="navbarHeader" style="">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold">

                    <li class="nav-item">
                        <a href="{{ route('allCategories') }}" title="" @class([
                            'nav-link',
                            'lb-text-primary' => $pageName === 'allCategories',
                        ])>
                            Todos los activos
                        </a>
                    </li>

                    @if (!empty($categories))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="categoriesHeader" data-bs-toggle="dropdown"
                                href="#" aria-expanded="false">
								<span>
                                {{ trans("$theme-app.lot.categories") }}
								</span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="categoriesHeader">
                                @foreach ($categories as $category)
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('category', ['keycategory' => $category['key_ortsec0']]) }}">
                                            {{ $category['des_ortsec0'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a id="auctionsHeader" data-bs-toggle="dropdown" href="#" aria-expanded="false"
                            @class([
                                'nav-link dropdown-toggle',
                                'lb-text-primary' => $pageName === 'urlAuction',
                            ])>
                           <span>subastas</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="auctionsHeader">

                            @foreach ($activeAuctions as $auction)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ Tools::url_auction($auction->cod_sub, $auction->name, null) }}">
                                        {{ $auction->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="auctionsHeader" data-bs-toggle="dropdown" href="#" aria-expanded="false"
                            @class([
                                'nav-link dropdown-toggle',
                                'lb-text-primary' => $pageName === 'valoracion',
                            ])>
                            <span>Publique con nosotros</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="auctionsHeader">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale'), 'rep' => 'AC']) }}">
                                    Administradores Concursales
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale'), 'rep' => 'EB']) }}">
                                    Entidades Bancarias
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale'), 'rep' => 'FI']) }}">
                                    Fondos de Inversión
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('contact_page') }}" title="{{ trans($theme . '-app.foot.contact') }}"
                            @class([
                                'nav-link',
                                'lb-text-primary' => $pageName === 'contact_page',
                            ])><span>{{ trans($theme . '-app.foot.contact') }}</span></a>
                    </li>
                </ul>

                @include('components.search', ['classes' => 'me-auto d-xxl-none'])
            </div>
        </div>
    </nav>

</header>

<section class="subnav d-none d-xxl-block">
    <div class="container header-brand">
        <a class="navbar-brand" href="/{{ $lang }}" title="{{ \Config::get('app.name') }}">
            <img class="img-responsive" src="/themes/{{ $theme }}/assets/img/foto_logo_white.png"
                alt="{{ \Config::get('app.name') }}" width="200">
        </a>
        <a class="navbar-brand" href="/{{ $lang }}" title="{{ \Config::get('app.name') }}">
            <img class="img-responsive logo-brand" src="/themes/{{ $theme }}/assets/img/logo_white.jpg"
                alt="{{ \Config::get('app.name') }}" width="300">
        </a>
    </div>
</section>


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
