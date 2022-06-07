<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8">
				<div class="row">

					<div class="col-xs-12 col-md-3 text-center">
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a>
					</div>

					<div class="col-xs-12 col-md-3 text-center">
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}</a>
					</div>

					<div class="col-xs-12 col-md-3 text-center">
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}</a>
					</div>

					<div class="col-xs-12 col-md-3 text-center">
						<a class="footer-link"
							href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a>
					</div>

				</div>

				<hr>

				<div class="row">
					<div class="col-xs-12 col-md-3 text-center">
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.conditions') }}"
							href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.conditions') ?>">{{ trans(\Config::get('app.theme').'-app.foot.conditions') }}</a>
					</div>

					<div class="col-xs-12 col-md-3 text-center">
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}"
							href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.privacy') ?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a>
					</div>

					<div class="col-xs-12 col-md-3 text-center">
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}"
							href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.cookies') ?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a>
					</div>



					<div class="col-xs-12 col-md-3 text-center">
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}"
							href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.term_condition') ?>">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a>
					</div>


				</div>
			</div>


			<div class="col-xs-12 col-md-2 text-center">
				<span class="hidden-lg hidden-md"><br><br><br><br></span>
				<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"
					alt="{{(\Config::get( 'app.name' ))}}">
				<span class="hidden-lg hidden-md"><br><br></span>
			</div>

			<div class="col-xs-12 col-md-2 text-center">

				<?php

	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();

?>

				<?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?> <br>
				<?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?><br>
				<?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?>
				<?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> ,
				<?= !empty($empresa->pais_emp)? $empresa->pais_emp : ''; ?></br>
				<?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?>
				<a class="footer-link footer-link-address"
					title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"
					href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"><?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?></a>
			</div>

		</div>

		<div class="row logos-img d-flex align-items-center flex-wrap">
			<div class="col-xs-12 col-md-3 mt-2">
				<a target="_blank" href="https://www.numisbids.com">
					<img class="img-responsive center-block" src="/themes/{{\Config::get('app.theme')}}/assets/img/NumisBids.png" alt="www.numisbids.com">
				</a>
			</div>
			<div class="col-xs-12 col-md-2 mt-2">
				<a target="_blank" href="https://www.biddr.com/">
					<img class="img-responsive center-block" src="/themes/{{\Config::get('app.theme')}}/assets/img/biddr.svg" alt="www.biddr.com/about/about">
				</a>
			</div>
			<div class="col-xs-12 col-md-2 mt-2">
				<a target="_blank" href="https://silicua.bidinside.com/">
					<img class="img-responsive center-block" src="/themes/{{\Config::get('app.theme')}}/assets/img/Logo-en.png" alt="www.bidinside.com">
				</a>
			</div>
			<div class="col-xs-12 col-md-2 mt-2">
				<a target="_blank" href="https://emax.bid/it/">
					<img class="img-responsive center-block" src="/themes/{{\Config::get('app.theme')}}/assets/img/emax-bid.png" alt="emax.bid/it/">
				</a>
			</div>
			<div class="col-xs-12 col-md-2 mt-2">
				<a target="_blank" href="https://www.anvar.es/">
					<img class="img-responsive center-block" src="/themes/{{\Config::get('app.theme')}}/assets/img/anvar.png" alt="www.anvar.es/">
				</a>
			</div>
			<div class="col-xs-12 col-md-1 mt-2">
				<a target="_blank" href="https://numisane.org/">
					<img class="img-responsive center-block" src="/themes/{{\Config::get('app.theme')}}/assets/img/logoANE.gif" alt="numisane.org/">
				</a>
			</div>
		</div>
	</div>
	</div>
</footer>
<div class="copy color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6">
				<p>&copy; <?= trans(\Config::get('app.theme').'-app.foot.rights') ?> </p>
			</div>
			<!--
						<div class="col-xs-12 col-sm-6 social-link flex-display">
								<div class="social-links-title"><?= trans(\Config::get('app.theme').'-app.foot.follow_us') ?></div>
								<ul class="ul-format flex-display">
									<li>
														<a class="social-link color-letter">
																		<i class="fab fa-2x fa-facebook-square"></i>
														</a>
										</li>
										<li>
														<a class="social-link color-letter">
																		<i class="fab fa-2x fa-twitter-square"></i>
														</a>
										</li>

										<li>
														<a class="social-link color-letter">
																		<i class="fab fa-2x fa-instagram"></i>
														</a>
										</li>
								</ul>
						</div>
					-->
			<div class="col-xs-12 ">
				<a class="color-letter" role="button"
					title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}"
					href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}"
					target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a>
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
