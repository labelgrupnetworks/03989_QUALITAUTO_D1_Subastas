@php

    $lang = config('app.locale');
    $registration_disabled = Config::get('app.registration_disabled');

    $searchAction = config('app.gridLots', false) == 'new' ? route('allCategories') : \Routing::slug('busqueda');
    $pageName = Route::currentRouteName();

    $wordpressPage = 'https://melvinauctions.com/';
	if(Config::get('app.locale') == 'en'){
		$wordpressPage .= 'en/home/';
	}
    $headerLinks = ['quienes-somos', 'quiero-vender', 'quiero-comprar', 'compromiso', 'partnership'];

@endphp

<header>

    <div class="logo-header py-3">
        <div class="container">
            <div class="row row-cols-1 row-cols-xl-3 logo-header-content">
                <div class="d-none d-xl-block"></div>
                <div class="text-center">
                    <a href="/{{ $lang }}" title="{{ \Config::get('app.name') }}">
                        <img class="logo-company" src="/themes/{{ $theme }}/assets/img/logo.png"
                            alt="{{ \Config::get('app.name') }}" width="200">
                    </a>
                </div>
                <div class="text-end register-buttons d-none d-xl-block">
                    @if (!Session::has('user'))
                        <a class="btn btn-link text-black" href="{{ \Routing::slug('register') }}">
                            {{ trans('web.login_register.register_yourself') }}
                        </a>
                        <span>
                            |
                        </span>
                        <button class="btn btn-link btn_login text-black">
                            {{ trans('web.login_register.generic_name') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <nav class="navbar navbar-expand-xl">
        <div class="container">
            {{-- <a class="navbar-brand" href="/{{$lang}}" title="{{(\Config::get( 'app.name' ))}}">
				<img width="150" class="img-responsive" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
			</a> --}}
            <button class="navbar-toggler m-auto collapsed" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                type="button" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-collapse collapse" id="navbarHeader" style="">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-3">

                    @if ($global['auctionTypes']->where('tipo_sub', 'W')->value('count'))
                        <li class="nav-item">
                            <a href="{{ route('subastas.presenciales') }}" @class([
                                'nav-link',
                                'lb-text-primary' => $pageName === 'subastas.presenciales',
                            ])>
                                <span>{{ trans('web.foot.auctions') }}</span>
                            </a>
                        </li>
                    @endif

					@foreach ($headerLinks as $link)
						<li class="nav-item">
							<a class="nav-link" href="{{ $wordpressPage . '#' . $link }}"
								rel="noopener noreferrer">
								{{ trans('web.links.' . str_replace('-', '_', $link)) }}
							</a>
						</li>
					@endforeach
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-none d-xl-flex align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.facebook.com/melvinauctions" target="_blank"
                            rel="noopener noreferrer">
                            <x-icon.fontawesome type="brands" icon="square-facebook" size="20px" version="6" />
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.instagram.com/melvinauctions" target="_blank"
                            rel="noopener noreferrer">
                            <x-icon.fontawesome type="brands" icon="instagram" size="20px" version="6" />
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ $searchAction }}">
                            <x-icon.fontawesome type="solid" icon="magnifying-glass" size="20px" version="6" />
                        </a>
                    </li>
                    <li class="nav-item">
                        @if (!Session::has('user'))
                            <button class="btn nav-link btn-link btn_login">
                                <x-icon.fontawesome type="solid" icon="user" size="20px" version="6" />
                            </button>
                        @else
                            <a class="nav-link" href="{{ \Routing::slug('user/panel/orders') }}"
                                title="{{ trans('web.login_register.my_panel') }}">
                                <x-icon.fontawesome type="solid" icon="user" size="20px" version="6" />
                            </a>
                        @endif
                    </li>

                    @if (Session::get('user.admin'))
                        <li>
                            <a class="nav-link" href="/admin" target="_blank">
                                <x-icon.fontawesome type="solid" icon="user-gear" version="6" />
                            </a>
                        </li>
                    @endif

                    <li class="d-flex flex-column">
                        @include('includes.header.language_selector')
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <div class="social-header d-xl-none">
        <ul class="navbar-nav mb-2 d-flex flex-row align-items-center justify-content-center gap-3">
            <li class="nav-item">
                <a class="nav-link" href="https://www.facebook.com/melvinauctions" target="_blank"
                    rel="noopener noreferrer">
                    <x-icon.fontawesome type="brands" icon="square-facebook" size="20px" version="6" />
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://www.instagram.com/melvinauctions" target="_blank"
                    rel="noopener noreferrer">
                    <x-icon.fontawesome type="brands" icon="instagram" size="20px" version="6" />
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ $searchAction }}">
                    <x-icon.fontawesome type="solid" icon="magnifying-glass" size="20px" version="6" />
                </a>
            </li>
            <li class="nav-item">
                @if (!Session::has('user'))
                    <button class="btn nav-link btn-link btn_login">
                        <x-icon.fontawesome type="solid" icon="user" size="20px" version="6" />
                    </button>
                @else
                    <a class="nav-link" href="{{ \Routing::slug('user/panel/orders') }}"
                        title="{{ trans('web.login_register.my_panel') }}">
                        <x-icon.fontawesome type="solid" icon="user" size="20px" version="6" />
                    </a>
                @endif
            </li>
        </ul>
    </div>

