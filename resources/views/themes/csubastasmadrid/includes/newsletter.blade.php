<script src='https://www.google.com/recaptcha/api.js?hl={{config('app.locale')}}'></script>

<div class="newsletter">
	<div class="news_home col-xs-12 ">
		<h4>{{trans(\Config::get('app.theme').'-app.foot.newsletter_title')}}</h4>
		<form class="form-inline" id="form-newsletter">
			<div class="form-group">
				<label>{{trans(\Config::get('app.theme').'-app.foot.newsletter_text_input')}} <label>
						<input class="newsletter-input form-control" type="text" name="email">
						<input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>">
			</div>

			<button id="newsletter-btn" class="btn-secondary-color btn"
				type="button">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>

			<ul class="list_cat_home">
				<div class="list_cat_home_block">
					<li><input type="checkbox" class="newsletter" name="families[]" value="1">
						{{trans(\Config::get('app.theme').'-app.foot.sellos_españa')}}</li>
					<li><input type="checkbox" class="newsletter" name="families[]" value="3">
						{{trans(\Config::get('app.theme').'-app.foot.libros_documentos')}}</li>
					<li><input type="checkbox" class="newsletter" name="families[]" value="5">
						{{trans(\Config::get('app.theme').'-app.foot.monedas_billetes')}}</li>
				</div>
				<div class="list_cat_home_block">
					<li><input type="checkbox" class="newsletter" name="families[]" value="2">
						{{trans(\Config::get('app.theme').'-app.foot.sellos')}}</li>
					<li><input type="checkbox" class="newsletter" name="families[]" value="4">
						{{trans(\Config::get('app.theme').'-app.foot.carteles')}}</li>
					<li><input type="checkbox" class="newsletter" name="families[]" value="6">
						{{trans(\Config::get('app.theme').'-app.foot.coleccionismo')}}</li>
				</div>
			</ul>
			<div id="recaptcha" data-callback="recaptcha_callback" class="g-recaptcha"
				data-sitekey="6LdhD34UAAAAANG9lkke6_b6fyycAsWTpfpm_sTV"></div>
			<div class="check_term box">
				<input name="condiciones" required type="checkbox" class="form-control" id="condiciones" />
				<label for="recibir-newletter">
					<?= trans(\Config::get('app.theme').'-app.login_register.read_conditions_politic') ?>
				</label>
			</div>
		</form>
	</div>

</div>
