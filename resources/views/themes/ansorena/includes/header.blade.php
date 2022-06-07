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
<?php #el proximo div es un espacio en blanco para que funcione el scroll del menu y no se suba todo para arriba ?>
<div class="header-height "></div>
<header class="fixed  header-height ">

	<div class="lang-selection">
		<div class="container">
			<div class="row">
				<div class="col-xs-3">

					@foreach(Config::get('app.locales') as $key => $value)
					<ul class="ul-format list-lang d-inline-flex">
						<?php
								if(\App::getLocale() != $key){
									#Obtener la ruta en el idioma contrario segun las tablas seo y/o traducciones links
									$ruta ="/$key". TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), \App::getLocale(), $key);
								}else{
									$ruta ="";
								}
                            ?>
						<li>
							<a translate="no" title="<?= trans(\Config::get('app.theme').'-app.head.language_'.$key) ?>"
								class="link-lang  color-letter {{ empty($ruta)? 'active': '' }} "
								{{ empty($ruta)? "": "href=$ruta" }}>

								<span translate="no"><img
										src="/themes/{{ \Config::get('app.theme') ."/assets/img/head/$key" }}{{ empty($ruta)? '': '_OFF' }}.png"></span>
							</a>
						</li>
					</ul>
					@endforeach

				</div>


				<div class="col-xs-4 col-sm-6 col-md-7 pl-0 pr-0">

					@if(\Config::get("app.emp") == '001' || \Config::get("app.emp") == '002')
						<div class="user-account">
							<div class="user-account-login">


									  <a class="cart-icon justify-center align-items-center " style="height: 100%;" href="{{route("showArticleCart", ["lang" => \Config::get("app.locale")]) }}">

											@php
											# 07-01-2021 a veces da error la carga del archivo, esto sucede todos los dias asó k copio el código directamente en vez de abrirlo
											#					{!! file_get_contents(asset('themes/duran/assets/img/bag.svg')) !!}

											@endphp
											<svg height="512pt" viewBox="-35 0 512 512.00102" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m443.054688 495.171875-38.914063-370.574219c-.816406-7.757812-7.355469-13.648437-15.15625-13.648437h-73.140625v-16.675781c0-51.980469-42.292969-94.273438-94.273438-94.273438-51.984374 0-94.277343 42.292969-94.277343 94.273438v16.675781h-73.140625c-7.800782 0-14.339844 5.890625-15.15625 13.648437l-38.9140628 370.574219c-.4492192 4.292969.9453128 8.578125 3.8320308 11.789063 2.890626 3.207031 7.007813 5.039062 11.324219 5.039062h412.65625c4.320313 0 8.4375-1.832031 11.324219-5.039062 2.894531-3.210938 4.285156-7.496094 3.835938-11.789063zm-285.285157-400.898437c0-35.175782 28.621094-63.796876 63.800781-63.796876 35.175782 0 63.796876 28.621094 63.796876 63.796876v16.675781h-127.597657zm-125.609375 387.25 35.714844-340.097657h59.417969v33.582031c0 8.414063 6.824219 15.238282 15.238281 15.238282s15.238281-6.824219 15.238281-15.238282v-33.582031h127.597657v33.582031c0 8.414063 6.824218 15.238282 15.238281 15.238282 8.414062 0 15.238281-6.824219 15.238281-15.238282v-33.582031h59.417969l35.714843 340.097657zm0 0"/></svg>

										</a>




								@if(!Session::has('user'))
								<a class=" btn_login "
									title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>"
									href="javascript:;">
									<img class="ico-login"
										src="/themes/{{\Config::get('app.theme')}}/assets/img/head/login.png">
									<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
								</a>
								@else
								<a class=" " href="{{ \Routing::slug('user/panel/orders') }}">
									<img class="ico-login"
										src="/themes/{{\Config::get('app.theme')}}/assets/img/head/login.png">
									<span class="hidden-xs ">
										{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }} <span>
								</a>

								@if(Session::get('user.admin'))
								<a href="/admin" target="_blank">
									{{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a>
								@endif
								<a href="{{ \Routing::slug('logout') }}" alt="logout"><img class="ico-logout"
										src="/themes/{{\Config::get('app.theme')}}/assets/img/head/close_session.png"> </a>
								@endif
							</div>

						</div>
					@endif


				</div>
				<div class="col-xs-5 col-sm-3  col-md-2 pl-0 rss-head">
					<ul class="ul-format">
						<li><a href="https://instagram.com/ansorena1845" title="Instagram" target="_blank"> <img
									src="/themes/{{\Config::get('app.theme')}}/assets/img/head/instagram.png"></a></li>
						<li><a href="https://www.linkedin.com/company/ansorena/" title="Linkedin" target="_blank"> <img
									src="/themes/{{\Config::get('app.theme')}}/assets/img/head/linkedin.png"></a></li>
						<li><a href="http://www.youtube.com/ansorenasubastas" title="Youtube" target="_blank"> <img
									src="/themes/{{\Config::get('app.theme')}}/assets/img/head/youtube.png"></a></li>
						<li><a href="http://twitter.com/ansorenaarte" title="Twitter" target="_blank"> <img
									src="/themes/{{\Config::get('app.theme')}}/assets/img/head/twitter.png"></a></li>
						<li><a href="http://www.facebook.com/ansorenadesde1845" title="Facebook" target="_blank"> <img
									src="/themes/{{\Config::get('app.theme')}}/assets/img/head/facebook.png"></a></li>
					</ul>

				</div>
			</div>
		</div>
	</div>

	<div class="logo-header">
		<a title="{{(\Config::get( 'app.name' ))}}" href="https://www.ansorena.com">
			<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png"
				alt="{{(\Config::get( 'app.name' ))}}">
		</a>
		<div class="menu-responsive hidden-lg">
			<div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter ">
				<img class="img-responsive" style="max-width: 40px" src="/themes/{{\Config::get('app.theme')}}/assets/img/menu_icon.png" alt="">
			</div>
		</div>
	</div>

	<?php /* OCULTO EL BUSCADOR
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
		<div class="menu-principal-search d-flex align-items-center justify-content-center">
			<form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form flex-inline position-relative">
				<div class="form-group">
					<input class="form-control input-custom br-100" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" />
				</div>
				<button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans(\Config::get('app.theme').'-app.head.search_button') }}</button>
			</form>
		</div>
	@endif
	*/
	?>
	<nav class="menu-header">

		<div class="menu-principal">
			<span role="button" class="close-menu-reponsive hidden-lg">
				<img src="/themes/{{ \Config::get('app.theme') }}/assets/img/shape.png" alt="Cerrar">
			</span>

			<ul class="menu-principal-content d-flex justify-content-center align-items-start">

				<?php /* QUITAMOS LA HOME
                    <li class="flex-display">
                        <a class="color-letter flex-display link-header justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">
                            <span>{{ trans(\Config::get('app.theme').'-app.home.home')}}</span>
                        </a>
					</li>
					*/
					?>

				@if(\Config::get("app.emp") == '001' || \Config::get("app.emp") == '002')

				<li>
					<a class="color-letter d-flex link-header justify-content-center align-items-center"
						 href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.joyas_category') }}" >
						<span>{{ trans(\Config::get('app.theme').'-app.foot.joyeria')}}</span>
					</a>
				</li>
				<li><a class="color-letter d-flex link-header justify-content-center align-items-center" href="https://galeria.ansorena.com/es" target="_blank">{{ trans(\Config::get('app.theme').'-app.galery.galery')}}</a></li>
				<li>
					<a class="color-letter d-flex link-header justify-content-center align-items-center"
						title="{{ trans(\Config::get('app.theme').'-app.foot.condecoraciones')}}"
						href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.condecoraciones')}}"><span>{{ trans(\Config::get('app.theme').'-app.foot.condecoraciones')}}</span></a>
				</li>
				<li><a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ route("subasta.actual") }}">{{ trans(\Config::get('app.theme').'-app.subastas.auctions')}}</a></li>

				<li>
					<a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('valoracion-articulos', null) }}"><span> {{ trans($theme.'-app.home.free-valuations') }}</span></a>
				</li>

				<li>
					<a class="color-letter d-flex link-header justify-content-center align-items-center"
						title="{{ trans(\Config::get('app.theme').'-app.foot.ansorena')}}"
						href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.historia')}}"><span>{{ trans(\Config::get('app.theme').'-app.foot.ansorena')}}</span></a>
				</li>


				<li>
					<a class="color-letter d-flex link-header justify-content-center align-items-center"
						title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}"
						href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
				</li>
				<?php /*
					@if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
						@php
							if(count($global['subastas']['S']['W']) == 1){
								#cojer primera subasta y primera sesión
									$subasta = $global['subastas']['S']['W']->first()->first();

									$url_web =  \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions,	$subasta->reference);
							}else{
									$url_web =  \Routing::translateSeo('presenciales');
							}

						@endphp
						<li>
							<a class="color-letter d-flex link-header justify-content-center align-items-center"
								href="{{ $url_web }}">
								<span>{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</span>
							</a>
						</li>
					@endif

					@if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
						@php
							if(count($global['subastas']['S']['O']) == 1){
								#cojer primera subasta y primera sesión
								$subasta = $global['subastas']['S']['O']->first()->first();

								$url_web =  \Tools::url_auction($subasta->cod_sub, \Str::slug($subasta->name) ,$subasta->id_auc_sessions,	$subasta->reference);
							}else{
								$url_web =  \Routing::translateSeo('subastas-online');
							}

						@endphp
						<li>
							<a class="color-letter flex-display link-header justify-center align-items-center"
								href="{{ $url_web }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</span></a>
						</li>
					@endif
					@if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
						<li><a class="color-letter flex-display link-header justify-center align-items-center"
								href="{{ \Routing::translateSeo('venta-directa') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</span></a>
						</li>
					@endif
					@if($global['subastas']->has('H') )
						<li>
							<a class="color-letter flex-display link-header justify-center align-items-center"
								href="{{ \Routing::translateSeo('subastas-historicas') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</span>
							</a>
						</li>
					@endif
					*/
					?>
				@endif
				<?php /*
                *


	      <li>
                    <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('calendar') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.calendar')}}</span></a>
                </li>
                 <li>
                    <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('valoracion-articulos') }}"><span> {{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</span></a>
				</li>
				  * */

                  ?>
				@if(\Config::get("app.emp") == '003' || \Config::get("app.emp") == '004')
						@php
						$subObj = new App\Models\V5\FgSub();

						#Cojemos la exposicion/subasta tipo E  activa que empiece antes, si no quieren que aparezca esa que la pongan en histórico
						#Las subastas con opcion carrito son las que ellos llaman online
						$actual = $subObj->select("DES_SUB, COD_SUB")->where("SUBC_SUB","S")->where("TIPO_SUB","E")->where("OPCIONCAR_SUB","N")->orderby("DFEC_SUB")->first();

					@endphp




						@if(!empty($actual))
							<li ><a href="{{\Tools::url_exposicion($actual->des_sub, $actual->cod_sub)}}" class="color-letter d-flex link-header justify-content-center align-items-center">{{ trans(\Config::get('app.theme').'-app.galery.current_exhibition') }}</a></li>
						@endif
						<li ><a href="{{Route("exposiciones")}}?online=N" class="color-letter d-flex link-header justify-content-center align-items-center">{{ trans(\Config::get('app.theme').'-app.galery.exhibitions') }}</a></li>

						<li ><a href="{{Route("exposiciones")}}?online=S" class="color-letter d-flex link-header justify-content-center align-items-center">{{ trans(\Config::get('app.theme').'-app.galery.online_exhibitions') }}</a></li>

						{{-- 	 lo comento por que de momento pongo todos en
						<li ><a href="{{Route("exposiciones-anteriores")}}" class="color-letter d-flex link-header justify-content-center align-items-center"> {{ trans(\Config::get('app.theme').'-app.galery.previous_exhibition') }}</a></li>
						--}}


						<li ><a href="{{Route("artistasGaleria")}}" class="color-letter d-flex link-header justify-content-center align-items-center">{{ trans(\Config::get('app.theme').'-app.galery.artists') }}</a></li>
						<li ><a href="{{Route("fondoGaleria")}}" class="color-letter d-flex link-header justify-content-center align-items-center">{{ trans(\Config::get('app.theme').'-app.galery.gallery_collection') }}</a></li>

						<li ><a href="<?php echo Routing::translateSeo('blog').trans(\Config::get('app.theme').'-app.links.news')?>" class="color-letter d-flex link-header justify-content-center align-items-center">{{ trans(\Config::get('app.theme').'-app.home.news')}}</a></li>

						<li ><a href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact_galery')?>" class="color-letter d-flex link-header justify-content-center align-items-center">{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</a></li>
					</ul>

				@endif

			</ul>
		</div>
		<?php /*   OCULTO EL BUSCADOR
        <div class="search-header-container  d-flex justify-content-center align-items-center hidden-xs" role="button">
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
                                  fill: #46494f;
                                }
                              </style>
                            </defs>
                            <g id="cancel" transform="translate(0 -0.435)">
                              <path id="Path_27" data-name="Path 27" class="close-svg" d="M18.993,17.284,33.238,3.039a1.481,1.481,0,0,0,0-2.144,1.481,1.481,0,0,0-2.144,0L16.849,15.139,2.6.894a1.481,1.481,0,0,0-2.144,0,1.481,1.481,0,0,0,0,2.144L14.7,17.284.459,31.528a1.481,1.481,0,0,0,0,2.144,1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306L16.848,19.428,31.093,33.673a1.842,1.842,0,0,0,1.225.306c.306,0,.919,0,.919-.306a1.481,1.481,0,0,0,0-2.144Z" transform="translate(0 0)"/>
                            </g>
                          </svg>
                        </div>

		</div>
		*/
		?>

	</nav>
