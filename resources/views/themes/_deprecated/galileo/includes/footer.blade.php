


<footer>
        <div class="container">
                <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-2 footer-module">
                              <div class="footer-title">
                                        {{ trans(\Config::get('app.theme').'-app.foot.auctions') }}
                                </div>
                                <ul class="ul-format footer-ul">
                                        <li>
                                            <a class="footer-link" href="{{ \Routing::translateSeo('subastas-presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
                                        </li>
                                        <li>
                                            <a class="footer-link" title=""  href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a>
                                        </li>



                                        <?php /*
                                        <li><a class="footer-link" href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
                                        <li><a class="footer-link" href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
                                        <li><a class="footer-link" href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
                                                *
                                                */
                                        ?>
                                </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2 footer-module">
                                <div class="footer-title">
                                        {{ trans(\Config::get('app.theme').'-app.foot.enterprise') }}
                                </div>
                                <ul class="ul-format footer-ul">
                                        <li><a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a></li>
                                        <li><a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}</a></li>
                                        <li><a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}</a></li>
                                        <li><a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.contact') }}" href="<?php echo Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>">{{ trans(\Config::get('app.theme').'-app.foot.contact') }}</a></li>
                                </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 footer-module">
                                <div class="footer-title">
                                        {{ trans(\Config::get('app.theme').'-app.foot.term_condition')}}
                                </div>
                                <ul class="ul-format footer-ul">
                                        <?php /*
                                        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a></li>
                                        */?>
                                        <li>
                                            <a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a></li>

                                        <li>
                                                <a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition')?>">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a>
                                        </li>
                                        <li>
                                                <a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a>
                                        </li>
                                </ul>
                        </div>
                        <div class="col-xs-12 col-md-5 footer-module module-address">
                                        <?php
                                        $empre= new \App\Models\Enterprise;
                                        $empresa = $empre->getEmpre();
                                     ?>
                        <div class="logo_footer">

                                <img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/img/logo_white.png"  alt="{{(\Config::get( 'app.name' ))}}">

                        </div>


                                                     <div class="footer-address-title">
                                                        <?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?> <br>
                                                        <?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?><br>
                                                        <?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?> <?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> , <?= !empty($empresa->pais_emp)? $empresa->pais_emp : ''; ?></br>

                                        </div>
                                        <ul class="ul-format footer-ul">
                                                <li>
                                                        <a class="footer-link footer-link-address" title="<?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?>" href=""><?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?></a>
                                                </li>
                                                <li>
                                                        <a class="footer-link footer-link-address" title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>" href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"><?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?></a>
												</li>
												<li>
													<a class="footer-link footer-link-address" role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a>
												</li>
                                        </ul>
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
                        <div class="col-xs-12 col-sm-6 social-link flex-display hide">
                                <div class="social-links-title"><?= trans(\Config::get('app.theme').'-app.foot.follow_us') ?></div>
                                <ul class="ul-format flex-display">
                                        <li>
                                                <a class="social-link color-letter">
                                                                <i class="fas fa-2x fa-linkedin-square" aria-hidden="true"></i>
                                                </a>
                                        </li>
                                        <li>
                                                        <a class="social-link color-letter">
                                                                        <i class="fas fa-2x fa-facebook-square" aria-hidden="true"></i>
                                                        </a>
                                        </li>
                                </ul>
			</div>
		</div>
	</div>
</div>
@if (!Cookie::get("cookie_law"))
    @include("includes.cookie")
<script>cookie_law();</script>
@endif
