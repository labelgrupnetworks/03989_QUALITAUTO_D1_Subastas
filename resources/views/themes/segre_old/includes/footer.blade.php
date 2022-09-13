
<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-lg-7">


			</div>

			<div class="col-xs-12 col-lg-5">
				<div class="row footer-title">
					<div class="col-xs-12 col-sm-5 image">
						<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png"  alt="{{(\Config::get( 'app.name' ))}}" width="90%">
					</div>
					<div class="col-xs-12 col-sm-7 enterprise text-justify">
						<div class="row">
							<div class="col-xs-12 col-sm-6 no-padding">
								<b><?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?></b> <br>
								<?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?><br>
								<?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?> <?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> , <?= !empty($empresa->pais_emp)? $empresa->pais_emp : ''; ?></br>
							</div>
							<div class="col-xs-12 col-sm-6">
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
			<div class="col-xs-12 col-sm-6 social-links">
				<span class="social-links-title"><?= trans(\Config::get('app.theme').'-app.foot.follow_us') ?></span>

				<a class="social-link color-letter"><i class="fab fa-2x fa-facebook-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-twitter-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-instagram"></i></a>
				<br>
			</div>

			<div class="col-xs-12">
				<a class="color-letter" role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a>
			</div>
		</div>
	</div>
</div>

@if (!Cookie::get("cookie_config"))
	@include("includes.cookie")
<script>cookie_law();</script>
@endif
