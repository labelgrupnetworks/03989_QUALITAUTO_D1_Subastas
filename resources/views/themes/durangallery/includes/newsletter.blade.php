<div class="container mt-2 mb-2">

	<div class="newsletter js-newletter-block d-flex flex-direction-column align-items-center">

		<div class="newsletter-tittle text-uppercase"> {{ trans($theme.'-app.foot.inscribete_catalogo') }}</div>

		<div class="w-100 newsletter-input-group">

			<input class="form-control newsletter-input" type="text" placeholder="Tu email">
			<input type="hidden" data-sitekey="{{ config('app.captcha_v3_public') }}" name="captcha_token" value="">

			<div class="form-check mt-1">
				<input name="condiciones" type="checkbox" id="condiciones" type="checkbox" class="form-check-input">


				<label class="form-check-label" for="condiciones">{!! trans($theme.'-app.login_register.read_conditions') !!} (<a
					href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.privacy_policy') }}"
					target="_blank">{{ trans($theme.'-app.login_register.more_info') }}</a>)
				</label>
			</div>

		</div>

		<input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>" >
		<input type="hidden" class="newsletter" name="families[]" value="1">
		<p class="captcha-terms">
			{!! trans("$theme-app.global.captcha-terms") !!}
		</p>
        <button id="newsletter-btn" type="button" class="button-principal button-newsletter">{{trans($theme.'-app.foot.newsletter_button')}}</button>

	</div>

</div>

<div class="col-12 block-social-links mt-3 mb-3">
	<ul class="d-flex justify-content-center">
		<li><span class="linea"></span></li>
		<li>

			<ul class="social-links _footer">

				<li class="facebook"><a itemprop="sameAs" href="{{ \Config::get('app.facebook') }}"
						target="_blank" rel="noreferrer noopener"><i class="fa fa-facebook" aria-hidden="true"></i></a>
				</li>
				<li class="instagram"><a itemprop="sameAs" href="{{ \Config::get('app.instagram') }}"
						target="_blank" rel="noreferrer noopener"><i class="fa fa-instagram" aria-hidden="true"></i></a>
				</li>

			</ul>

		</li>
		<li><span class="linea"></span></li>
	</ul>
</div>


