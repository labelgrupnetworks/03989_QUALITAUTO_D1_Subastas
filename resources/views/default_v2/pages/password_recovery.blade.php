<div class="sub_panel">
	<form id="password_recovery" onsubmit='password_recovery(event, "{{\App::getLocale()}}")'>
		@csrf
		<div class="row">
			<div class="col-sm-10 m-auto mb-3">
				<label>{{ trans(\Config::get('app.theme').'-app.login_register.insert_email')}}</label>
				<input name="email" type="email" class="form-control" placeholder="Email">
				<input type="hidden" name="post" value="true">
				<label class="error-recovery text-danger"></span>
			</div>
		</div>
		<div class="text-center">
			<button type="submit" class="btn btn-lb-primary">
				{{ trans(\Config::get('app.theme').'-app.login_register.send_email') }}
			</button>
		</div>
	</form>
</div>
