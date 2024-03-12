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

	<div class="logo-header">
		<a title="{{(\Config::get( 'app.name' ))}}" href="/{{$lang}}">
			<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo.png?a=1"  alt="{{(\Config::get( 'app.name' ))}}">
		</a>
		<div class="menu-responsive hidden-lg">
			<div role="button" class="menu-text d-flex justify-content-center align-items-center color-letter ">
				<img class="img-responsive" style="max-width: 40px" src="/themes/{{\Config::get('app.theme')}}/assets/img/menu_icon.png" alt="">
			</div>
		</div>
	</div>


	<nav class="menu-header">

		<div class="menu-principal">
			<span role="button" class="close-menu-reponsive hidden-lg">
				<img src="/themes/{{ \Config::get('app.theme') }}/assets/img/shape.png" alt="Cerrar">
			</span>

			<ul class="menu-principal-content d-flex justify-content-center align-items-start">

				<li>
					<a class="color-letter flex-display link-header justify-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.home.home')}}" href="/{{$lang}}">
						<span>{{ trans(\Config::get('app.theme').'-app.home.home')}}</span>
					</a>
				</li>

               @if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
                  <li>
                      <a class="color-letter d-flex link-header justify-content-center align-items-center" href="{{ \Routing::translateSeo('presenciales') }}">
                        <span>{{ trans(\Config::get('app.theme').'-app.foot.auctions')}}</span>
                    </a>
                    </li>
                @endif

                @if($global['subastas']->has('H') )
                    <li>
                        <a class="color-letter flex-display link-header justify-center align-items-center" href="{{ \Routing::translateSeo('subastas-historicas') }}"><span>{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</span>
                        </a>
                    </li>
                @endif

                 <li>
                    <a class="color-letter flex-display link-header justify-center align-items-center" title="" href="{{ \Routing::translateSeo('valoracion-articulos') }}"><span> {{ trans(\Config::get('app.theme').'-app.home.free-valuations') }}</span></a>
                </li>
                <li>
                    <a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact')}}</span></a>
                </li>

				@if(!Session::has('user'))
				<li>
                    <a class="color-letter d-flex link-header justify-content-center align-items-center btn_login_desktop btn_login" title="<?= trans(\Config::get('app.theme').'-app.login_register.login') ?>" href="javascript:;">
						<span>{{trans(\Config::get('app.theme').'-app.login_register.login') }}</span>
					</a>
                </li>
				@else
				<li>
					<a class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}" href="{{ \Routing::slug('user/panel/orders') }}">
						<span>{{ trans(\Config::get('app.theme').'-app.login_register.my_panel') }}</span>
					</a>
				</li>

					@if(Session::get('user.admin'))
					<li>
						<a href="/admin" target = "_blank" class="color-letter d-flex link-header justify-content-center align-items-center" title="{{ trans(\Config::get('app.theme').'-app.login_register.admin') }}">
							<span>{{ trans(\Config::get('app.theme').'-app.login_register.admin') }}</span>
						</a>
					</li>
					@endif


				@endif
			</ul>

		</div>


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
