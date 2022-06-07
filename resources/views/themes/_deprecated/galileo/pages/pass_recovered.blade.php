@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
@if(Config::get('app.panel_password_recovery'))
<section class="mb-20">
    <div class="container panel pass-recovery">
        <div class='row'>
            <form class="frmLogin col-xs-12 col-md-4 col-md-offset-4" id="passRecovered">
                        <div class="col-xs-12 inputs-custom-group pass-recovery-inputs" style="display: block">
                    <div class="form-group col-xs-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if(!empty(Request::input('email')))
                        <input type="hidden" name="email" value="{{Request::input('email')}}">
                        @endif
                        <div class="form" style="position:relative">
                            <label for="contrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.new_pass') }}</label>
                                <input maxlength="20" type="password" id="password" name="password" type="password" class="form-control" id="contrasena"  data-minlength="5" required>
                                <img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <div class="form" style="position: relative">
                        <label for="confirmcontrasena">{{ trans(\Config::get('app.theme').'-app.user_panel.new_pass_repeat') }}</label>
                        <input maxlength="20" type="password" name="confirm_password" class="form-control" data-match="#password" id="confirmcontrasena"  required  data-minlength="5">
                        <img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                        </div>
                        <div class="text-center button-pass-recovery">
                            <button class="button-principal" type="submit">{{ trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
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
			<?= trans(\Config::get('app.theme').'-app.login_register.pass_sent') ?>
		</div>
	</div>
</section>
@endif
@stop