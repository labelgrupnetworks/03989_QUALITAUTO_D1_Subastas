<section class="newsletter  newsletter-js">
    <div class="d-flex flex-column align-items-start gap-2 gap-lg-4">

		<h3 class="newsletter-tittle mb-lg-3 fs-1">
			{{ trans("$theme-app.foot.newsletter_title") }}
		</h3>

		<input class="form-control newsletter-input-email-js" type="email" aria-label="email" aria-describedby="newsletter-btn" placeholder="email">
		<input class="lang-newsletter-js" type="hidden" value="<?= \App::getLocale() ?>">
        <input class="newsletter" id="newsletter-input" name="families[]" type="hidden" value="1">

		<div class="form-check">
			<input class="form-check-input condiciones-newsletter-js"   type="checkbox" type="checkbox">
			<label class="form-check-label small" for="condiciones">
				{!! trans("$theme-app.login_register.read_conditions_politic") !!}
			</label>
		</div>

		<button class="btn btn-lb-secondary button-newsletter newsletter-btn-js"  type="button">
			{{ trans("$theme-app.foot.newsletter_button") }}
		</button>

    </div>
</section>
