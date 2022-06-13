<?php

use App\libs\TradLib as TradLib;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FxSec;

$lang = Config::get('app.locale');

$registration_disabled = Config::get('app.registration_disabled');
$fullname = Session::get('user.name');
if (strpos($fullname, ',')) {
    $str = explode(',', $fullname);
    $name = $str[1];
} else {
    $name = $fullname;
}

$categorys = (new FgOrtsec0())->GetAllFgOrtsec0()->get();
$subCategorys = FxSec::joinFgOrtsecFxSec()
    ->select('cod_sec', 'des_sec', 'lin_ortsec1')
    ->whereIn('lin_ortsec1', $categorys->pluck('lin_ortsec0'))
    ->get();
?>
@if (count(Config::get('app.locales')) > 1)
    <div class="lang-selection">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 text-right d-flex justify-content-flex-end">

                    @foreach (Config::get('app.locales') as $key => $value)
                        <ul class="ul-format list-lang d-inline-flex">
                            <?php
                            if (\App::getLocale() != $key) {
                                #Obtener la ruta en el idioma contrario segun las tablas seo y/o traducciones links
                                $ruta = "/$key" . TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), \App::getLocale(), $key);
                            } else {
                                $ruta = '';
                            }
                            ?>
                            <li>
                                <a translate="no" title="<?= trans($theme . '-app.head.language_es') ?>"
                                    class="link-lang  color-letter {{ empty($ruta) ? 'active' : '' }} "
                                    {{ empty($ruta) ? '' : "href=$ruta" }}>

                                    <span translate="no">{{ trans($theme . '-app.home.' . $key) }}</span>
                                </a>
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<header>

    {{----------------------- mobile header -----------------------}}
    <nav class="navbar navbar-default hidden-sm hidden-md hidden-lg">
        <div class="container mobile-header">

            <div class="navbar-header">

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="logo-header">
                    <a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
                        <img class="logo-company" src="/themes/{{ $theme }}/assets/img/logo.png"
                            alt="{{ \Config::get('app.name') }}">
                    </a>
                </div>

                <div class="navbar-brand icons-band">
					@if (!Session::has('user'))
						{{-- <a href="{{ config('app.custom_login_url') }}&context_url={{Request::getSchemeAndHttpHost()}}" --}}
						<a href="{{ config('app.custom_login_url') }}&context_url={{$host}}"
							class="{{ !Session::has('user') ? 'btn_login' : '' }}" data-display="static">
							<i class="fa fa-user"></i>
						</a>
					@endif
					@if (Session::has('user'))
						<a href="{{ \Routing::slug('user/panel/allotments') }}" class="ml-1" data-display="static">
							<i class="fa fa-shopping-bag" aria-hidden="true"></i>
						</a>
					@endif
                </div>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                            aria-expanded="false">{{ trans("$theme-app.lot.categories") }} <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach ($categorys as $category)
                                <li>
                                    <a
                                        href="{{ route('category', ['category' => $category['key_ortsec0']]) }}">{{ $category['des_ortsec0'] }}</a>
                                </li>
                            @endforeach
                            <li><a href="{{ route('allCategories') }}">TODOS</a></li>
                            <li role="separator" class="divider"></li>

                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('artists') }}">{{ trans($theme . '-app.artist.artists') }}</a>
                    </li>
                    <li>
                        <a href="{{ \Routing::slug('dmg') }}">{{ trans($theme . '-app.subastas.dmg_auction') }}</a>
                    </li>
                    <li>
                        <a href="{{ trans("$theme-app.links.blog_duran") }}" target = "_blank">{{ trans($theme . '-app.home.magazine') }}</a>
                    </li>
                    <li>
                        <a href="{{ Routing::translateSeo(trans($theme . '-app.links.contact')) }}">{{ trans($theme . '-app.foot.contact') }}</a>
                    </li>
                    <li>
                        <a href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}">{{ trans($theme . '-app.foot.about_us') }}</a>
                    </li>

                    @if (!Session::has('user'))
                        <li>
                            <a href="javascript:;" class="hidden btn_login"title="{{ trans($theme . '-app.login_register.login') }}">
								{{ trans($theme . '-app.login_register.login') }}
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="color-letter d-flex link-header"
                                title="{{ trans($theme . '-app.login_register.my_panel') }}"
                                href="{{ \Routing::slug('user/panel/allotments') }}">{{ trans($theme . '-app.login_register.my_panel') }}
                            </a>
                        </li>
                        @if (Session::get('user.admin'))
                            <li><a href="/admin"
                                    target="_blank">{{ trans($theme . '-app.login_register.admin') }}</a>
                            </li>
                        @endif
                        <li><a
                                href="{{ Routing::slug('logout') }}">{{ trans($theme . '-app.login_register.logout') }}</a>
                        </li>
                    @endif
                </ul>

            </div>
        </div>
    </nav>



    {{----------------------- tablet header -----------------------}}
    <nav class="navbar navbar-default hidden-xs hidden-lg">
        <div class="container-fluid tablet-header">
			<div class="row d-flex align-items-center justify-content-center">

				<div class="col-xs-2">
					<div class="navbar-header">

						<div class="">
							<a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
								<img class="logo-company" src="/themes/{{ $theme }}/assets/img/logo.png"
									alt="{{ \Config::get('app.name') }}">
							</a>
						</div>

					</div>
				</div>

            	<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="col-xs-9">
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">

							<li class="dropdown">
								<a href="#" class="dropdown-toggle color-letter flex-display link-header" data-toggle="dropdown" role="button" aria-haspopup="true"
									aria-expanded="false">{{ trans("$theme-app.lot.categories") }} <span
										class="caret"></span></a>
								<ul class="dropdown-menu">
									@foreach ($categorys as $category)
										<li>
											<a class="color-letter flex-display link-header"
												href="{{ route('category', ['category' => $category['key_ortsec0']]) }}">{{ $category['des_ortsec0'] }}</a>
										</li>
									@endforeach
									<li><a class="color-letter flex-display link-header" href="{{ route('allCategories') }}">TODOS</a></li>
									<li role="separator" class="divider"></li>

								</ul>
							</li>
							<li>
								<a class="color-letter flex-display link-header" href="{{ route('artists') }}">{{ trans($theme . '-app.artist.artists') }}</a>
							</li>
							<li>
								<a class="color-letter flex-display link-header" href="{{ \Routing::slug('dmg') }}">{{ trans($theme . '-app.subastas.dmg_auction') }}</a>
							</li>
							<li>
								<a class="color-letter flex-display link-header" href="{{ trans("$theme-app.links.blog_duran") }}" target = "_blank">{{ trans($theme . '-app.home.magazine') }}</a>
							</li>
							<li>
								<a class="color-letter flex-display link-header" href="{{ Routing::translateSeo(trans($theme . '-app.links.contact')) }}">{{ trans($theme . '-app.foot.contact') }}</a>
							</li>
							<li>
								<a class="color-letter flex-display link-header" href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}">{{ trans($theme . '-app.foot.about_us') }}</a>
							</li>

							@if (!Session::has('user'))
								<li>
									<a href="javascript:;" class="hidden btn_login color-letter flex-display link-header" title="{{ trans($theme . '-app.login_register.login') }}">
										{{ trans($theme . '-app.login_register.login') }}
									</a>
								</li>
							@else
								<li>
									<a class="color-letter d-flex link-header"
										title="{{ trans($theme . '-app.login_register.my_panel') }}"
										href="{{ \Routing::slug('user/panel/allotments') }}">{{ trans($theme . '-app.login_register.my_panel') }}
									</a>
								</li>
								@if (Session::get('user.admin'))
									<li><a class="color-letter flex-display link-header" href="/admin"
											target="_blank">{{ trans($theme . '-app.login_register.admin') }}</a>
									</li>
								@endif
								<li><a class="color-letter flex-display link-header"
										href="{{ Routing::slug('logout') }}">{{ trans($theme . '-app.login_register.logout') }}</a>
								</li>
							@endif
						</ul>

					</div>
				</div>

				<div class="col-xs-1">
					<div class="navbar-brand icons-band">
						@if (!Session::has('user'))
							<a href="{{ config('app.custom_login_url') }}&context_url={{$host}}"
								class="{{ !Session::has('user') ? 'btn_login' : '' }}" data-display="static">
								<i class="fa fa-user"></i>
							</a>
						@endif
						@if (Session::has('user'))
							<a href="{{ \Routing::slug('user/panel/allotments') }}" class="ml-1" data-display="static">
								<i class="fa fa-shopping-bag" aria-hidden="true"></i>
							</a>
						@endif
					</div>
				</div>

			</div>
        </div>
    </nav>



    {{----------------------- desktop header -----------------------}}
    <div class="container-fluid hidden-xs hidden-sm hidden-md desktop-header">

        {{-- <div class="row mt-2">
		</div> --}}


        <nav class="menu-header mt-1 mb-1">

            <div class="row">

                <div class="col-xs-2">
                    <div class="logo-header">
                        <a title="{{ \Config::get('app.name') }}" href="/{{ $lang }}">
                            <img class="logo-company" src="/themes/{{ $theme }}/assets/img/logo.png"
                                alt="{{ \Config::get('app.name') }}">
                        </a>
                    </div>
                </div>

                {{-- <div class="col-xs-1"></div> --}}

                <div class="col-xs-8 mt-1">
                    <div class="menu-principal">

                        <ul class="menu-principal-content d-flex  align-items-center justify-content-center">

                            <li>
                                <a class="color-letter d-flex link-header justify-content-center align-items-center category-button"
                                    href="/{{ $lang }}/subastas"><span>{{ trans("$theme-app.lot.categories") }}</span></a>
                                <div class="submenuDefault">
                                    <div class="row">
                                        <div class="col-xs-12 p-2 category-col" role="tablist">
                                            @foreach ($categorys as $category)
                                                <div class="categoryOption">
                                                    {{-- <a href="#{{$category["key_ortsec0"]}}" aria-controls="{{$category["key_ortsec0"]}}" role="tab" data-toggle="tab">{{$category["des_ortsec0"]}}</a> --}}
                                                    <a href="{{ route('category', ['category' => $category['key_ortsec0']]) }}"
                                                        aria-controls="{{ $category['key_ortsec0'] }}"
                                                        {{-- role="tab" data-toggle="tab" --}}>{{ $category['des_ortsec0'] }}</a>
                                                </div>
                                            @endforeach
                                            <div class="categoryOption">
                                                <a href="{{ route('allCategories') }}"
                                                    aria-controls="todos">TODOS</a>
                                            </div>
                                        </div>

                                        {{-- <div class="col-xs-6 p-2">
											<div class=" tab-content tab-header">
											@foreach ($categorys as $category)
												<div role="tabpanel" class="tab-pane fade" id="{{$category["key_ortsec0"]}}">

													<p class="mb-2"><a class="category" href="{{ route("category", array("category" => $category["key_ortsec0"])) }}">{{$category["des_ortsec0"]}}</a></p>

													@foreach ($subCategorys->where('lin_ortsec1', $category['lin_ortsec0']) as $subCategory)
														<p><a class="subcategory" href="{{ route("allCategories", ['category' => $subCategory->lin_ortsec1, 'section' => $subCategory->cod_sec]) }}">{{ $subCategory->des_sec }}</a></p>
													@endforeach

												</div>
											@endforeach
												<div role="tabpanel" class="tab-pane fade" id="todos">
													<a class="category" href="{{ route("allCategories") }}">TODOS</a>
												</div>
											</div>
										</div> --}}

                                    </div>
                                </div>

                            </li>

                            <li>
                                <a class="color-letter flex-display link-header" title=""
                                    href="{{ route('artists') }}"><span>{{ trans($theme . '-app.artist.artists') }}</span></a>
                            </li>
                            <li>
                                <a class="color-letter flex-display link-header" title=""
                                    href="{{ \Routing::slug('dmg') }}"><span>{{ trans($theme . '-app.subastas.dmg_auction') }}</span></a>
                            </li>
                            <li>
                                <a class="color-letter flex-display link-header" target = "_blank"
                                    title="{{ trans($theme . '-app.home.magazine') }}"
                                    href="{{ trans("$theme-app.links.blog_duran") }}"><span>
                                        {{ trans($theme . '-app.home.magazine') }}</span></a>
                            </li>
                            <li>
                                <a class="color-letter d-flex link-header"
                                    title="{{ trans($theme . '-app.foot.contact') }}"
                                    href="<?= \Routing::translateSeo(trans($theme . '-app.links.contact')) ?>"><span>{{ trans($theme . '-app.foot.contact') }}</span></a>
                            </li>
                            <li>
                                <a class="color-letter d-flex link-header"
                                    title="{{ trans($theme . '-app.foot.about_us') }}"
                                    href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}"><span>{{ trans($theme . '-app.foot.about_us') }}</span></a>
                            </li>

                            @if (!Session::has('user'))
                                <li>
                                    <a href="{{ config('app.custom_login_url') }}&context_url={{$host}}"
                                        class="color-letter d-flex link-header btn_login_desktop"
                                        title="{{ trans($theme . '-app.login_register.login') }}"
                                        href="javascript:;"><span>{{ trans($theme . '-app.login_register.login') }}</span>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="color-letter d-flex link-header"
                                        title="{{ trans($theme . '-app.login_register.my_panel') }}"
                                        href="{{ \Routing::slug('user/panel/allotments') }}"><span>{{ trans($theme . '-app.login_register.my_panel') }}</span>
                                    </a>
                                </li>
                                @if (Session::get('user.admin'))
                                    <li>
                                        <a class="color-letter d-flex link-header"
                                            title="{{ trans($theme . '-app.login_register.admin') }}" href="/admin"
                                            target="_blank"><span>{{ trans($theme . '-app.login_register.admin') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="color-letter d-flex link-header"
                                        title="{{ trans($theme . '-app.login_register.logout') }}"
                                        href="{{ \Routing::slug('logout') }}"><span>{{ trans($theme . '-app.login_register.logout') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="col-xs-2 header-icons-wrapper mt-1">

                    <div class="social-bar mr-2">
                        @if (config('app.facebook', '') || true)
                            <a href="{{ \Config::get('app.facebook') }}" target="_blank"
                                class="social-link color-letter"><i class="fab fa-facebook-square"></i></a>
                        @endif


                        @if (config('app.instagram', '') || true)
                            <a href="{{ \Config::get('app.instagram') }}" target="_blank"
                                class="social-link color-letter"><i class="fab fa-instagram"></i></a>
                        @endif
                    </div>

                    <a class="search-header" data-display="static">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                    @if (Session::has('user'))
                        <a class="" data-display="static"
                            href="{{ \Routing::slug('user/panel/favorites') }}">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                        </a>
                        <a class="" data-display="static"
                            href="{{ \Routing::slug('user/panel/showShoppingCart') }}">
                            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                        </a>
                    @endif

                </div>




            </div>


        </nav>


    </div>


</header>

@if (!empty(\Config::get('app.gridLots')) && \Config::get('app.gridLots') == 'new')
	<div class="menu-principal-search d-flex align-items-center justify-content-center">
		<form id="formsearchResponsive" role="search" action="{{ route('allCategories') }}"
			class="search-component-form flex-inline position-relative">
			<div class="form-group">
				<input class="form-control input-custom br-100"
					placeholder="{{ trans($theme . '-app.head.search_label') }}" type="text" name="description" />
			</div>
			<button role="button" type="submit"
				class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans($theme . '-app.head.search_button') }}</button>
		</form>
	</div>
@else
	<div class="menu-principal-search d-flex align-items-center justify-content-center">
		<form id="formsearchResponsive" role="search" action="{{ \Routing::slug('busqueda') }}"
			class="search-component-form flex-inline position-relative">
			<div class="form-group">
				<input class="form-control input-custom br-100"
					placeholder="{{ trans($theme . '-app.head.search_label') }}" type="text" name="texto" />
			</div>
			<button role="button" type="submit"
				class="br-100 right-0 position-absolute btn btn-custom-search background-principal">{{ trans($theme . '-app.head.search_button') }}</button>
		</form>
	</div>
@endif

<div class="login_desktop" style="display: none">
    <div class="login_desktop_content">
        <div class="only-login white-background">
            <div class="login-content-form">
                <img class="closedd" role="button" src="/themes/{{ $theme }}/assets/img/shape.png"
                    alt="Close">
                <div class="login_desktop_title">
                    <?= trans($theme . '-app.login_register.login') ?>
                </div>
                <form data-toggle="validator" id="accerder-user-form"
                    class="flex-display justify-center align-items-center flex-column">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <div class="input-login-group">
                            <i class="fa fa-user"></i>
                            <input class="form-control"
                                placeholder="{{ trans($theme . '-app.login_register.user') }}" type="email"
                                name="email" type="text">
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="input-login-group">
                            <i class="fa fa-key"></i>
                            <input class="form-control"
                                placeholder="{{ trans($theme . '-app.login_register.contraseña') }}" type="password"
                                name="password" maxlength="20" autocomplete="off">
                            <img class="view_password eye-password"
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                        </div>
                    </div>
                    <span class="message-error-log text-danger seo_h5"></span></p>
                    <div class="pass-login-content">
                        <div class="text-center">
                            <button id="accerder-user" class="button-principal" type="button">
                                <div>{{ trans($theme . '-app.login_register.acceder') }}</div>
                            </button>
                        </div>
                        <a onclick="cerrarLogin();" class="c_bordered pass_recovery_login"
                            data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery"
                            data-title="{{ trans($theme . '-app.login_register.forgotten_pass_question') }}"
                            href="javascript:;" data-toggle="modal"
                            data-target="#modalAjax">{{ trans($theme . '-app.login_register.forgotten_pass_question') }}</a>

                    </div>
                </form>
                <div class="login-separator"></div>
                <p class="text-center">{{ trans($theme . '-app.login_register.not_account') }}</p>
                <div class="create-account-link">
                    @if (empty($registration_disabled))
                        <a class="" title="{{ trans($theme . '-app.login_register.register') }}"
                            href="{{ \Routing::slug('register') }}">{{ trans($theme . '-app.login_register.register') }}</a>
                    @else
                        <p class="text-center" style="color: darkred;">
                            {{ trans($theme . '-app.login_register.registration_disabled') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
