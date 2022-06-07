<?php
    $lang = Config::get('app.locale');
?>
<div class="top-info">
    <div class="container">
        
        <div class="row mini-menu ">

            <div class="search float-r pull-right" id="minimenu-idiomas">

                
                <div class="bloque">
                    <a href="/es" style="margin-right:5px;"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/lang_es.png" alt=""></a>
                    <a href="/en" style="margin-right:5px;"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/lang_en.png" alt=""></a>
                    <a href="/cn" style="margin-right:5px;"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/lang_cn.png" alt=""></a>
                </div>

                <div class="bloque">
                    <span>T: +34 91 577 87 97</span>
                    <br>
                    <span>M: +34 717 79 17 39</span>
                    <br>
                    <span><img src="/themes/{{\Config::get('app.theme')}}/assets/img/ico-whatsapp.png" alt="WhatsApp" width="11"> +34 616 095 044</span>
                </div>

                <div class="bloque">
                    <a title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}" href="<?php echo \Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')?>"><img src="/themes/{{\Config::get('app.theme')}}/assets/img/pagoonline.jpg" alt="" height="11"></a>
                </div>
            </div>
        </div>
    </div>
</div>

<header>
    <div class="container">
    <nav class="menu-header row">
        <div class="menu-responsive hidden-lg">
            <div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter ">{{ trans(\Config::get('app.theme').'-app.head.menu') }}</div>
        </div>
        <div class="logo-header">
            <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
                <img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
            </a>
        </div>
        <div class="menu-principal">
            
            <ul class="menu-principal-content d-flex justify-content-center align-items-center hidden-xs hidden-sm hidden-md">
                <span role="button" class="close-menu-reponsive hidden-lg">{{ trans(\Config::get('app.theme').'-app.head.close') }}</span>
                <?php //   <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li> ?>
                    <li class="flex-display">
                        <a class="color-letter flex-display link-header justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">
                            <span>{{ trans(\Config::get('app.theme').'-app.home.home')}}</span>
                        </a>
                    </li>
                <?php
                   $subastaObj        = new \App\Models\Subasta();
                   $has_subasta = $subastaObj->auctionList ('S', 'W');
                   if( empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));
                   }
                   
                ?>
                @if(!empty($has_subasta))
                  <li>
                      <a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::translateSeo('presenciales') }}">
                        <span>{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</span>
                    </a>
                    </li>
                @endif
                
                <?php
                  $has_subasta = $subastaObj->auctionList ('S', 'O');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
                   }
                ?>
                @if(!empty($has_subasta))
                    <li>
                        <a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-online') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</span></a>
                    </li>
                @endif
                <?php
                  $has_subasta = $subastaObj->auctionList ('S', 'V');
                  if(empty($has_subasta) && Session::get('user.admin')){
                       $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'V'));
                   }
                ?>
                @if(!empty($has_subasta))
                    <li><a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('venta-directa') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</span></a></li>
                @endif
                <?php
                    $has_subasta = $subastaObj->auctionList ('H');
                ?>
                @if(!empty($has_subasta))
                    <li>
                        <a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-historicas') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</span>
                        </a>
                    </li>
                @endif
               <?php /*
                *    <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>
	    
                * */
                  ?>
	      <li>
                  <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('calendar') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.calendar')}}</span></a>
                </li>
                 <li>
                    <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('valoracion-articulos') }}"><span> {{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</span></a>
                </li>
                <li>
                    <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
                </li>
                <li>
                  @if(!Session::has('user'))
                    <div class="user-account-login">
                        <a class="flex-display justify-center align-items-center btn_login_desktop btn_login color-letter" title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>" href="javascript:;">
                                <?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
                        </a>
                    </div> 
                    @else
                    
                    <!-- PENDIENTE -->
                    <div class="user-account-login my-account mega-menu">
                            <a class="flex-display justify-center align-items-center btn_login_desktop btn_login color-letter" href="{{ \Routing::slug('user/panel/orders') }}" >
                                {{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}
                                
                            </a>
                            @if(Session::get('user.admin'))
                                <a class="color-letter" href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a>
                            @endif
                            <a class="color-letter" href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a>
                    </div>
                    
                    
                    <!-- ORIGINAL
                    <a class="my-account color-letter" >
                        <img width="25px;" class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/user.png"  alt="{{(\Config::get( 'app.name' ))}}">
                        <span class="hidden-xs">{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</span>
                        
                    </a>
                    <div class="mega-menu background-body rigth-0">
                            <a class="color-letter" href="{{ \Routing::slug('user/panel/orders') }}" >
                                {{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}
                                
                            </a>
                            @if(Session::get('user.admin'))
                                <a class="color-letter" href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a>
                            @endif
                            <a class="color-letter" href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a>
                        </div>
                    -->
            @endif  
                </li>
            </ul>
        </div>
    </nav>
        </div>

</header>

<div class="menu-principal-search d-flex align-items-center justify-content-center">
        <form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form flex-inline position-relative">
            <div class="form-group">
                <input class="form-control input-custom br-100" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" />
            </div>
            <button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans(\Config::get('app.theme').'-app.head.search_button') }}</button>
        </form>
</div>

<div class="login_desktop" style="display: none" >
    <div class="login_desktop_content">
        <div class="only-login white-background">  
            <div class="login-content-form">
            <img class="closedd" role="button" src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png" alt="Close">   
            <div class="login_desktop_title">
                <?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
            </div>          
            <form data-toggle="validator" id="accerder-user-form" class="flex-display justify-center align-items-center flex-column">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="input-login-group">
                        <i class="fa fa-user"></i>
                        <input class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.user') }}" type="email" name="email" type="text">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="input-login-group">
                        <i class="fa fa-key"></i>
                        <input class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20">
                        <img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                    </div>
                </div>
                <span class="message-error-log text-danger seo_h5"></span></p>
                <div class="pass-login-content">
                    <div class="text-center">
                    <button id="accerder-user" class="button-principal" type="button">
                        <div>{{ trans(\Config::get('app.theme').'-app.login_register.acceder') }}</div>
                    </button>
                    </div>
                    <a onclick="cerrarLogin();" class="c_bordered pass_recovery_login" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}</a>

                </div>
            </form>
            <div class="login-separator"></div>
            <p class="text-center">{{ trans(\Config::get('app.theme').'-app.login_register.not_account') }}</p>
            <div class="create-account-link">
                <a class="" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('login') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a>
            </div>
        </div>
    </div>
    </div>

</div>

<div style="background:#FFFDF0">
    <br><br>
</div>
<br>