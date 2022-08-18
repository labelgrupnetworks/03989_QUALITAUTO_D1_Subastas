<?php
use App\libs\TradLib as TradLib;

$lang = config('app.locale');
$registration_disabled = Config::get('app.registration_disabled');

$fullname = Session::get('user.name');
$name = $fullname;
if(strpos($fullname, ',')){
	$str = explode(",", $fullname);
	$name = $str[1];
}

$categories = (new App\Models\V5\FgOrtsec0())->getAllFgOrtsec0()->get()->toarray();
$searchAction = config('app.gridLots', false) == "new" ? route('allCategories') : \Routing::slug('busqueda');
?>

<header>

	<div class="first-header-wrapper">
		<div class="container py-1 bk-gray">
			<div class="d-flex justify-content-end align-items-center gap-3">

				@include('components.search', ['classes' => 'me-auto d-none d-xxl-block'])

				@if(!Session::has('user'))
					<button class="btn btn-sm btn-primary-custom btn_login">{{ trans($theme.'-app.login_register.login') }}</button>
				@else
					<a class="btn btn-sm btn-primary-custom" href="{{ \Routing::slug('user/panel/orders') }}">{{ trans($theme.'-app.login_register.my_panel') }}</a>

					@if(Session::get('user.admin'))
					<a class="btn btn-sm btn-primary-custom" href="/admin"  target = "_blank"> {{ trans($theme.'-app.login_register.admin') }}</a>
					@endif

					<a class="btn btn-sm btn-primary-custom" href="{{ \Routing::slug('logout') }}" >{{ trans($theme.'-app.login_register.logout') }}</a>
				@endif

				@if(count(Config::get('app.locales')) > 1)
				{{-- Con dropdown --}}
				<div class="btn-group">
					<button type="button" class="btn btn-sm btn-outline-primary-custom dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
					  {{ config('app.locales')[config('app.locale')] }}
					</button>
					<ul class="dropdown-menu dropdown-menu-end">
						@foreach(Config::get('app.locales') as $key => $value)
						@php
							$route = '';
							if($key != $lang){
								$route = "/$key". TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), \App::getLocale(), $key);
							}
						@endphp
						<li>
							<a @class(['dropdown-item', 'disabled' => $key == $lang]) translate="no" aria-current="true" title="{{ trans("$theme-app.head.language_$key") }}" href="{{$route}}">
								{{ trans("$theme-app.home.$key") }}
							</a>
						</li>
					@endforeach
					</ul>
				</div>
				{{-- Con enlaces --}}
				{{-- @foreach(Config::get('app.locales') as $key => $value)
					@php
						$route = '';
						if($key != $lang){
							$route = "/$key". TradLib::getRouteTranslate((substr($_SERVER["REQUEST_URI"], 4)), \App::getLocale(), $key);
						}
					@endphp
					<a @class(['selected' => $key == $lang]) translate="no" title="{{ trans("$theme-app.head.language_$key") }}" href="{{$route}}">
						<span translate="no">{{ trans("$theme-app.home.$key") }}</span>
					</a>
				@endforeach --}}
				@endif
			</div>
		</div>
	</div>

	<nav class="navbar navbar-expand-xxl">
		<div class="container">
			<a class="navbar-brand" href="/{{$lang}}" title="{{(\Config::get( 'app.name' ))}}">
				<img width="200" class="img-responsive" src="/themes/{{$theme}}/assets/img/logo.png"  alt="{{(\Config::get( 'app.name' ))}}">
			</a>
			<button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
				data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="navbar-collapse collapse" id="navbarHeader" style="">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold">

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="categoriesHeader" data-bs-toggle="dropdown" aria-expanded="false">{{ trans("$theme-app.lot.categories") }}</a>
						<ul class="dropdown-menu" aria-labelledby="categoriesHeader">
							@foreach ($categories as $category)
								<li>
									<a class="dropdown-item" href="{{ route("category",array( "category" => $category["key_ortsec0"])) }}">{{$category["des_ortsec0"]}}</a>
								</li>
							@endforeach
						</ul>
					</li>

					@if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
					<li class="nav-item">
						<a class="nav-link"
							href="{{ \Routing::translateSeo('presenciales') }}">
							<span>{{ trans($theme.'-app.foot.auctions')}}</span>
						</a>
					</li>
					@endif
					@if($global['subastas']->has('S') && $global['subastas']['S']->has('O'))
					<li class="nav-item">
						<a class="nav-link"
							href="{{ \Routing::translateSeo('subastas-online') }}">
							<span>{{ trans($theme.'-app.foot.online_auction') }}</span>
						</a>
					</li>
					@endif
					@if($global['subastas']->has('S') && $global['subastas']['S']->has('V'))
					<li class="nav-item">
						<a class="nav-link" href="{{ \Routing::translateSeo('venta-directa') }}">
							<span>{{ trans($theme.'-app.foot.direct_sale')}}</span>
						</a>
					</li>
					@endif
					@if($global['subastas']->has('H'))
					<li class="nav-item">
						<a class="nav-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">
							<span>{{ trans($theme.'-app.foot.historico')}}</span>
						</a>
					</li>
					@endif

					<li class="nav-item">
						<a class="nav-link" title="" href="{{ \Routing::translateSeo('calendar') }}"><span>{{ trans($theme.'-app.foot.calendar')}}</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" title="" href="{{ \Routing::translateSeo('valoracion-articulos', null) }}"><span> {{ trans($theme.'-app.home.free-valuations') }}</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" title="{{ trans($theme.'-app.foot.contact')}}" href="<?= \Routing::translateSeo(trans($theme.'-app.links.contact')) ?>"><span>{{ trans($theme.'-app.foot.contact')}}</span></a>
					</li>
				</ul>

				@include('components.search', ['classes' => 'me-auto d-xxl-none'])
			</div>
		</div>
	</nav>

