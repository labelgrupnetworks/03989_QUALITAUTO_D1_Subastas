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
?>

<div class="lang-selection">
    <div class="social-container">
        <div class="row">
            <div class="col-xs-12 text-right d-flex justify-content-flex-end">

				<?php foreach(Config::get('app.locales') as $key => $value) { ?>
                        <ul class="ul-format list-lang d-inline-flex">

                            @if(\App::getLocale() != $key)
                            <?php
                            	#Obtener la ruta en el idioma contrario segun las tablas seo y/o traducciones links
                            	$ruta = TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), \App::getLocale(), $key);
                            ?>
                                <li>
                                    <a translate="no" title="<?= trans(\Config::get('app.theme').'-app.head.language_es') ?>" class="link-lang color-letter" href="/<?=$key . $ruta;?>">
                                        <span translate="no">{{ trans(\Config::get('app.theme').'-app.home.' . $key)}}</span>
                                    </a>
                                </li>
                            @else
                            <li>
                                <a translate="no" title="<?= trans(\Config::get('app.theme').'-app.head.language_es') ?>" class="link-lang active color-letter">
                                    <span translate="no">{{ trans(\Config::get('app.theme').'-app.home.' . $key)}}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                        <?php }  ?>
                        <ul  class="ul-format list-lang d-inline-flex redes-sociales">
                            <li class="facebook">
                                <a href="https://www.facebook.com/duran.subastas" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li class="instagram">
                                <a href="https://instagram.com/duransubastas/"  target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            <li class="twitter">
                                <a href="https://twitter.com/duransubastas"  target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                            <li class="youtube">
                                <a href="https://www.youtube.com/channel/UCKWEKBgBba5RGYaDRiSdHDA/videos"  target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                            <li class="email">
                                <a href="mailto:duran@duran-subastas.com"  target="_blank">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </li>
                        </ul>





            </div>
        </div>
    </div>
</div>

