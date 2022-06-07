<div class="sub_panel">
	<form id="password_recovery" action='javascript:password_recovery("{{\App::getLocale()}}")'>
		<div class="row">
			<div class=" col-sm-8 col-sm-offset-2">
				<label>{{ trans(\Config::get('app.theme').'-app.login_register.insert_email')}}</label>
				<input name="email" type="email" class="form-control input-md" placeholder="Email">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="post" value="true">
                                <label class="error-recovery text-danger"></span>
			</div>
		</div>
		<div class="acenter text-center">
			<button type="button" class="btn btn-step-reg password_recovery_button" onclick='password_recovery("{{\App::getLocale()}}")'>{{ trans(\Config::get('app.theme').'-app.login_register.send_email') }}</button>
		</div>
	</form>

</div>