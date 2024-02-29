@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    @if (Config::get('app.panel_password_recovery'))
        <section class="mb-5 mt-5">
            <div class="container panel panel-recovery">
                <div class='row'>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-lg-offset-4 col-md-offset-4">

                        <form class="frmLogin" id="passRecovered">
                            <div class="form-group">
                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                <input id="login" name="login" type="hidden" value="{{ $data['login'] }}">
                                <input type="password" style="display:none">
                                @if (!empty(Request::input('email')))
                                    <input name="email" type="email" value="{{ Request::input('email') }}"
                                        style="display:none">
                                @endif
                                <div class="form" style="position:relative">
                                    <label
                                        for="contrasena">{{ trans($theme . '-app.user_panel.new_pass') }}</label>
                                    <input class="form-control" id="password" id="contrasena" name="password"
                                        data-minlength="5" type="password" type="password" maxlength="20" required>
                                    <img class="view_password eye-password"
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form" style="position: relative">
                                    <label
                                        for="confirmcontrasena">{{ trans($theme . '-app.user_panel.new_pass_repeat') }}</label>
                                    <input class="form-control" id="confirmcontrasena" name="confirm_password"
                                        data-match="#password" data-minlength="5" type="password" maxlength="20" required>
                                    <img class="view_password eye-password"
                                        src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                                </div>
                                <div class="text-center mt-2">
                                    <button class="btn btn-step-reg"
                                        type="submit">{{ trans($theme . '-app.user_panel.save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="permanentAuctions">
            <div class="container">
                <div class="alert alert-success">
                    <?= trans($theme . '-app.login_register.pass_sent') ?>
                </div>
            </div>
        </section>
    @endif
@stop
