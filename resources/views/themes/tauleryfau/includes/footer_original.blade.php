<footer>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                 <img alt="<?= Config::get('app.name') ?>" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo-footer.png"  alt="{{(\Config::get( 'app.name' ))}}">
            </div>
            <div class="col-xs-12 footer-navbar">
                    <ul class="nav navbar">
                        <?php
                            $lang = Config::get('app.locale');
                        ?>
                            <li><a href="/{{$lang}}"><i class="fas fa-home"></i><?php /*trans(\Config::get('app.theme').'-app.home.home')*/ ?></a></li>
                            <li>
                                <a title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a>
                            </li>

                            <?php

                            $subastaObj        = new \App\Models\Subasta();
                            $has_subasta = $subastaObj->auctionList ('S', 'O');
                            if( empty($has_subasta) && Session::get('user.admin')){
                                $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                            }

                            ?>
                            @if(!empty($has_subasta))
                                <li class="auctions">
                                    <a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a>
                                </li>
                            @endif
                            <?php
                            $has_subasta = $subastaObj->auctionList ('S', 'P');
                            if(empty($has_subasta) && Session::get('user.admin')){
                                $has_subasta = $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'P'));
                            }
                            ?>
                            @if(!empty($has_subasta))
                                <li>
                                    <a href="{{ \Routing::translateSeo('subastas-permanentes') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a>
                                </li>
                            @endif
                            <?php
                            $url_lotes='';
                            $has_subasta = $subastaObj->auctionList ('S', 'V');
                            if(empty($has_subasta) && Session::get('user.admin')){
                                $has_subasta = $subastaObj->auctionList ('A', 'V');
                            }
                            if(!empty($has_subasta)){
                                 $url_lotes= \Tools::url_auction(head($has_subasta)->cod_sub,head($has_subasta)->name,head($has_subasta)->id_auc_sessions,head($has_subasta)->reference);
                            }
                        ?>
                            @if(!empty($has_subasta))
                            <li><a href="{{$url_lotes}}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
                            @endif
                            <li><a href="<?=\Routing::translateSeo('valoracion-articulos')?>">{{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</a></li>
                            <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}</a></li>
                            <li><a title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell') }}</a></li>
                            <li>
                                <a title="{{ trans(\Config::get('app.theme').'-app.foot.contact') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact') ?>">{{ trans(\Config::get('app.theme').'-app.foot.contact') }}</a>
                            </li>

                    </ul>
            </div>
            <div class="col-xs-12 col-sm-4 text-left">
                <p class="term-pol"><a>&copy; <?= trans(\Config::get('app.theme').'-app.foot.rights') ?></a></p>
            </div>
            <div class="col-xs-12 col-sm-8 text-right">
                <p>

                    <span class="term-pol" style="margin-right: 10px;">
                        <a title="{{ trans(\Config::get('app.theme').'-app.foot.shipping_terms') }}" href="/files/politica de envíos-2021.pdf" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.shipping_terms') }}</a>
                    </span>
                    <span class="term-pol" style="margin-right: 10px;">
                    <a  title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition_sub') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition_sub')?>">{{ trans(\Config::get('app.theme').'-app.foot.term_condition_sub') }}</a>
                    </span>
                    <span class="term-pol" style="margin-right: 10px;">
                    <a class="term-pol" title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition')?>">{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}</a>
					</span>
					<span class="term-pol">
						<a class="term-pol" title="{{ trans(\Config::get('app.theme').'-app.cookies.title') }}" href="{{route('cookieConfig', ['lang' => \Config::get('app.locale')])}}">{{ trans(\Config::get('app.theme').'-app.cookies.title') }}</a>
					</span>
                    </p>
                <p class="term-pol">
                </p>
            </div>
            <div class="col-xs-12 developedBy hidden">
                <a role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="<?= Config::get('app.developed_url') ?>" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy') }}</a>
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
<?php /*
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
            <iframe id="myFrame" width="100%" height="700" src="/files/POLITICA_ENVIOS_2019_2020.pdf">

            </iframe>

      </div>
    </div>
</div>
*/
?>
</footer>





