<section class="newsletter newsletter-js">
	<div class="container">
		<div class="row flex-column-reverse flex-md-row">

			<div class="col-12 col-md-5 px-4 align-self-center">
				<img class="img-fluid" src="/themes/{{$theme}}/assets/img/undraw2.svg" alt="">
			</div>

			<div class="col-12 col-md-7 px-4 mb-5 mb-md-0 d-flex flex-column gap-4">
				<h1 class="newsletter-tittle lb-text-primary">{{ trans($theme.'-app.foot.newsletter_title') }}</h1>
				<h2 class="newsletter-subtittle">{{ trans($theme.'-app.foot.newsletter_description') }}</h2>

				<div class="row">
					<div class="col-md-8">
						<div class="input-group mb-3">
							<input type="email" class="form-control newsletter-input-email-js" placeholder="email" aria-label="email"
								aria-describedby="newsletter-btn" autocomplete="email">
							<button class="btn btn-lb-primary button-newsletter newsletter-btn-js" type="button"
								>{{trans($theme.'-app.foot.newsletter_button')}}</button>
						</div>

					</div>
				</div>

				<input type="hidden" class="lang-newsletter-js" value="<?=\App::getLocale()?>">
				<input type="hidden" class="newsletter"  name="families[]" value="1">
				<div class="form-check">
					<input class="condiciones-newsletter-js" type="checkbox" id="condiciones" type="checkbox" class="form-check-input">
					<label class="form-check-label" for="condiciones">{!!
						trans($theme.'-app.login_register.read_conditions_politic') !!}
					</label>
				</div>


			</div>
		</div>
	</div>
</section>
