<div class="sub_panel">
	<form id="password_recovery">
            
		<div class="row">
			<div class=" col-sm-8 col-sm-offset-2">
                            
				<label>{{ trans(\Config::get('app.theme').'-app.login_register.activate_account_description')}}</label>
				<input name="email" type="email" class="form-control input-md" placeholder="Email" id="emailActivate">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="post" value="true">
                                <input type="hidden" name="activate" value="true">
                                <label class="error-recovery text-danger" style="display:none;" id="error-recovery">{{ trans(\Config::get('app.theme').'-app.login_register.not_valid_mail') }}</label>
                                <div class="checkbox">
                                    <input 
                                        name="conditions" 
                                        required 
                                        type="checkbox"
                                        class="form-control filled-in" 
                                        id="conditions"
                                        >
                                    <label for="conditions">
                                        {{ trans(\Config::get('app.theme').'-app.login_register.read_conditions') }} (<a href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.term_condition') ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)                                
                                    </label>
                                </div>
                                <label class="error-check text-danger" style="display:none;" id="error-check">Activar campo</label>
                                
			</div>
		</div>
		<div class="acenter text-center">
			<button type="button" class="btn btn-step-reg password_recovery_button" onclick='activate_account("{{\App::getLocale()}}")'>{{ trans(\Config::get('app.theme').'-app.login_register.send_email') }}</button>
		</div>
	</form>

</div>