<footer>
<div class="container">
        <div class="row">
                <div class="col-xs-12 col-sm-2">
                        <div class="module_footer text-left">
                                <img class="img-responsive" src="/themes/{{$theme}}/img/logo.png" alt="{{ trans($theme.'-app.home.name') }}">
                                <address>
									{{ $global['company']->nom_emp }}<br>
									{{ $global['company']->dir_emp }}<br>
									{{ $global['company']->cp_emp }} {{ $global['company']->pob_emp }}, {{ $global['company']->pais_emp }}<br>
									{{ $global['company']->tel1_emp }} - <a title="{{ $global['company']->email_emp }}" href="mailto:{{ $global['company']->email_emp }}">{{ $global['company']->email_emp }}</a>
                                </address>
                                <ul class="redes">
                                        <li><a title="Facebook" href="<?= Config::get('app.facebook') ?>"><i class="fa fa-facebook"></i></a></li>
                                        <li>
											<a title="Twitter" href="<?= Config::get('app.twitter') ?>">
												@include('components.x-icon', ['size' => '14'])
											</a>
										</li>
                                </ul>
                        </div>
                </div>
                <div class="col-xs-12 col-sm-2">
                        <div class="module_footer">
                                <div class="tit_links">
                                        {{ trans($theme.'-app.foot.enterprise') }}
                                </div>
                                <ul class="links">
                                        <li><a title="{{ trans($theme.'-app.foot.about_us') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.about_us')  ?>">{{ trans($theme.'-app.foot.about_us') }}</a></li>
                                        <li><a title="{{ trans($theme.'-app.foot.contact') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.contact') ?>">{{ trans($theme.'-app.foot.contact') }}</a></li>
                                </ul>
                        </div>
                </div>

                <div class="col-xs-12 col-sm-2">
                        <div class="module_footer">
                                <div class="tit_links">
                                        {{ trans($theme.'-app.foot.auctions') }}
                                </div>
                                <ul class="links">
                                        <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans($theme.'-app.foot.auctions')}}</a></li>
                                        <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a></li>
                                        <?php /*
                                        <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans($theme.'-app.foot.auctions')}}</a></li>
                                        <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans($theme.'-app.foot.online_auction')}}</a></li>
                                        <li><a href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans($theme.'-app.foot.direct_sale')}}</a></li>
                                         *
                                         */
                                        ?>
                                </ul>
                        </div>
                    </div>
<div class="col-xs-12 col-sm-3">
                        <div class="module_footer">
                                <div class="tit_links">
                                      {{ trans($theme.'-app.foot.term_condition')}}
                                </div>
                                <ul class="links">
                                    <?php /*
                                        <li><a title="{{ trans($theme.'-app.foot.privacy') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.privacy')?>">{{ trans($theme.'-app.foot.privacy') }}</a></li>
                                        <li><a title="{{ trans($theme.'-app.foot.cookies') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.cookies')?>">{{ trans($theme.'-app.foot.cookies') }}</a></li>
                                    */?>
                                     <li><a title="{{ trans($theme.'-app.foot.normas') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition')?>">{{ trans($theme.'-app.foot.normas') }}</a></li>
                                    <li><a title="{{ trans($theme.'-app.foot.how_to_buy') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy')?>">{{ trans($theme.'-app.foot.how_to_buy') }}</a></li>
									<li><a title="{{ trans($theme.'-app.foot.aviso_legal') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.aviso_legal')?>">{{ trans($theme.'-app.foot.aviso_legal') }}</a></li>
									<li>
										<button class="footer-link footer-link-button" type="button" data-toggle="modal" data-target="#cookiesPersonalize">
											{{ trans("$theme-app.cookies.configure") }}
										</button>
									</li>
                                </ul>
                        </div>
                </div>
            @if(Session::has('user'))
                            <div class="col-xs-12 col-sm-3">
                        <div class="module_footer">
                                <div class="tit_links">
                                      {{ trans($theme.'-app.user_panel.mi_cuenta') }}
                                </div>
                                <ul class="links">
                                    <?php /*
                                        <li><a title="{{ trans($theme.'-app.foot.privacy') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.privacy')?>">{{ trans($theme.'-app.foot.privacy') }}</a></li>
                                        <li><a title="{{ trans($theme.'-app.foot.cookies') }}" href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.cookies')?>">{{ trans($theme.'-app.foot.cookies') }}</a></li>
                                    */?>
                                        <li  role="presentation"><a data-title="{{ trans($theme.'-app.panel.my_orders') }}" href="{{ \Routing::slug('user/panel/orders') }}">{{ trans($theme.'-app.user_panel.orders') }}</a></li>
                                        <li  role="presentation" ><a href="{{ \Routing::slug('user/panel/favorites') }}" data-title="{{ trans($theme.'-app.panel.my_adj') }}" >{{ trans($theme.'-app.user_panel.favorites') }}</a></li>
                                        <li  role="presentation"><a data-title="{{ trans($theme.'-app.panel.my_adj') }}" href="{{ \Routing::slug('user/panel/allotments') }}/N" >{{ trans($theme.'-app.user_panel.allotments') }}</a></li>


                                </ul>
                        </div>

        </div>
            @endif
        </div>

</div>
    </footer>
<div class="copy">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12" style="display: flex; flex-wrap: wrap">
				<p style="margin: 5px auto 5px 0px;">
					<span>&copy; <?= trans($theme.'-app.foot.rights') ?></span>
				</p>
				<p style="margin: 5px 0px;"><a class="color-letter" role="button" title="{{ trans($theme.'-app.foot.developedSoftware') }}" href="{{ trans($theme.'-app.foot.developed_url') }}" target="no_blank">{{ trans($theme.'-app.foot.developedBy') }}</a></p>
			</div>
		</div>
	</div>
</div>


@if (!Cookie::get((new App\Models\Cookies)->getCookieName()))
	@include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
