@php
    $lang = config('app.locale');
@endphp
<header
    class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between px-2">
    <a class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none navbar-brand" href="/{{ $lang }}">
        <img class="img-fluid" src="/themes/{{ $theme }}/assets/img/logo_numismatica.png" alt="numismática"
            width="80">
        <img class="img-fluid logo" src="/themes/{{ $theme }}/assets/img/logo.png"
            alt="{{ \Config::get('app.name') }}">
    </a>

    <div class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
        <h2 class="auction_title text-center h1 no-decoration m-0 p-0">{{ $data['name'] }}</h2>
    </div>

    <div class="col-12 col-md-3 text-center text-md-end">
        @if (!Session::has('user'))
            <div class="dropdown-center">
                <a class="nav-link dropdown-toggle" id="dropdownLogin" data-bs-toggle="dropdown"
                    data-bs-auto-close="false" href="#" role="button" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="29"
                        height="29" viewBox="0 0 29 29">
                        <defs>
                            <clipPath id="clip-path">
                                <rect id="Rectángulo_19" data-name="Rectángulo 19" width="29" height="29"
                                    fill="#b9b13c" />
                            </clipPath>
                        </defs>
                        <g id="Grupo_25" data-name="Grupo 25" clip-path="url(#clip-path)">
                            <path id="Trazado_1" data-name="Trazado 1"
                                d="M14.5,29A14.5,14.5,0,1,1,29,14.5,14.517,14.517,0,0,1,14.5,29M5.81,24.823a13.475,13.475,0,0,0,17.378,0l-.6-1.543a8.679,8.679,0,0,0-16.178,0ZM14.5,16.75a9.619,9.619,0,0,1,9.021,6.17l.46,1.183a13.5,13.5,0,1,0-18.961,0l.459-1.181A9.619,9.619,0,0,1,14.5,16.75m0-1.875a4.656,4.656,0,1,1,4.656-4.656A4.661,4.661,0,0,1,14.5,14.875m0-8.312a3.656,3.656,0,1,0,3.656,3.656A3.66,3.66,0,0,0,14.5,6.563"
                                fill="#b9b13c" />
                        </g>
                    </svg>
                </a>
                <ul class="dropdown-menu dropdown-menu-md-end">
                    <div class="own_box p-1" data-rel="login">
                        <form id="accerder-user-form">
                            @csrf

                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="bi" width="16" height="16" fill="currentColor">
                                        <use xlink:href="/bootstrap-icons.svg#person-fill"></use>
                                    </svg>
                                </span>
                                <input class="form-control" name="email"
                                    data-error="{{ trans(\Config::get('app.theme') . '-app.login_register.write_valid_email') }}"
                                    type="email" placeholder="{{ trans($theme . '-app.login_register.user') }}">
                            </div>
                            <div class="input-group mb-2">
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

                            <span class="message-error-log"></span>
                            <input class="btn btn-lb-primary w-100" type="button"
                                value="{{ trans(\Config::get('app.theme') . '-app.login_register.sign_in') }}"
                                onclick="javascript:login_web()">
                        </form>
                    </div>
                </ul>
            </div>
        @else
            <li class="nav-item">
                <h5>{{ Session::get('user.name') }} ({{ $data['js_item']['user']['cod_licit'] }})</h5>
            </li>

            <li class="nav-item">
                <a class="btn btn-lb-secondary"
                    href="{{ \Routing::slug('logout') }}/tr">{{ trans(\Config::get('app.theme') . '-app.login_register.logout') }}</a>
            </li>
        @endif
    </div>
</header>
