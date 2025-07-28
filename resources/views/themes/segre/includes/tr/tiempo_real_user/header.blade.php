@php
    $lang = config('app.locale');
@endphp
<header
    class="d-flex flex-wrap gap-1 flex-column flex-md-row align-items-center justify-content-center justify-content-lg-between px-2 py-1">

	<a class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none navbar-brand" href="/{{ $lang }}" style="flex: 1;">
		<x-icon.logo />
    </a>

    <div class="nav mb-2 justify-content-center mb-lg-0" style="flex: 2;">
        <h2 class="auction_title text-center h1 no-decoration m-0 p-0">{{ $data['name'] }}</h2>
    </div>

    <div class="text-center text-lg-end" style="flex: 1;">
        @if (!Session::has('user'))
            <div class="dropdown-center">
                <a class="btn btn-sm btn-lb-primary dropdown-toggle" id="dropdownLogin" data-bs-toggle="dropdown"
                    data-bs-auto-close="false" href="#" role="button" aria-expanded="false">
                    {{ trans('web.login_register.login') }}
                </a>
                <ul class="dropdown-menu dropdown-menu-lg-end">

                    <div class="own_box p-1 position-relative" data-rel="login">
						<div class="loading-wrapper position-absolute text-lb-secondary d-none">
							<div class="spinner-border" style="width: 4rem; height: 4rem;" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
						</div>
                        <form id="accerder-user-form">
                            @csrf

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="bi" width="16" height="16" fill="currentColor">
                                        <use xlink:href="/bootstrap-icons.svg#person-fill"></use>
                                    </svg>
                                </span>
                                <input class="form-control" name="email"
                                    data-error="{{ trans('web.login_register.write_valid_email') }}"
                                    type="email" placeholder="{{ trans('web.login_register.user') }}">
                            </div>
                            <div class="input-group mb-2">
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

                            <span class="message-error-log text-danger"></span>
                            <input class="btn btn-lb-primary w-100" type="button"
                                value="{{ trans('web.login_register.sign_in') }}"
                                onclick="javascript:login_web()">
                        </form>
                    </div>
                </ul>
            </div>
        @else
		<ul class="nav nav-user align-items-center gap-2 justify-content-center justify-content-lg-end">
			<li>
                <h6 class="m-0 nav-user-name">{{ Session::get('user.name') }} ({{ $data['js_item']['user']['cod_licit'] }})</h5>
            </li>

            <li>
                <a class="btn btn-sm btn-lb-secondary-gold"
                    href="{{ \Routing::slug('logout') }}/tr">Cerrar sesión</a>
            </li>
		</ul>
        @endif
    </div>
</header>
