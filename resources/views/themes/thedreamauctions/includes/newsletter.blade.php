@if(env('APP_DEBUG'))
<div class="newsletter">
	<div class="container">
		<div class="row d-flex flex-wrap">

			<div class="col-xs-12 col-sm-6 col-md-5 col-lg-4 mt-2">
				<div class="newsletter-tittle">{{ trans(\Config::get('app.theme').'-app.foot.newsletter_title') }}</div>
				<p class="newsletter-subtittle">
					{{ trans(\Config::get('app.theme').'-app.foot.newsletter_description') }}</p>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-7 col-lg-8 mt-2 d-flex align-items-center justify-content-center">
				<a href="https://www.thedreamauctions.com/contacto/" target="_blank"
					class="btn button-principal newsletter-button">{{ trans("$theme-app.foot.contact") }}</a>
			</div>

		</div>
	</div>
</div>
@endif
