<div class="container">

	<div class="row">
		<div class="col-xs-12 col-md-6">

			<div class="newsletter">


				<div class="row">

					<div class="col-xs-12 mt-2 mb-1">
						<div class="newsletter-tittle">
							{{ trans(\Config::get('app.theme').'-app.foot.newsletter_title') }}</div>
					</div>
					<div
						class="col-xs-12 mt-2 mb-2 newsletter-control-input">
						<input class="form-control input-lg newsletter-input" type="text" placeholder="{{ trans(\Config::get('app.theme').'-app.foot.newsletter_text_input') }}">
						<input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>">
						<input type="hidden" class="newsletter" id="newsletter-input" name="families[]" value="1">

					</div>
					<div
						class="check_term box col-xs-12 mt-2 mb-2">
						<div class="form-check">
							<input name="condiciones" type="checkbox" id="condiciones" type="checkbox"
								class="form-check-input">
							<label class="form-check-label" for="condiciones">{!!
								trans(\Config::get('app.theme').'-app.login_register.read_conditions_politic')
								!!}</label>
						</div>
						<button id="newsletter-btn" type="button"
							class="button-principal button-newsletter">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>
					</div>

				</div>


			</div>

		</div>

		<div class="col-xs-12 col-md-6 slider-blog">

			{!! \BannerLib::bannersPorKey('blog_banner', 'blog_banner') !!}

		</div>

	</div>

</div>
