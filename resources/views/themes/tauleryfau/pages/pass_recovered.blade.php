@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')
@if(Config::get('app.panel_password_recovery'))
<section>
	<div class="container">
    	<h1>{{ trans($theme.'-app.user_panel.new_pass') }}</h1>
    	<br><br>
        <div class='row'>
            <div class="col-lg-4 col-md-4 col-xs-12 col-lg-offset-4 col-md-offset-4 form-group account-container flex">
               <form class="frmLogin" id="passRecovered">
                    <div class="form-group form-group-custom">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="login" id="login" value="{{$data['login']}}">
                        <input style="display:none" type="password">
                        <div class="form" style="position:relative">
                        <label for="contrasena">{{ trans($theme.'-app.user_panel.new_pass') }}</label>
                        <input maxlength="20" type="password" id="password" name="password" type="password" class="form-control" id="contrasena"  data-minlength="5" required="required">
                       	@if(!empty(Request::input('email')))
                        <input style="display:none" type="email" name="email" value="{{Request::input('email')}}">
                        @endif
                    </div>
                    <br>
                    <div class="form-group form-group-custom">
                        <div class="form" style="position: relative">
                        <label for="confirmcontrasena">{{ trans($theme.'-app.user_panel.new_pass_repeat') }}</label>
                        <input maxlength="20" type="password" name="confirm_password" class="form-control" data-match="#password" id="confirmcontrasena"  required="required" data-minlength="5">
                        <br><br>
                        <div class="text-center">
                            <button class="btn btn-step-reg" type="submit">{{ trans($theme.'-app.user_panel.save') }}</button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br><br>
                </form>
            </div>
        </div>
    </div>
    <br><br>
    <br><br>
</section>
@else
<section class="permanentAuctions">
	<div class="container">
		<div class="alert alert-success">
			<?= trans($theme.'-app.login_register.pass_sent') ?>
		</div>
	</div>
</section>
@endif
@stop
