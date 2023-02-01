<?php

use App\libs\TradLib as TradLib;

    $lang = Config::get('app.locale');

    $registration_disabled = Config::get('app.registration_disabled');
    $fullname = Session::get('user.name');
    if(strpos($fullname, ',')){
        $str = explode(",", $fullname);
        $name = $str[1];
    }else{
        $name = $fullname;
	}

	$fgortsec0 = new App\Models\V5\FgOrtsec0();
	$langComplete = \Tools::getLanguageComplete(\Config::get('app.locale'));
	$categories = $fgortsec0->GetAllFgOrtsec0()->get()->toarray();

?>
<header>
    <nav class="menu-header">
        <div class="menu-responsive hidden-lg">
            <div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter ">{{ trans(\Config::get('app.theme').'-app.head.menu') }}</div>
        </div>
        <div class="logo-header">
            <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
                <img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png?a=1"  alt="{{(\Config::get( 'app.name' ))}}">
            </a>
        </div>
        <div class="menu-principal">

            <ul class="menu-principal-content d-flex justify-content-center align-items-center">
                <span role="button" class="close-menu-reponsive hidden-lg">{{ trans(\Config::get('app.theme').'-app.head.close') }}</span>
                <?php //   <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li> ?>
                    <li class="flex-display">
                        <a class="color-letter flex-display link-header justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">
                            <span>{{ trans(\Config::get('app.theme').'-app.home.home')}}</span>
                        </a>
					</li>
					<li>
						<a class="color-letter d-flex link-header justify-content-center align-items-center category-button" onclick="javascript:categoryToogle();"  href="#"><span>{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</span></a>
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
				   // $has_subasta = $subastaObj->auctionList ('H');
				   $has_subasta =null;
                ?>
                @if(!empty($has_subasta))
                    <li>
                        <a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-historicas') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</span>
                        </a>
                    </li>
				@endif

               <?php /*
                *    <li><a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</a></li>


					<li>
						<a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('calendar') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.calendar')}}</span></a>
					</li>
					<li>
						<a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('valoracion-articulos') }}"><span> {{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</span></a>
					</li>
				 * */
				 ?>
                <li>
                    <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
                </li>
            </ul>
        </div>
        <div class="search-header-container  d-flex justify-content-center align-items-center hidden-xs" role="button">
                <div class="search-header d-flex justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.17 29.861">
                    <defs>
                      <style>
                        .cls-1 {
                          fill: #FFFFFF;
                        }
                      </style>
                    </defs>
                    <g id="magnifying-glass" transform="translate(-7.254)">
                      <path id="Path_1" data-name="Path 1" class="cls-1" d="M36.055,27.715l-6.7-6.7a12.612,12.612,0,1,0-9.441,4.3,12.545,12.545,0,0,0,7.6-2.594l6.765,6.767a1.258,1.258,0,0,0,1.779-1.778ZM9.769,12.661A10.147,10.147,0,1,1,19.916,22.805,10.16,10.16,0,0,1,9.769,12.661Z"/>
                    </g>
                  </svg>
                </div>
                <div class="search-header-close d-flex justify-content-center align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33.697 33.544">
                            <defs>
                              <style>
                                .close-svg {
                                  fill: #fff;
                                }
                              </style>
                            </defs>
                            <g id="cancel" transform="translate(0 -0.435)">
                              <path id="Path_27" data-name="Path 27" class="close-svg" d="M18.993,17.284,33.238,3.039a1.481,1.481,0,0,0,0-2.144,1.481,1.481,0,0,0-2.144,0L16.849,15.139,2.6.894a1.481,1.481,0,0,0-2.144,0,1.481,1.481,0,0,0,0,2.144L14.7,17.284.459,31.528a1.481,1.481,0,0,0,0,2.144,1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306L16.848,19.428,31.093,33.673a1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306a1.481,1.481,0,0,0,0-2.144Z" transform="translate(0 0)"/>
                            </g>
                          </svg>
                        </div>

        </div>
        <div class="user-account">
                @if(!Session::has('user'))
                    <div class="user-account-login">
                        <a class="flex-display justify-center align-items-center btn_login_desktop btn_login" title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>" href="javascript:;">
                                <?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
                        </a>
                    </div>
                    @else
                    <div class="my-account color-letter-header">
                        <div class="row d-flex align-items-center h-100">
                            <div class="col-xs-3 text-center">
                                <img width="25" height="25" src="/themes/{{\Config::get('app.theme')}}/assets/img/user.png"  alt="{{(\Config::get( 'app.name' ))}}">
                            </div>
                            <div class="col-xs-9 text-center">
                        @if(!empty($name))
                                <div class="hidden-xs" style='font-size: 11px'><b><?= $name ?></b></div>
                        @endif
                            <span class="hidden-xs">{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</span>
                            </div>
                        </div>

                    <div class="mega-menu background-body rigth-0">
                        <a class="color-letter" href="{{ \Routing::slug('user/panel/orders') }}" >
                            {{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}

                        </a>
                        @if(Session::get('user.admin'))
                            <a class="color-letter" href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a>
                        @endif
                        <a class="color-letter" href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a>
                    </div>
                    </div>
            @endif
        </div>
    </nav>
</header>

<div class="header_categories">

	<span class="hidden-lg">
		<a href="javascript:categoryToogle()" class="btn btn-default w100">Cerrar</a>
		<br>
	</span>

	<a title="{{trans(\Config::get('app.theme').'-app.home.all_categories')}}" href='{{ route("allCategories") }}' class="title mb-2">
		<strong>{{trans(\Config::get('app.theme').'-app.home.all_categories')}}</strong>
	</a>
	@foreach ($categories as $k => $category)
	<div class="d-flex align-items-center mb-2">
		<img style="margin-right: 5px" src="/themes/{{Config::get("app.theme")}}/assets/category/category_{{$category["lin_ortsec0"]}}.png" alt="{{$category["des_ortsec0"]}}" width="25" height="auto">
		<strong>
		<a title="{!! $category["des_ortsec0"] !!}" href='{{ route("category",array( "category" => $category["key_ortsec0"])) }}' class="title">
			   {{$category["des_ortsec0"]}}
		</a>
		</strong>
	</div>
	@endforeach

</div>
@if (!empty(\Config::get("app.gridLots")) && \Config::get("app.gridLots") =="new" )
<div class="menu-principal-search d-flex align-items-center justify-content-center">
        <form id="formsearchResponsive" role="search" action="{{ route('allCategories') }}" class="search-component-form flex-inline position-relative">
            <div class="form-group">
                <input class="form-control input-custom br-100" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="description" />
            </div>
            <button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans(\Config::get('app.theme').'-app.head.search_button') }}</button>
        </form>
</div>
@else
<div class="menu-principal-search d-flex align-items-center justify-content-center" style="display: none">
	<form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form flex-inline position-relative">
		<div class="form-group">
			<input class="form-control input-custom br-100" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" />
		</div>
		<button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans(\Config::get('app.theme').'-app.head.search_button') }}</button>
	</form>
</div>
@endif

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
                @if(empty($registration_disabled))
                <a class="" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a>
                @else
                <p class="text-center" style="color: darkred;">{{ trans(\Config::get('app.theme').'-app.login_register.registration_disabled') }}</p>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>


<script>

</script>
