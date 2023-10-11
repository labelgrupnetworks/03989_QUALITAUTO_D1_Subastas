<?php
                            $subastaObj        = new \App\Models\Subasta();

                        ?>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3 logo-footer">
                <?php
                    $empre= new \App\Models\Enterprise;
                    $empresa = $empre->getEmpre();
                ?>
                <img height="40" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png" alt="{{ trans(\Config::get('app.theme').'-app.home.name') }}">
                <div class="name-footer_sub_title"style="margin-top: 10px;"><?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?></div>
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
                        {{ trans(\Config::get('app.theme').'-app.foot.sells') }}
                    </div>
                    <ul class="links">
                        <?php
                            $has_subasta = $subastaObj->auctionList ('S', 'O');
                            if(empty($has_subasta) && Session::get('user.admin')){
                                $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
                            }
                            ?>
                            @if(!empty($has_subasta))
                                <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online')}}</a></li>
                            @endif

                            <?php
                                $has_subasta = $subastaObj->auctionList ('S', 'W');

                                if(empty($has_subasta) && Session::get('user.admin')){
                                     $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                                 }

                            ?>
                            @if(!empty($has_subasta))
                            <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.presenciales')}}</a></li>
                            @endif


                            <?php
                                $has_subasta = $subastaObj->auctionList ('S', 'V');
                                if(empty($has_subasta) && Session::get('user.admin')){
                                     $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'V'));
                                 }
                            ?>
                              @if(!empty($has_subasta))
                                  <li><a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
                              @endif
                            <?php
                            $has_subasta = $subastaObj->auctionList ('H');
                            ?>
                            @if(!empty($has_subasta))
                            <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
                            @endif

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
                        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a></li>
                        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a></li>
                        <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.aviso') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.aviso')?>">{{ trans(\Config::get('app.theme').'-app.foot.aviso') }}</a></li>

                    </ul>
                </div>
            </div>
            <div class="col-xs-12">
                <address>

                    <div class="address-email">
                        <a title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>" href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"><?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?></a>
                    </div>
					@if(!empty($empresa->tel1_emp))
                    <div class="address-phone">
                        <a href="tel:{{$empresa->tel1_emp}}">{{$empresa->tel1_emp}}</a>
                    </div>
					@endif

                    <?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?>
                    <?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?> <?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> , <?= !empty($empresa->pais_emp)? $empresa->pais_emp : ''; ?></br>
                </address>
            </div>
            <div class="col-xs-12 copy no-padding">
                <div class="col-xs-7">
					<p>&copy; <?= trans(\Config::get('app.theme').'-app.foot.rights') ?>  </p>
                </div>
                <div class="col-xs-5 footer-redes">
                    <div class="redes-title">
                        <span>{{(strtoupper(Config::get('app.locale')) == 'ES') ? "Síguenos" : "Follow us"}}</span>
                    </div>
                    <ul class="redes">
						<li><a title="Facebook" href="<?= Config::get('app.facebook') ?>"><i class="fa fa-facebook"></i></a></li>
						<li><a title="Linkedin" href="<?= Config::get('app.linkedin') ?>"><i class="fa fa-linkedin"></i></a></li>
						<li><a title="Instagram" href="<?= Config::get('app.instagram') ?>"><i class="fa fa-instagram"></i></a></li>
						<li><a title="Youtube" href="<?= Config::get('app.youtube') ?>"><i class="fa fa-youtube-play"></i></a></li>
                        <li>
							<a title="Twitter" href="<?= Config::get('app.twitter') ?>">
								@include('components.x-icon', ['size' => '20'])
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
</footer>
