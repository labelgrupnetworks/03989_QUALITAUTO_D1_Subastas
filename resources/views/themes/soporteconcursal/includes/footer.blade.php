
<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-lg-7">
				<div class="row">
					<div class="col-xs-12 col-sm-3 text-center">
						<div class="footer-title">
							{{ trans(\Config::get('app.theme').'-app.foot.auctions') }}
						</div>
						<ul class="ul-format footer-ul">
							<li>
									<a class="footer-link" href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
							</li>
							<li>
									<a class="footer-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a>
							</li>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-4 text-center">
						<div class="footer-title">
							{{ trans(\Config::get('app.theme').'-app.foot.enterprise') }}
						</div>
						<ul class="ul-format footer-ul">
							<li><a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a></li>
							<li>
								<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
							</li>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-5 text-center">

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
				</div>
			</div>

			<div class="col-xs-12 col-lg-5">
				<div class="row footer-title">
					<div class="col-xs-12 col-sm-5 image">
						<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png?a=1"  alt="{{(\Config::get( 'app.name' ))}}" width="90%">
					</div>
					<div class="col-xs-12 col-sm-7 enterprise text-justify">
						<div class="row">
							<div class="col-xs-12">
								<b><?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?></b> <br>
								<?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?><br>
								<?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?> <?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?>
								<br><?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?><br>
								<a title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>" href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>">
									<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>
								</a>
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
				<p>&copy; <?= trans(\Config::get('app.theme').'-app.foot.rights') ?>  </p>
			</div>

			{{--
			<div class="col-xs-12">
				<a class="color-letter" role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a>
			</div>
			--}}
		</div>
	</div>
</div>

@if (!Cookie::get("cookie_config"))
    @include("includes.cookie")
@endif
