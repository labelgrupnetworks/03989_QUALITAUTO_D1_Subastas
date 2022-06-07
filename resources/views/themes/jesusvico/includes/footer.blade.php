
<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>

	<div class="container-fluid">

		<div class="row">

			<div class="col-xs-12">
				<div class="row">

					<div class="col-xs-12 col-sm-12 col-lg-2 enterprise no-padding text-center">
						<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.jpg"  alt="{{(\Config::get( 'app.name' ))}}" width="90%"><br>
						<?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?><br>
						<?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?> <?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> , <?= !empty($empresa->pais_emp)? $empresa->pais_emp : ''; ?></br>
						<?= !empty($empresa->tel1_emp)? "Tels. $empresa->tel1_emp" : ''; ?><br>
						<a title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>" href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>">
							<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>
						</a>
					</div>

					<div class="col-xs-12 col-sm-12 col-lg-8">

						<div class="row">

							<div class="col-xs-12 col-lg-2  text-center">
								<div class="footer-title">
									Horario
								</div>
								De lunes a viernes,<br>de 9:30h a 18:00h

							</div>

							<div class="col-xs-12 col-lg-2 text-center">
								<div class="footer-title">
									{{ trans(\Config::get('app.theme').'-app.subastas.auctions')}}
								</div>
								<ul class="ul-format footer-ul">
									<li>
											<a class="footer-link" href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
									</li>
									<li>
										<a class="footer-link" href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a>
									</li>
								</ul>
							</div>

							<div class="col-xs-12 col-lg-2 text-center">
								<div class="footer-title">
									{{ trans(\Config::get('app.theme').'-app.login_register.empresa')}}
								</div>
								<ul class="ul-format footer-ul">
									<li>
										<a class="footer-link"
										title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}"
										href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us') }}">{{ trans(\Config::get('app.theme').'-app.foot.about_us')}}</a>
									</li>
									<li>
										<a class="footer-link"
										title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}"
										href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.team') }}">{{ trans(\Config::get('app.theme').'-app.foot.team')}}</a>
									</li>
									<li>
										<a class="footer-link"
										title="{{ trans(\Config::get('app.theme').'-app.foot.press')}}"
										href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.press') }}">{{ trans(\Config::get('app.theme').'-app.foot.press')}}</a>
									</li>
									<li>
										<a class="footer-link"
										title="{{ trans(\Config::get('app.theme').'-app.foot.ethical_code')}}"
										href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.ethical_code') }}">{{ trans(\Config::get('app.theme').'-app.foot.ethical_code')}}</a>
									</li>
								</ul>
							</div>

							<div class="col-xs-12 col-lg-3 text-center">

								<div class="footer-title">
										{{ trans(\Config::get('app.theme').'-app.foot.term_condition')}}
								</div>
								<ul class="ul-format footer-ul">
									<li>
										<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition')?>">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a>
									</li>
									<li>
										<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a>
									</li>
									<li>
										<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a>
									</li>
								</ul>
							</div>

							<div class="col-xs-12 col-lg-3 text-center">

								<div class="footer-title">
									Páginas
								</div>
								<ul class="ul-format footer-ul">
									<li>
										<a class="footer-link" title="{{ trans("$theme-app.foot.buy_coins") }}" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.buy_coins") }}">{{ trans("$theme-app.foot.buy_coins") }}</a>
									</li>
									<li>
										<a class="footer-link" title="{{ trans("$theme-app.foot.where_sell_coins") }}" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.where_sell_coins") }}">{{ trans("$theme-app.foot.where_sell_coins") }}</a>
									</li>
									<li>
										<a class="footer-link" title="{{ trans("$theme-app.foot.sell_old_coins") }}" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.sell_old_coins") }}">{{ trans("$theme-app.foot.sell_old_coins") }}</a>
									</li>
									<li>
										<a class="footer-link" title="{{ trans("$theme-app.foot.sell_coins_safely") }}" href="{{ Routing::translateSeo('pagina').trans("$theme-app.links.sell_coins_safely") }}">{{ trans("$theme-app.foot.sell_coins_safely") }}</a>
									</li>
								</ul>
							</div>

						</div>

					</div>



					<div class="col-xs-12 col-sm-12 col-lg-2 enterprise no-padding footer-title text-center">
						<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_numismatica.png"  alt="{{(\Config::get( 'app.name' ))}}" width="90%">
					</div>

				</div>
			</div>

		</div>

	</div>

</footer>








<div class="copy color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-4">
				<p>&copy; <?= trans(\Config::get('app.theme').'-app.foot.rights') ?>  </p>
			</div>

			<div class="col-xs-12 col-sm-4">
				<a class="color-letter" role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a>
			</div>

			<div class="col-xs-12 col-sm-4 social-links">
				<span class="social-links-title"><?= trans(\Config::get('app.theme').'-app.foot.follow_us') ?></span>

				<a class="social-link color-letter"><i class="fab fa-2x fa-facebook-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-twitter-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-instagram"></i></a>
				<br>
			</div>
		</div>
	</div>
</div>

@if (!Cookie::get("cookie_law"))
	@include("includes.cookie")
<script>cookie_law();</script>
@endif
