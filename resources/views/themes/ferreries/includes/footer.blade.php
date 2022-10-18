<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>





<div class="footer copy color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<p>&copy; <?= trans($theme.'-app.foot.rights') ?> </p>
			</div>

			@if(!empty(\Config::get('app.facebook', '')) || !empty(\Config::get('app.twitter', '')) || !empty(\Config::get('app.instagram', '')) || !empty(\Config::get('app.pinterest', '')))
			<div class="col-xs-12 col-sm-6 social-links">
				<span class="social-links-title"><?= trans($theme.'-app.foot.follow_us') ?></span>

				@if(!empty(\Config::get('app.facebook', '')))
				<a href="{{ (\Config::get('app.facebook')) }}" target="_blank" class="social-link color-letter"><i class="fab fa-2x fa-facebook-square"></i></a>
				&nbsp;
				@endif

				@if(!empty(\Config::get('app.twitter', '')))
				<a href="{{ (\Config::get('app.twitter')) }}" target="_blank" class="social-link color-letter"><i class="fab fa-2x fa-twitter-square"></i></a>
				&nbsp;
				@endif

				@if(!empty(\Config::get('app.instagram', '')))
				<a href="{{ (\Config::get('app.instagram')) }}" target="_blank" class="social-link color-letter"><i class="fab fa-2x fa-instagram"></i></a>
				&nbsp;
				@endif

				@if(!empty(\Config::get('app.pinterest', '')))
				<a href="{{ (\Config::get('app.pinterest')) }}" target="_blank" class="social-link color-letter"><i class="fab fa-2x fa-pinterest"></i></a>
				&nbsp;
				@endif

				<br>
			</div>
			@endif

			<div class="col-xs-12">
				<a class="color-letter" role="button"
					title="{{ trans($theme.'-app.foot.developedSoftware') }}"
					href="{{ trans($theme.'-app.foot.developed_url') }}"
					target="no_blank">{{ trans($theme.'-app.foot.developedBy') }}</a>
			</div>
		</div>
	</div>
</div>

@if (!Cookie::get("cookie_config"))
	@include("includes.cookie")
@endif

<script>
	let domain = window.location.hostname;
</script>

@if (empty($cookiesState['google']) && empty($cookiesState['all']))
<script>
	deleteGoogleCookies(domain);

	if(domain.includes('www')){
		deleteGoogleCookies(domain.split('www')[1]);
	}
</script>
@endif

@if (empty($cookiesState['facebook']) && empty($cookiesState['all']))
<script>
	deleteFacebookCookies(domain);

	if(domain.includes('www')){
		deleteFacebookCookies(domain.split('www')[1]);
	}
</script>
@endif
