<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div style=" background: #b3b3b3;height: 1px;"></div>
	<div class="container-fluid">
		<div class="row mb-2 mt-2">
			<div class="col-xs-12 col-md-3 img-container">
				<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"
							alt="{{(\Config::get( 'app.name' ))}}" width="90%">
			</div>
			<div class="col-xs-12 col-md-9">
				<?php
				#Quito los enlaces del pie que no pitan much oy se ven muy descolocados
				/*
				<ul class="ul-format footer-ul">
					<li>
						<a class="footer-link"
							href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
					</li>
					<li>
						<a class="footer-link"
							href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a>
					</li>
				</ul>
				*/
				?>
			</div>

		</div>
	</div>

</footer>





<div class="signature">
	<div class="container-fluid">
		<div class="row signature-container">
			<div class="col-xs-12 col-md-5">
				<p><span class="text-transform-original">{{ $empresa->dir_emp ?? '' }}</span>
					{{ $empresa->cp_emp ?? '' }} {{ $empresa->pob_emp ?? '' }} - <span
						class="text-transform-original">{{ trans(\Config::get('app.theme').'-app.foot.horario_contacto')}}</span>
				</p>


			</div>
			<div class="col-xs-12 col-md-7 social-links ">
				<ul class="ul-format footer-ul">
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.rights') }}">{{ trans(\Config::get('app.theme').'-app.foot.rights')." ".date("Y") }}</a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition')?>">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a>
					</li>
				</ul>

			</div>

		</div>
	</div>
</div>

@if (!Cookie::get("cookie_config"))
@include("includes.cookie")
@endif
