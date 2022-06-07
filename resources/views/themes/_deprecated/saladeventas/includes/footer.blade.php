<footer>
<div class="container">
        <div class="row">
                <div class="col-xs-12 col-sm-3">
                        <div class="module_footer text-left">
                                 <?php
                                    $empre= new \App\Models\Enterprise;
                                    $empresa = $empre->getEmpre();
                                 ?>

                                <img height="33" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo-footer.png" alt="{{ trans(\Config::get('app.theme').'-app.home.name') }}" height="33">
                                <address>
                                         <?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?> <br>
                                         <?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?><br>
                                         <?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?> <?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> , <?= !empty($empresa->pais_emp)? $empresa->pais_emp : ''; ?></br>

                                         <?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?> <br>
                                         <?= trans(\Config::get('app.theme').'-app.foot.whatsapp_valorate') ?> <br>
                                         <a title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>" href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"><?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?></a>
                                </address>
                                <ul class="redes">
                                    <li><a title="Instagram" href="<?= Config::get('app.instagram') ?>" target="_blank"> <i class="fa fa-instagram"></i> Instagram </a></li>
                                    <?php /*
                                        <li><a title="Facebook" href="<?= Config::get('app.facebook') ?>"><i class="fa fa-facebook"></i></a></li>
                                        <li><a title="Twitter" href="<?= Config::get('app.twitter') ?>"><i class="fa fa-twitter"></i></a></li>
                                        <li><a title="Google Plus" href="<?= Config::get('app.googleplus') ?>"><i class="fa fa-google-plus"></i></a></li>

                                     */
                                    ?>
                                </ul>
                        </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                        <div class="module_footer">
                                <div class="tit_links">
                                        {{ trans(\Config::get('app.theme').'-app.foot.enterprise') }}
                                </div>
                                <ul class="links">
                                        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a></li>
                                        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.contact') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact') ?>">{{ trans(\Config::get('app.theme').'-app.foot.contact') }}</a></li>
                                </ul>
                        </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                        <div class="module_footer">
                                <div class="tit_links">
                                        {{ trans(\Config::get('app.theme').'-app.foot.auctions') }}
                                </div>
                                <ul class="links">
                                        <?php
                                           $subastaObj        = new \App\Models\Subasta();
                                           $has_subasta = $subastaObj->auctionList ('S');
                                           if(Session::get('user.admin')){
                                              $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A'));
                                           }
                                           $not_finished=false;
                                           $finished=false;
                                       ?>
                                       @foreach($has_subasta as $header_sub)
                                           @if(strtotime($header_sub->session_end) > time())
                                               <?php $not_finished = true?>
                                           @elseif(strtotime($header_sub->session_end) < time())
                                               <?php $finished = true?>
                                           @endif
                                       @endforeach
                                       @if(!empty($has_subasta) && $not_finished)
                                           <li class="li-color"><a href="{{ \Routing::translateSeo('todas-subastas') }}?finished=false">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
                                       @endif
                                       @if(!empty($has_subasta) && $finished)
                                           <li class="li-color"><a href="{{ \Routing::translateSeo('todas-subastas') }}?finished=true">{{ trans(\Config::get('app.theme').'-app.foot.auctions-finished')}}</a></li>
                                       @endif
                                        <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
                                        <?php /*
                                        <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
                                        <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
                                        <li><a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
                                         *
                                         */
                                        ?>
                                </ul>
                        </div>
                </div>
                <div class="col-xs-12 col-sm-3">
                        <div class="module_footer">
                                <div class="tit_links">
                                      {{ trans(\Config::get('app.theme').'-app.foot.term_condition')}}
                                </div>
                                <ul class="links">
                                    <?php /*
                                        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a></li>
                                        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a></li>
                                    */?>
                                     <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition')?>">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a></li>
                                     <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.faq')?>">{{ trans(\Config::get('app.theme').'-app.foot.faq') }}</a></li>
                                     <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a></li>
                                    <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a></li>

                                </ul>
                        </div>
                </div>
        </div>
</div>
    </footer>
<div class="copy">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12" style="display: flex; flex-wrap: wrap">
				<p style="margin: 15px auto 15px 0px;">
					<span>&copy; <?= trans(\Config::get('app.theme').'-app.foot.rights') ?></span>
				</p>
				<p style="margin: 15px 0px;"><a class="color-letter" role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a></p>
			</div>
		</div>
	</div>
</div>
@if (!Cookie::get("cookie_law"))
    @include("includes.cookie")
<script>cookie_law();</script>
@endif
