<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-lg-5">
				<div class="row ">
					<div class="col-xs-12 col-sm-5 image">
						<img class="logo-company" src="/themes/{{$theme}}/assets/img/logo_footer.png"
							alt="{{(\Config::get( 'app.name' ))}}" width="90%">
					</div>
					<div class="col-xs-12 col-sm-5 col-md-offset-2 enterprise text-justify">
						<div class="footer-address">
							<div class="row">
								<div class="footer-title col-xs-12">
									{{ trans($theme.'-app.foot.contact') }}
								</div>
								<div class="col-xs-12">

								<span>
								{{ trans("$theme-app.foot.address") }}<br>
								{{ trans("$theme-app.foot.zip_code") }}<br>
								</span>
								<a href="mailto:{{ trans("$theme-app.foot.email") }}">{{ trans("$theme-app.foot.email") }}</a><br><br>
								<strong>{{ trans("$theme-app.foot.phone_number") }}</strong>

								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-lg-7">
				<div class="row">
					<div class="col-xs-12 col-sm-2 text-center">

					</div>
					<div class="col-xs-12 col-sm-5 text-left">
						<div class="footer-title">
							Links
						</div>
						<ul class="ul-format footer-ul">

							<li>
								<a class="footer-link"
									href="{{\Routing::translateSeo("/subasta-actual") }}">{{ trans($theme.'-app.subastas.auctions')}}</a>
							</li>
							<li><a class="footer-link"  
								title="{{ trans($theme.'-app.foot.about_us') }}"
								href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.about_us')  ?>">{{ trans($theme.'-app.foot.about_us') }}</a>
						</li>


							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.general_conditions') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.general_conditions')?>">{{ trans($theme.'-app.foot.general_conditions') }}</a>
							</li>
							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.privacy') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.privacy')?>">{{ trans($theme.'-app.foot.privacy') }}</a>
							</li>
							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.aviso_legal') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.foot.aviso_legal_link')?>">{{ trans($theme.'-app.foot.aviso_legal') }}</a>
							</li>
						</ul>
					</div>

					<div class="col-xs-12 col-sm-5 text-left ">

						<div class="footer-title">
							{{ trans($theme.'-app.foot.follow_us')}}
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<a class="share-social-foot d-flex align-items-center justify-content-center color-letter" target="_blank"  href="https://www.facebook.com/Boutique-de-Lolo-102017296036941"><i class="fa fa-facebook-f"></i></a>
							</li>
							<li>
								<a class="share-social-foot d-flex align-items-center justify-content-center color-letter" target="_blank" href="https://www.instagram.com/boutiquedelolomadrid"><i class="fa fa-instagram"></i></a>
							</li>



						</ul>
					</div>
				</div>
			</div>


		</div>
	</div>
</footer>








<div class="copy color-letter">
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
