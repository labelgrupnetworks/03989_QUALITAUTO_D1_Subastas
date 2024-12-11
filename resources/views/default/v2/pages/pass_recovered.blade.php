@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    @if (Config::get('app.panel_password_recovery'))
        <main>
            <div class="container panel-recovery">
                <div class='row'>
                    <div class="m-auto" style="max-width: 380px">
                        <form class="frmLogin" id="passRecovered">

                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <input id="login" name="login" type="hidden" value="{{ $data['login'] }}">
                            <input type="password" style="display:none">

                            @if (!empty(Request::input('email')))
                                <input name="email" type="email" value="{{ Request::input('email') }}"
                                    style="display:none">
                            @endif


                            <div class="mb-3">
                                <label class="form-label"
                                    for="password">{{ trans($theme . '-app.user_panel.new_pass') }}</label>
                                <div class="input-group form-group">
                                    <span class="input-group-text">
                                        <svg class="bi" width="16" height="16" fill="currentColor">
                                            <use xlink:href="/bootstrap-icons.svg#key-fill"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control" id="password" name="password"
                                        data-minlength="5" type="password" placeholder="*****" maxlength="20"
                                        autocomplete="off" required>
                                    <span class="input-group-text view_password">
                                        <img class="eye-password"
                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"
                                    for="confirmcontrasena">{{ trans($theme . '-app.user_panel.new_pass') }}</label>
                                <div class="input-group form-group">
                                    <span class="input-group-text">
                                        <svg class="bi" width="16" height="16" fill="currentColor">
                                            <use xlink:href="/bootstrap-icons.svg#key-fill"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control" id="confirmcontrasena" name="confirm_password"
                                        data-minlength="5" data-match="#password" type="password" placeholder="*****"
                                        maxlength="20" autocomplete="off" required>
                                    <span class="input-group-text view_password">
                                        <img class="eye-password"
                                            src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3 text-center">
                                <button class="btn btn-lb-primary btn-step-reg" type="submit">
                                    {{ trans($theme . '-app.user_panel.save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    @else
        <main class="permanentAuctions">
            <div class="container">
                <div class="alert alert-success">
                    <?= trans($theme . '-app.login_register.pass_sent') ?>
                </div>
            </div>
        </main>
    @endif
@stop
