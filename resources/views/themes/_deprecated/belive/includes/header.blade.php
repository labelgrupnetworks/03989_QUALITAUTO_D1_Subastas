<?php
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


<header>
	<nav class="topmenu">
		<div class="container">

			<div class="logo">
				<a onclick="javascript:$('.menu').toggle('fade')"><i class="fa fa-bars fa-3x"></i></a>
				&nbsp;&nbsp;            
				<a title="{{(\Config::get( 'app.name' ))}}" href="https://www.belivehotels.com/{{$lang}}">
					<img src="/themes/{{\Config::get('app.theme')}}/assets/img/bl_logo_w_hor.svg"  alt="{{(\Config::get( 'app.name' ))}}">
				</a>
				<div class="menu">
					<ul class="menu-principal-content d-flex justify-content-center align-items-center">
						<span role="button" class="close-menu-reponsive hidden-lg"><i class="fa fa-times"></i></span>
						<?php //   <li><a title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/">{{ trans(\Config::get('app.theme').'-app.home.home')}}</a></li> ?>
							<li>
								<a class="color-letter flex-display justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">
									<span>{{ trans(\Config::get('app.theme').'-app.home.home')}}</span>
								</a>
							</li>
						
						<?php
						  $subastaObj        = new \App\Models\Subasta();
						   $has_subasta = $subastaObj->auctionList ('S', 'O');
						  if(empty($has_subasta) && Session::get('user.admin')){
							   $has_subasta= array_merge($has_subasta,$subastaObj->auctionList ('A', 'O'));
						   }
						?>
						@if(!empty($has_subasta))
							<li>
								<a class="color-letter flex-display justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-online') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</span></a>
							</li>
						@endif
						<?php
							$has_subasta = $subastaObj->auctionList ('H');
						?>
						@if(!empty($has_subasta))
							<li>
								<a class="color-letter flex-display justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-historicas') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</span>
								</a>
							</li>
						@endif
						<li>
							<a class="color-letter flex-display justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
						</li>
					</ul>

				</div>
			</div>

			




			<div class="idioma" onclick="javascript:$('#idiomas').toggle('fade');">
				{{ trans(\Config::get('app.theme').'-app.head.idioma')}}
				<span>{{ strtoupper(\App::getLocale()) }} &nbsp;<i class="fa fa-chevron-down"></i></span>
				<div id="idiomas">
				<?php foreach(Config::get('app.locales') as $key => $value) { ?>
								
										
						@if(\App::getLocale() != $key)
							
								<a translate="no" title="<?= trans(\Config::get('app.theme').'-app.head.language_es') ?>" class="link-lang color-letter" href="/<?=$key;?>">
									<span translate="no">{{ mb_strtoupper(trans(\Config::get('app.theme').'-app.home.' . $key))}}</span>
								</a>
							
						@else
						
							<a translate="no" title="<?= trans(\Config::get('app.theme').'-app.head.language_es') ?>" class="link-lang active color-letter">
								<span translate="no">{{ trans(\Config::get('app.theme').'-app.home.' . $key)}}</span>
							</a>
						
						@endif
					
					<?php } ?>
				</div>
			</div>
			<div class="atencion-cliente">
				{{ trans(\Config::get('app.theme').'-app.head.atencion_cliente')}}
				<span class="tel">911360606</span>
			</div>


			<div class="search">
				<span class="fa fa-3x fa-search" onclick="$('.search_box').toggle('fade');"></span>
				<div class="search_box">
					<form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}" class="search-component-form flex-inline position-relative">
						<div class="form-group">
							<input class="form-control input-custom br-100" style="color:#464646;" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" />
						</div>
						<button role="button" type="submit" class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans(\Config::get('app.theme').'-app.head.search_button') }}</button>
					</form>
					<a onclick="$('.search_box').toggle('fade');"><img class="closedd hidden-xs" role="button" src="/themes/{{\Config::get('app.theme')}}/assets/img/shape.png" alt="Close"></a>
				</div>
			</div>

			@if(Session::has('user'))
				<div class="user logged">

						<span class="nombre">{{\Session::get('user.name')}}</span>
						<span class="fa fa-3x fa-user" onclick="$('.my-account').toggle('fade');"></span>
						<div class="my-account">
							<a href="{{ \Routing::slug('user/panel/orders') }}" >
								{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}
							</a>
							@if(Session::get('user.admin'))
								<a href="/admin"  target = "_blank"> {{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</a>
							@endif
							<a href="{{ \Routing::slug('logout') }}" >{{ trans(\Config::get('app.theme').'-app.login_register.logout') }}</a>
						</div>

				</div>
			@else
				<div class="user">
					<span class="fa fa-3x fa-user signIn"></span>
				</div>
			@endif

		</div>
	</nav>
</header>


			
		


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