</header>

<div class="login_desktop" style="display: none">
	<div class="login_desktop_content">
		<div class="only-login white-background">
			<div class="login-content-form">
				<img class="closedd" role="button" src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png"
					alt="Close">
				<div class="login_desktop_title">
					<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>
				</div>
				<form data-toggle="validator" id="accerder-user-form"
					class="flex-display justify-center align-items-center flex-column">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<div class="input-login-group">
							<i class="fa fa-user"></i>
							<input class="form-control"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.user') }}"
								type="email" name="email" type="text">
						</div>
					</div>
					<div class="form-group ">
						<div class="input-login-group">
							<i class="fa fa-key"></i>
							<input class="form-control"
								placeholder="{{ trans(\Config::get('app.theme').'-app.login_register.contraseña') }}"
								type="password" name="password" maxlength="20">
							<img class="view_password eye-password"
								src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
						</div>
					</div>
					<span class="message-error-log text-danger seo_h5"></span></p>
					<div class="pass-login-content">
						<div class="text-center">
							<button id="accerder-user" class="button-principal" type="button">
								<div>{{ trans(\Config::get('app.theme').'-app.login_register.acceder') }}</div>
							</button>
						</div>
						<a onclick="cerrarLogin();" class="c_bordered pass_recovery_login"
							data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery"
							data-title="{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}"
							href="javascript:;" data-toggle="modal"
							data-target="#modalAjax">{{ trans(\Config::get('app.theme').'-app.login_register.forgotten_pass_question')}}</a>

					</div>
				</form>
				<div class="login-separator"></div>
				<p class="text-center">{{ trans(\Config::get('app.theme').'-app.login_register.not_account') }}</p>
				<div class="create-account-link">
					@if(empty($registration_disabled))
					<a class="" title="{{ trans(\Config::get('app.theme').'-app.login_register.register') }}"
						href="{{ \Routing::slug('register') }}">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</a>
					@else
					<p class="text-center" style="color: darkred;">
						{{ trans(\Config::get('app.theme').'-app.login_register.registration_disabled') }}</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>



<script>

</script>