</header>


<div class="login_desktop" style="display: none" >
    <div class="login_desktop_content">
        <div class="only-login white-background">
            <div class="login-content-form">
            <img class="closedd" role="button" src="/themes/{{$theme}}/assets/img/shape.png" alt="Close">
            <div class="login_desktop_title">
                <?= trans($theme.'-app.login_register.login') ?>
            </div>
            <form data-toggle="validator" id="accerder-user-form" class="flex-display justify-center align-items-center flex-column">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="input-login-group">
                        <i class="fa fa-user"></i>
                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.user') }}" type="email" name="email" type="text">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="input-login-group">
                        <i class="fa fa-key"></i>
                        <input class="form-control" placeholder="{{ trans($theme.'-app.login_register.contraseña') }}" type="password" name="password" maxlength="20" autocomplete="off">
                        <img class="view_password eye-password" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                    </div>
                </div>
                <span class="message-error-log text-danger seo_h5"></span></p>
                <div class="pass-login-content">
                    <div class="text-center">
                    <button id="accerder-user" class="button-principal" type="button">
                        <div>{{ trans($theme.'-app.login_register.acceder') }}</div>
                    </button>
                    </div>
                    <a onclick="cerrarLogin();" class="c_bordered pass_recovery_login" data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery" data-title="{{ trans($theme.'-app.login_register.forgotten_pass_question')}}" href="javascript:;" data-toggle="modal" data-target="#modalAjax" >{{ trans($theme.'-app.login_register.forgotten_pass_question')}}</a>

                </div>
            </form>
            <div class="login-separator"></div>
            <p class="text-center">{{ trans($theme.'-app.login_register.not_account') }}</p>
            <div class="create-account-link">
                @if(empty($registration_disabled))
                <a class="" title="{{ trans($theme.'-app.login_register.register') }}" href="{{ \Routing::slug('register') }}">{{ trans($theme.'-app.login_register.register') }}</a>
                @else
                <p class="text-center" style="color: darkred;">{{ trans($theme.'-app.login_register.registration_disabled') }}</p>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>