<header>
    <nav class="menu-header">
        <div class="logo-header">
            <a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
                <img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
            </a>
        </div>
        <div class="menu-principal">

            <ul class="menu-principal-content d-flex justify-content-left align-items-center">
                <img class="logo-company visible-xs-block" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png"  alt="{{(\Config::get( 'app.name' ))}}" width="90%">
                <span role="button" class="close-menu-reponsive hidden-lg"><i class="fas fa-times"></i></span>

                <?php
                   $subastaObj        = new \App\Models\Subasta();
                   $has_subasta = $subastaObj->auctionList ('S', 'W');
                   if(  Session::get('user.admin')){
					   $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'W'));

                   }

                ?>
            		<?php //    @if(!empty($has_subasta)) ?>
                  <li>
                      <a class="color-letter d-flex link-header align-items-center ">
                        <span>{{trans(\Config::get('app.theme').'-app.foot.auctions') }}</span>
                    	</a>
						@php
						/*
							<div class="submenu pt-3 pb-3">
								<div class="container">
									<div class="row">
										<div class="hidden-xs hidden-sm hidden-md col-lg-1 no-padding"></div>
										<div class="col-xs-12 col-lg-4 no-padding auctions-list-menu">
											@foreach($has_subasta as $subasta)
												@if($subasta->reference == '001')
													<a href="{{ \Tools::url_auction($subasta->cod_sub,$subasta->des_sub,$subasta->id_auc_sessions, $subasta->reference) }}">{{$subasta->des_sub}}</a>
													<br>
												@endif
											@endforeach
											<a  href="/es/subasta/subasta-solo-online-duran_7501-001">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}} </a>
											<br>
											<a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.metas.title_historic') }}</a>

										</div>

									</div>
								</div>
							</div>
						*/
						@endphp
						 <div class="submenuDuran ">
							<div  >
								@foreach($has_subasta as $subasta)
									@if($subasta->reference == '001')
									<div class="categoryOption"> 	<a href="{{ \Tools::url_auction($subasta->cod_sub,$subasta->des_sub,$subasta->id_auc_sessions, $subasta->reference) }}">{{$subasta->des_sub}}</a> </div>
									@endif
								@endforeach
								<div class="categoryOption"> <a  href="/<?= \Config::get("app.locale") ?>/subasta/subasta-solo-online-duran_7501-001?order=orden_asc">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}} </a> </div>
									<div class="categoryOption"> <a href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.metas.title_historic') }}</a> </div>
							</div>
					  </div>
                    </li>


 				<li><a class="color-letter flex-display link-header justify-center align-items-center" href="/<?= \Config::get("app.locale") ?>/subasta/tienda-online_7500-001?order=orden_desc"><span>{{  trans(\Config::get('app.theme').'-app.foot.compra_ahora') }}</span></a></li>
				<li><a class="color-letter flex-display link-header justify-center align-items-center" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell')  }}"><span>{{   mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.how_to_sell')  ) }}</span></a></li>
{{--
				<li><a class="color-letter flex-display link-header justify-center align-items-center" href="/{{\Config::get("app.locale")}}/info-subasta/7503-venta-privadas"><span>{{   mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.ventas_privadas')  ) }}</span></a></li>
--}}
				<li><a class="color-letter flex-display link-header justify-center align-items-center" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.valorar_producto')  }}"><span>{{   mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.tasaciones')  ) }}</span></a></li>

				<li>
					<a class="color-letter d-flex link-header align-items-center hidden-xs">
					  <span>{{trans(\Config::get('app.theme').'-app.foot.descubre') }}</span>
					</a>
					  <div class="submenuDuran ">
							<div >
								<div class="categoryOption"> <a  href="https://blog.duran-subastas.com/" target="_blank">{{ trans(\Config::get('app.theme').'-app.foot.magazine')}}</a> </div>


								<div class="categoryOption"> <a  href="<?= route("artists") ?>">{{ trans(\Config::get('app.theme').'-app.artist.artists')}}</a> </div>

							</div>
					  </div>
				</li>
				{{--
				<li><a class="color-letter flex-display link-header justify-center align-items-center" href="https://www.durangallery.com"><span>{{   mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.galeria')  ) }}</span></a></li>
				--}}

				<li>
					<a class="color-letter d-flex link-header align-items-center ">
					  <span>{{trans(\Config::get('app.theme').'-app.foot.duran') }}</span>
					</a>
					  <div class="submenuDuran ">
							<div >
								<div class="categoryOption"> <a  href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a> </div>
								<div class="categoryOption"> <a  href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  }}">{{ trans(\Config::get('app.theme').'-app.foot.nosotros') }}</a> </div>

							</div>
					  </div>
				</li>


            </ul>
        </div>
        <div class="search-header-container  d-flex justify-content-center align-items-center" role="button">
                <div class="search-header d-flex justify-content-center align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.17 29.861">
                    <defs>
                      <style>
                        .cls-1 {
                          fill: #46494f;
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
        <div class="cart-icon">
            <div class="cart-icon-link">
		      <a class="flex-display justify-center align-items-center " style="height: 100%;" href="{{route("showShoppingCart", ["lang" => \Config::get("app.locale")]) }}">

					@php
					# 07-01-2021 a veces da error la carga del archivo, esto sucede todos los dias asó k copio el código directamente en vez de abrirlo
					#					{!! file_get_contents(asset('themes/duran/assets/img/bag.svg')) !!}

					@endphp
					<svg height="512pt" viewBox="-35 0 512 512.00102" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m443.054688 495.171875-38.914063-370.574219c-.816406-7.757812-7.355469-13.648437-15.15625-13.648437h-73.140625v-16.675781c0-51.980469-42.292969-94.273438-94.273438-94.273438-51.984374 0-94.277343 42.292969-94.277343 94.273438v16.675781h-73.140625c-7.800782 0-14.339844 5.890625-15.15625 13.648437l-38.9140628 370.574219c-.4492192 4.292969.9453128 8.578125 3.8320308 11.789063 2.890626 3.207031 7.007813 5.039062 11.324219 5.039062h412.65625c4.320313 0 8.4375-1.832031 11.324219-5.039062 2.894531-3.210938 4.285156-7.496094 3.835938-11.789063zm-285.285157-400.898437c0-35.175782 28.621094-63.796876 63.800781-63.796876 35.175782 0 63.796876 28.621094 63.796876 63.796876v16.675781h-127.597657zm-125.609375 387.25 35.714844-340.097657h59.417969v33.582031c0 8.414063 6.824219 15.238282 15.238281 15.238282s15.238281-6.824219 15.238281-15.238282v-33.582031h127.597657v33.582031c0 8.414063 6.824218 15.238282 15.238281 15.238282 8.414062 0 15.238281-6.824219 15.238281-15.238282v-33.582031h59.417969l35.714843 340.097657zm0 0"/></svg>

                </a>
            </div>
        </div>
        <div class="user-account">
                @if(!Session::has('user'))
                    <div class="user-account-login">
                        <a class="flex-display justify-center align-items-center btn_login_desktop btn_login" title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>" href="javascript:;">
                               <svg viewBox="-42 0 512 512.001" xmlns="http://www.w3.org/2000/svg"><path d="m210.351562 246.632812c33.882813 0 63.21875-12.152343 87.195313-36.128906 23.96875-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.128906 87.195312 23.980469 23.96875 53.316406 36.125 87.191406 36.125zm-65.972656-189.292968c18.394532-18.394532 39.972656-27.335938 65.972656-27.335938 25.996094 0 47.578126 8.941406 65.976563 27.335938 18.394531 18.398437 27.339844 39.980468 27.339844 65.972656 0 26-8.945313 47.578125-27.339844 65.976562-18.398437 18.398438-39.980469 27.339844-65.976563 27.339844-25.992187 0-47.570312-8.945312-65.972656-27.339844-18.398437-18.394531-27.34375-39.976562-27.34375-65.976562 0-25.992188 8.945313-47.574219 27.34375-65.972656zm0 0"/><path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.3125-10.339844-7.808594-20.550781-13.375-30.335938-5.769532-10.15625-12.550782-19-20.160157-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.042969 5.339844-10.96875 0-22.085937-1.796876-33.042968-5.339844-11.210938-3.621094-20.300782-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.753906-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.609375 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.0625 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.777344-1.023438 19.953125-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.4375 23.730469 65.066406 23.730469h246.53125c26.621094 0 48.511719-7.984375 65.0625-23.730469 16.757813-15.945312 25.253906-37.589843 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm-44.90625 72.828125c-10.933594 10.40625-25.449218 15.464844-44.378906 15.464844h-246.527344c-18.933594 0-33.449218-5.058594-44.378906-15.460938-10.722656-10.207031-15.933594-24.140625-15.933594-42.585937 0-9.59375.316406-19.066407.949219-28.160157.617187-8.921874 1.878906-18.722656 3.75-29.136718 1.847656-10.285156 4.199219-19.9375 6.996094-28.675782 2.683593-8.378906 6.34375-16.675781 10.882812-24.667968 4.332031-7.617188 9.316407-14.152344 14.816407-19.417969 5.144531-4.925781 11.628906-8.957031 19.269531-11.980469 7.066406-2.796875 15.007812-4.328125 23.628906-4.558594 1.050781.558594 2.921875 1.625 5.953125 3.601563 6.167969 4.019531 13.277344 8.605469 21.136719 13.625 8.859375 5.648437 20.273437 10.75 33.910156 15.152344 13.941406 4.507812 28.160156 6.796875 42.273437 6.796875 14.113282 0 28.335938-2.289063 42.269532-6.792969 13.648437-4.410156 25.058594-9.507813 33.929687-15.164063 8.042969-5.140624 14.953125-9.59375 21.121094-13.617187 3.03125-1.972656 4.902344-3.042969 5.953125-3.601563 8.625.230469 16.566406 1.761719 23.636719 4.558594 7.636719 3.023438 14.121093 7.058594 19.265625 11.980469 5.5 5.261719 10.484375 11.796875 14.816406 19.421875 4.542969 7.988281 8.207031 16.289062 10.886719 24.660156 2.800781 8.75 5.15625 18.398438 7 28.675782 1.867187 10.433593 3.132812 20.238281 3.75 29.144531v.007812c.636719 9.058594.957031 18.527344.960937 28.148438-.003906 18.449219-5.214844 32.378906-15.9375 42.582031zm0 0"/></svg>
                        </a>
                    </div>
                    @else
                    <div class="my-account color-letter-header">
                        <div class="row">
                            <div class="col-xs-3 text-center">
                                <img width="25px;" class="icono-customer" src="/themes/{{\Config::get('app.theme')}}/assets/img/user.png"  alt="{{(\Config::get( 'app.name' ))}}">
                            </div>
                            <div class="col-xs-9 text-left nombre-us">
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
       <div class="menu-responsive hidden-lg">
            <div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter ">
                <img class="icono-movil-menu" role="button" src="/themes/{{\Config::get('app.theme')}}/assets/img/menu-movil.png" alt="Movil">
            </div>
        </div>
    </nav>
</header>

<div class="menu-principal-search d-flex align-items-center justify-content-center">
        <form id="formsearchResponsive" role="search" action="{{ route('allCategories') }}" class="search-component-form flex-inline position-relative">
            <div class="form-group">
                <input class="form-control input-custom br-100" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="description" />
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

				<?php /* Input que determina si se realiza también login en página de prestashop */ ?>
                @if(!empty(\Config::get('app.ps_activate')))
				<input type="hidden" id="presta" value="1">
                @endif

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
			{{-- @if(!empty(\Config::get('app.ps_activate')))
			<iframe id="iframePresta" width="1px" height="1px" frameborder="0px"></iframe>
            <form id="formPresta" method="post" action="{{ \Config::get('app.ps_shop_path') . 'api-ajax/external-login' }}">
                <input type="hidden" name="valoresPresta" id="valoresPresta" value="">
                <input type="hidden" name="submitLogin" id="submitLogin" value="1">
            </form>
            @endif --}}
        </div>
    </div>
    </div>
</div>




