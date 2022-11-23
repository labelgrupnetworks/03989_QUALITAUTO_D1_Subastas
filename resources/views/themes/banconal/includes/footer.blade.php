<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer class="menu-footer">
	<div class="container">
		<div class="row">

			<div class="col-xs-12 col-lg-7">
				<div class="row">

					<div class="col-xs-12  text-center">

						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.term_condition') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition')?>">{{ trans($theme.'-app.foot.term_condition') }}</a>
							</li>

						</ul>
					</div>
					@php	/*
					<div class="col-xs-12 col-sm-4 text-center">
						<ul class="ul-format footer-ul">

							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.privacy') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.privacy')?>">{{ trans($theme.'-app.foot.privacy') }}</a>
							</li>

						</ul>
					</div>

					<div class="col-xs-12 col-sm-4  text-center">


						<ul class="ul-format footer-ul">

							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.cookies') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.cookies')?>">{{ trans($theme.'-app.foot.cookies') }}</a>
							</li>
						</ul>
					</div>
					*/ @endphp

				</div>
			</div>
			<div class="col-xs-12 col-lg-5">
				<div class="row footer-title">
					<div class="col-xs-12 col-sm-5 image">
						<img class="logo-company" src="/themes/{{$theme}}/assets/img/logo_footer.png"
							alt="{{(\Config::get( 'app.name' ))}}" width="90%">
					</div>

				</div>
			</div>

		</div>
	</div>
</footer>






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
