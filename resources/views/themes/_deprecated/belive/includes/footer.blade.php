
<!--
<br><br>

<center><h2>{{ trans(\Config::get('app.theme').'-app.foot.discover_more')}}</h2></center>

<div id="logos" class="container">
	<a href="https://www.globalia.com" target="_blank" rel="nofollow">
		<img src="/themes/belive/assets/img/1.jpg" width="100%">
	</a>

	<a href="https://www.aireuropa.com/es/vuelos" target="_blank" rel="nofollow">
		<img src="/themes/belive/assets/img/2.jpg" width="100%">
	</a>

	<a href="https://www.halconviajes.com/" target="_blank" rel="nofollow">
		<img src="/themes/belive/assets/img/3.jpg" width="100%">
	</a>
	<a href="https://www.globalia.com" target="_blank" rel="nofollow">
		<img src="/themes/belive/assets/img/1.jpg" width="100%">
	</a>

	<a href="https://www.aireuropa.com/es/vuelos" target="_blank" rel="nofollow">
		<img src="/themes/belive/assets/img/2.jpg" width="100%">
	</a>

	<a href="https://www.halconviajes.com/" target="_blank" rel="nofollow">
		<img src="/themes/belive/assets/img/3.jpg" width="100%">
	</a>
</div>

<script>
	$('#logos').slick({
		infinite: true,
		slidesToShow: 4,
		slidesToScroll: 1,
		responsive: [
	    {
	      breakpoint: 768,
	      settings: {
	        slidesToShow: 1,
	        slidesToScroll: 1,
	        infinite: true,
	        dots: false,
	      }
	  	}],
	});
</script>

-->




<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container">
		<div class="row">

			<div class="col-xs-12 col-lg-9">
				<div class="row">
					<div class="col-xs-12 col-sm-3 text-center border">
						<ul class="ul-format footer-ul">

						<?php foreach(Config::get('app.locales') as $key => $value) { ?>
							<li>
								<a title="{{ trans(\Config::get('app.theme').'-app.home.' . $key)}}" class="footer-link" href="/<?=$key;?>">
									<span translate="no">{{ ucfirst(mb_strtolower(trans(\Config::get('app.theme').'-app.home.' . $key)))}}</span>
								</a>
							</li>

						<?php } ?>
						</ul>
					</div>
					<div class="col-xs-12 col-sm-3 text-center border">
						<!---<div class="footer-title">
							{{ trans(\Config::get('app.theme').'-app.foot.auctions') }}
						</div>  -->
						<ul class="ul-format footer-ul">
							<li>
									<a class="footer-link" href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
							</li>
							<li>
									<a class="footer-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a>
							</li>
						</ul>
					</div>

					<div class="col-xs-12 col-sm-3 text-center border">
						<!---<div class="footer-title">
							{{ trans(\Config::get('app.theme').'-app.foot.enterprise') }}
						</div> --->
						<ul class="ul-format footer-ul">
							<li><a class="footer-link" href="<?php echo trans(\Config::get('app.theme').'-app.links.about_us')  ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a></li>
							<li>
								<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
							</li>
							<!--<li><a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.faq')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.faq')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.faq')}}</span></a></li>-->
						</ul>
					</div>


					<div class="col-xs-12 col-sm-3 text-center border">

						<!---<div class="footer-title">
								{{ trans(\Config::get('app.theme').'-app.foot.term_condition')}}
						</div>-->
						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link" href="<?php echo trans(\Config::get('app.theme').'-app.links.legal_warning')?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a>
							</li>
							<li>
								<a class="footer-link" href="<?php echo trans(\Config::get('app.theme').'-app.links.privacy')?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a>
							</li>
							<li>
								<a class="footer-link" href="<?php echo trans(\Config::get('app.theme').'-app.links.cookies')?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a>
							</li>

						</ul>
					</div>
				</div>
			</div>

			<div class="col-xs-12 col-lg-3 text-center">
				<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
                <br>
                <div style="width:200px;margin: auto;">
	                <i class="fa fa-phone fa-2x" style="float:left;margin:5px 10px 0 0"></i>
	                <p style="margin:20px 0 0 10px;text-align: left">
	                    <small>{{ trans(\Config::get('app.theme').'-app.head.atencion_cliente')}}</small>
	                    <br/>
	                    <b><a href="tel:911360606" title="Atención al cliente" style="color:#FFF;font-size:20px;">911360606</a></b>
	                </p>
                </div>
                <br><br>
                <div class="text-center">
                    <ul>
                            <a href="https://twitter.com/belivehotels" title="Twitter Be Live Hotels" target="_blank" rel="nofollow" class="footer-link">
                                <span class="fab fa-twitter"></span>
                            </a>
                        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="https://www.facebook.com/BeLiveHotels/" title="Facebook Be Live Hotels" target="_blank" class="footer-link">
                                <span class="fab fa-facebook"></span>
                            </a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="https://www.youtube.com/user/BeLiveHotels" title="youtube Be Live Hotels" target="_blank" class="footer-link">
                                <span class="fab fa-youtube"></span>
                            </a>
                        	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="https://www.instagram.com/belivehotels/" title="instagram Be Live Hotels"
                               target="_blank" class="footer-link">
                                <span class="fab fa-instagram"></span>
                            </a>
                        </li>
                    </ul>
                </div>
			</div>
		</div>
		<br>
		<hr>
    </div>


	<div class="legal">

	    <p class="text-center">
            Be Live Hotels, S.L. con CIF B-38674792, con domicilio en C/Aguilar y Quesada, 1 - 38400 Santa Cruz de Tenerife - Tenerife - España.

            <br>
            {{ trans(\Config::get('app.theme').'-app.foot.commercial_register') }}
        </p>
    </div>
    <div class="links_foot">

		<a href="<?php echo trans(\Config::get('app.theme').'-app.links.about_us')  ?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a>
		&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="<?php echo trans(\Config::get('app.theme').'-app.links.cookies')?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a>
		&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="<?php echo trans(\Config::get('app.theme').'-app.links.privacy')?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a>
		&nbsp;&nbsp;|&nbsp;&nbsp;
		<a href="<?php echo trans(\Config::get('app.theme').'-app.links.legal_warning')?>" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.legal_warning') }}</a>
<!--
		<a href="https://www.belivehotels.com/es/informacion-general/" target="_self">Sobre Nosotros</a>
		<span class="hidden-md hidden-lg"><br></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
		<a href="https://www.belivehotels.com/es/informacion-general/cookies/" target="_self">Política de cookies</a>
		<span class="hidden-md hidden-lg"><br></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
		<a href="https://www.belivehotels.com/es/informacion-general/politica-privacidad/" target="_self">Política de privacidad</a>
		<span class="hidden-md hidden-lg"><br></span><span class="hidden-sm hidden-xs">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
		<a href="https://www.belivehotels.com/es/informacion-general/condiciones-generales/" target="_self">Aviso legal</a>
-->
    </div>

    <br>
    <center><small><a style="color: rgba(255,255,255,0.8);" class="color-letter" role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a></small></center>

</footer>


@if (!Cookie::get("cookie_law"))
	@include("includes.cookie")
<script>cookie_law();</script>
@endif
