<section class="newsletter">
    <div class="d-flex flex-column align-items-start gap-4">

		<h3 class="newsletter-tittle mb-3">
			{{ trans("$theme-app.foot.newsletter_title") }}
		</h3>

		<input class="form-control" type="email" aria-label="email" aria-describedby="newsletter-btn" placeholder="email">
		<input id="lang-newsletter" type="hidden" value="<?= \App::getLocale() ?>">
        <input class="newsletter" id="newsletter-input" name="families[]" type="hidden" value="1">

		<div class="form-check">
			<input class="form-check-input" id="condiciones" name="condiciones" type="checkbox" type="checkbox">
			<label class="form-check-label" for="condiciones">
				{!! trans("$theme-app.login_register.read_conditions_politic") !!}
			</label>
		</div>

		<button class="btn btn-lb-secondary button-newsletter" id="newsletter-btn" type="button">
			{{ trans("$theme-app.foot.newsletter_button") }}
		</button>

    </div>
</section>
