<header>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="items_top wrapper wrapper">
                    <div class="logo">
                        <?php
                            $lang = Config::get('app.locale');
                        ?>
                        <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}"></a>
                    </div>
                    <div class="header-left">
                        <ul class="items_top">
                            <li>
                                <div class="user_section">
                                    <div class="blockLogin">
                                        <a class="link" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact')?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a>
                                        @if(!Session::has('user'))

                                            <div class="user_section_divider">|</div>
                                            <a class="link" class="btn_login_desktop" title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>" href="{{ \Routing::slug('login') }}"><?= trans(\Config::get('app.theme').'-app.login_register.login') ?></a>
                                        @endif
                                    </div>
                                     @if(Session::has('user'))

                                        <div class="blockLogout">
                                            <a class="btn-xs btn btn-primary" href="{{ \Routing::slug('user/panel/orders') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</a>
                                            @if(Session::get('user.admin'))
                                                <a class="btn-xs btn btn-primary" href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a>
                                            @endif
                                            <a class="btn btn-danger btn-xs" href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a>
                                        </div>
                                     @endif
                                </div>
                            </li>
                            <li>
                                <div class="languaje">
                                    @if (\Config::get( 'app.enable_language_selector' ))
                                    <select
                                        id="selectorIdioma"
                                        actuallang="/{{ \App::getLocale() }}/"
                                        name="idioma"
                                        class="form-control"
                                        style="width:100px; height:27px; font-size:11px;"
                                    >
                                        <option value="es"><?= trans(\Config::get('app.theme').'-app.head.language_es') ?></option>
                                        <option value="en"><?= trans(\Config::get('app.theme').'-app.head.language_en') ?></option>
                                    </select>
                                    @elseif(\Config::get( 'app.google_translate' ))
                                        <div class="google_translate1">
                                            <div id="google_translate_element"></div>
                                        </div>
                                        <script type="text/javascript">
                                            function googleTranslateElementInit() {
                                                new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'en,es,de,fr,ru,zh-CN', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
                                            }
                                        </script>
                                        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                                    @endif
                                </div>
                            </li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>






<nav class="navbar navbar-default">
	<div class="container">
            <div class="row"><div class="col-xs-12 np ">
	  <div class="navbar-header visible-xs visible-sm">
	    <button id="btnResponsive" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">

	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	    </button>
	  </div>
	  <div id="navbar" class="hidden-xs hidden-sm np">
              <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 np">
	    <ul class="nav navbar-nav ">
                <li class="dropdown mega-dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}<span class="caret"></span></a>
                    <ul class="dropdown-menu ">
                       <?php

                                $subastaObj        = new \App\Models\Subasta();
                                $has_subasta = $subastaObj->auctionList ('S', 'W');
                                if(Session::get('user.admin')){
                                   $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
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
                            <li class="li-color"><a href="{{ \Routing::translateSeo('presenciales') }}?finished=false">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
                        @endif
                        @if(!empty($has_subasta) && $finished)
                            <li class="li-color"><a href="{{ \Routing::translateSeo('presenciales') }}?finished=true">{{ trans(\Config::get('app.theme').'-app.foot.auctions-finished')}}</a></li>
                        @endif
                        <?php
                        $has_subasta = $subastaObj->auctionList ('H');
                        ?>
                        @if(!empty($has_subasta))
                            <li><a class="dropdown-toggle" href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
                        @endif


                        </ul>
                    </li>
					<?php
						$has_subasta = $subastaObj->auctionList ('S', 'O');
						if(empty($has_subasta) && Session::get('user.admin')){
							$has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
						}
					?>
				  	@if(!empty($has_subasta))
						<li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
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

					<li><a title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  }}">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a></li>
                <li class="howSell">
                    <a class="title_info_open "title ="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy')}}</a>

                    <div class="divider hidden-xs">|</div>

                    <a class="title_info_open" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_sell')}}</a>
                </li>

				<li class="dropdown mega-dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{ trans(\Config::get('app.theme').'-app.foot.live_bidding')}}<span class="caret"></span></a>
					<ul class="dropdown-menu ">
						<?php
							$subastaObj = new \App\Models\Subasta();
                            $has_subasta = $subastaObj->auctionList ('S', 'W');
                            if(Session::get('user.admin')){
                            	$has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
							}

							$isLiveAuction = false;
							foreach ($has_subasta as $key => $subasta) {
								if(strtotime($subasta->session_end) > time()){
									$isLiveAuction = \Routing::translateSeo('api/subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
									break;
								}
							}
						?>

						@if ($isLiveAuction)
						<li class="li-color"><a href="{{ $isLiveAuction }}" target="_blank">{{ trans(\Config::get('app.theme').'-app.lot.bid_live')}}</a></li>
						@endif
						<li class="li-color"><a href="https://www.invaluable.com/auction-house/sala-de-ventas-kosq2fakok" target="_blank">Invaluable</a></li>
						<li class="li-color"><a href="https://beta.lot-tissimo.com/de-de/auction-catalogues/sala-de-ventas" target="_blank">Lot-Tissimo</a></li>
						<li class="li-color"><a href="https://www.the-saleroom.com/en-gb/auction-catalogues/sala-de-ventas" target="_blank">The saleroom</a></li>
						<li class="li-color"><a href="https://www.liveauctioneers.com/auctioneer/7387/sala-de-ventas-barcelona/" target="_blank">Live auctioneers</a></li>
					</ul>
				</li>

				<li><a href="{{ \Routing::translateSeo('blog')}} ">{{ trans(\Config::get('app.theme').'-app.blog.blogTitle') }}</a></li>






	    </ul>
              </div>
              <div class="search-menu col-xs-12 col-sm-3 col-md-3 col-lg-3 np">
                <form id="formsearch-responsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="">
                          <div class="form-group">
                                        <input class="form-control input-custom" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" id="textSearch">
                              <button type="submit" class="btn btn-custom-search" style="right:0px;"><i class="fa fa-search"></i></button>

                          </div>
                      </form>
              </div>
	  </div>
        </div>
</div>
	</div>
</nav>
<div id="menuResponsive" class="hidden-lg">
	<div class="me">
	  <a id="btnResponsiveClose" title="Cerrar" href="javascript:;">
	    <img src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png" alt="Cerrar">
	  </a>
	</div>
	<div class="clearfix"></div>
	<ul class="nav navbar-nav navbar-right navbar-responsive">
            <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li>
            <?php

                   $subastaObj        = new \App\Models\Subasta();
                   $has_subasta = $subastaObj->auctionList ('S', 'W');
                   if( empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                   }

                ?>
                @if(!empty($has_subasta))
                  <li><a href="{{ \Routing::translateSeo('presenciales') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
                @endif
                <?php
                    $has_subasta = $subastaObj->auctionList ('H');
                ?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a></li>
                @endif
                <?php
                  $has_subasta = $subastaObj->auctionList ('S', 'O');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
                   }
                ?>
                @if(!empty($has_subasta))
                    <li><a href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
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
				<li><a title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact')?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a></li>
				<li><a href="{{ \Routing::translateSeo('blog')}} ">{{ trans(\Config::get('app.theme').'-app.blog.blogTitle') }}</a></li>

	</ul>
</div>