</header>


<div class="login_desktop container-fluid" style="display: none">
    <div class="h-100 d-flex justify-content-center align-content-center">
        <div class="login_desktop_content m-auto">
            <div class="only-login bg-white p-5 position-relative">
                <div class="login-content-form">
                    <img class="closedd" src="/themes/{{ $theme }}/assets/img/shape.png" alt="Close"
                        role="button">

                    <p class="login_desktop_title h1">{{ trans('web.login_register.login') }}</p>

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
                                placeholder="{{ trans('web.login_register.user') }}" autocomplete="email">
                        </div>

                        <div class="input-group mb-0">
                            <span class="input-group-text">
                                <svg class="bi" width="16" height="16" fill="currentColor">
                                    <use xlink:href="/bootstrap-icons.svg#key-fill"></use>
                                </svg>
                            </span>
                            <input class="form-control" name="password" type="password"
                                placeholder="{{ trans('web.login_register.contraseña') }}" maxlength="20"
                                autocomplete="off">
                            <span class="input-group-text view_password">
                                <img class="eye-password"
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                            </span>
                        </div>

                        <small class="align-self-end">
                            <a class="c_bordered" id="p_recovery"
                                data-ref="{{ \Routing::slug('password_recovery') }}"
                                data-title="{{ trans('web.login_register.forgotten_pass_question') }}"
                                data-bs-toggle="modal" data-bs-target="#modalAjax" href="javascript:;"
                                onclick="cerrarLogin();">
                                {{ trans('web.login_register.forgotten_pass_question') }}
                            </a>
                        </small>

                        <p><span class="message-error-log text-danger seo_h5"></span></p>

                        <button class="btn btn-lb-primary w-100 mt-4" id="accerder-user" type="submit">
                            <span class="text">{{ trans('web.login_register.acceder') }}</span>
                            <div class="spinner spinner-1 m-auto"></div>
                        </button>

                    </form>

                    <div class="login-separator" data-content="o"></div>

                    <p class="text-center mb-2">{{ trans('web.login_register.not_account') }}</p>

                    <div class="create-account-link">
                        @if (empty($registration_disabled))
                            <a class="btn btn-outline-lb-secondary w-100" href="{{ \Routing::slug('register') }}"
                                title="{{ trans('web.login_register.register') }}">
                                {{ trans('web.login_register.register') }}
                            </a>
                        @else
                            <p class="text-center" style="color: darkred;">
                                {{ trans('web.login_register.registration_disabled') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
