<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-lg-9">
				<div class="row">
					<div class="col-xs-12 col-sm-3 text-center">
						<div class="footer-title">
							{{ trans(\Config::get('app.theme').'-app.foot.enterprise') }}
						</div>
						<ul class="ul-format footer-ul">
							<li><a class="footer-link"
									title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}"
									href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a>
							</li>
							<li>
								<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}"
									href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
							</li>
							<li><a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.faq')}}"
									href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.faq')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.faq')}}</span></a>
							</li>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-2 text-center">
						<div class="footer-title">
							{{ trans(\Config::get('app.theme').'-app.foot.catalogos') }}
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link"
									href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
							</li>
							<li>
								<a class="footer-link"
									href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a>
							</li>
							<li><a class="footer-link"
								href="https://www.sorolla.com/">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-4 text-center">

						<div class="footer-title">
							{{ trans(\Config::get('app.theme').'-app.foot.informacion')}}
						</div>
						<ul class="ul-format footer-ul">

							<li>
								<a class="footer-link"
									title="{{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}"
									href="{{ \Routing::translateSeo('valoracion-articulos') }}">{{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</a>
							</li>
							<li>
								<a class="footer-link"
									title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}"
									href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}</a>
							</li>
							<li>
								<a class="footer-link"
									title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}"
									href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell') }}">{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}</a>
							</li>
						</ul>
					</div>

					<div class="col-xs-12 col-sm-3 text-center">

						<div class="footer-title">
							{{ trans(\Config::get('app.theme').'-app.foot.term_condition')}}
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.aviso') }}"
									href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.aviso')?>">{{ trans(\Config::get('app.theme').'-app.foot.aviso') }}</a>
							</li>
							<li>
								<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.condiciones_uso') }}"
									href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.condiciones_uso')?>">{{ trans(\Config::get('app.theme').'-app.foot.condiciones_uso') }}</a>
							</li>

							<li>
								<a class="footer-link"
									title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}"
									href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a>
							</li>
							<li>
								<a class="footer-link"
									title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}"
									href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a>
							</li>
							<li>
								<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.dissent') }}"
									href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.dissent')?>">{{ trans(\Config::get('app.theme').'-app.foot.dissent') }}</a>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-lg-3">
				<div class="row footer-title">
					<div class="col-xs-12 col-sm-6 image">
						<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png"
							alt="{{(\Config::get( 'app.name' ))}}" width="90%">
					</div>
					<div class="col-xs-12 col-sm-6 enterprise text-justify">
						<div class="row">
							<div class="col-xs-12 no-padding">
								<b><?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?></b> <br>
								<?= !empty($empresa->dir_emp)? "C/ ". $empresa->dir_emp : ''; ?>.<br>
								<?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?>
								<?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> ,
								<?= !empty($empresa->pais_emp)? "($empresa->pais_emp)" : ''; ?></br>
							</div>
							<div class="col-xs-12 no-padding">
								<br><?= !empty($empresa->tel1_emp)? "(+34) ".$empresa->tel1_emp : ''; ?><br>
								<a title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"
									href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>">
									<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>
								</a>
							</div>
							<div class="col-xs-12 no-padding">
								<br><?= trans(\Config::get('app.theme').'-app.foot.horario') ?>
							</div>
						</div>
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
				<a class="color-letter" role="button"
					title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}"
					href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}"
					target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a>
			</div>

			<div class="col-xs-12 col-sm-6 social-links">
				<span class="social-links-title"><?= trans(\Config::get('app.theme').'-app.foot.follow_us') ?></span>

				<a class="social-link color-letter"><i class="fab fa-2x fa-facebook-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-twitter-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-instagram"></i></a>
				<br>
			</div>

		</div>

		<div class="row">
			<div class="col-xs-12 col-sm-12 text-center">
				<p>{{ trans(\Config::get('app.theme').'-app.foot.rights') }}</p>
			</div>
		</div>

	</div>
</div>

@if (!Cookie::get("cookie_config"))
@include("includes.cookie")
<script>
	cookie_law();
</script>
@endif
