<div class="container mt-2 mb-2">

	<div class="newsletter d-flex flex-direction-column align-items-center">

		<div class="newsletter-tittle text-uppercase"> {{ trans(\Config::get('app.theme').'-app.foot.inscribete_catalogo') }}</div>

		<div class="w-100 newsletter-input-group">

			<input class="form-control newsletter-input" type="text" placeholder="{{ trans("$theme-app.foot.newsletter_text_input") }}">

			<div class="form-check mt-1">
				<input name="condiciones" type="checkbox" id="condiciones" type="checkbox" class="form-check-input">

				<label class="form-check-label" for="condiciones">{!! trans(\Config::get('app.theme').'-app.login_register.read_conditions') !!} (<a
					href="{{ Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.privacy_policy') }}"
					target="_blank">{{ trans(\Config::get('app.theme').'-app.login_register.more_info') }}</a>)
				</label>
			</div>

		</div>

		<input type="hidden" id="lang-newsletter" value="<?=\App::getLocale()?>" >
        <input type="hidden" class="newsletter" id="newsletter-input" name="families[]" value="1" >
        <button id="newsletter-btn" type="button" class="button-principal button-newsletter">{{trans(\Config::get('app.theme').'-app.foot.newsletter_button')}}</button>

		{{-- <div>
			<p class="legal-text text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium aliquam similique exercitationem sint, architecto delectus placeat sed! Quibusdam doloribus nisi ut. Id, nobis. Tempora vero ducimus hic modi laboriosam commodi. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsam a amet suscipit. In aperiam quisquam dolores numquam. Voluptatem, illo! Obcaecati cupiditate quia nihil vero, ducimus modi eum reprehenderit pariatur harum.</p>
		</div> --}}

	</div>

</div>

{{-- <div class="col-12 block-social-links mt-3 mb-3">
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
</div> --}}


