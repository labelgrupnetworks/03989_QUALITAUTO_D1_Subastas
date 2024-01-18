<div class="newsletter js-newletter-block text-center">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 mb-3">
                <div class="newsletter-tittle"> {{ trans(\Config::get('app.theme').'-app.foot.inscribete_catalogo') }}</div>
             {{--   <p class="newsletter-subtittle">SUSCRIBETE A LA NEWSLETTER</p> --}}
            </div>
            <div class="col-xs-3"></div>
            <div class=" newsletter-control-input">
                <div class="newsletter-placeholder">
                    {{ trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }}
                </div>
                <input class="form-control input-sm newsletter-input" type="text" placeholder="">


                <input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>" >
				<input type="hidden" class="newsletter" id="newsletter-input" name="families[]" value="1">
                <button id="newsletter-btn" type="button" class="button-principal button-newsletter">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>
				<!-- codi check terminos y condiciones -->
				<div class="check_term box ">
					<div class="form-check">
						<input
							name="condiciones"
							type="checkbox"
							id="condiciones"
							type="checkbox" class="form-check-input">
						<label class="form-check-label" for="condiciones">{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions') !!} (<a
							href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.term_condition') ?>"
							target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)</label>
					</div>
				</div>
				<!--  -->
            </div>
            <div class="col-xs-3"></div>
        </div>
    </div>
</div>


