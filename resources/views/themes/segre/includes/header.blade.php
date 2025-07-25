@php
    use App\libs\TradLib;
    use Illuminate\Support\Str;

    $lang = config('app.locale');
    $registration_disabled = Config::get('app.registration_disabled');
    $searchAction = config('app.gridLots', false) == 'new' ? route('allCategories') : \Routing::slug('busqueda');
    $pageName = Route::currentRouteName();
    $current_page_url = url()->current();
@endphp

<header>

    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <button class="navbar-toggler collapsed" data-bs-toggle="collapse" data-bs-target="#navbarHeader" type="button"
                aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand" href="/{{ $lang }}" title="{{ \Config::get('app.name') }}">
                <x-icon.logo />
            </a>

            <div class="navbar-collapse collapse flex-grow-1 justify-content-center" id="navbarHeader" style="">
                <ul class="navbar-nav gap-md-4 mb-2 mb-lg-0">

					<li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            {{ trans("web.home.us") }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('contact_page') }}">
                                    {{ trans('web.foot.contact') }}
                                </a>
                            </li>
							<li>
								<a class="dropdown-item" href="{{ Routing::translateSeo('pagina') . trans('web.segre-enlaces.experts') }}">
									{{ trans('web.foot.experts') }}
								</a>
							</li>
							<li>
								<a class="dropdown-item"
									href="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale')]) }}"
									title="{{ trans("$theme-app.home.free-valuations") }}">
									{{ trans("$theme-app.home.free-valuations") }}
								</a>
							</li>
                        </ul>
                    </li>

                    @if($global['auctionTypes']->where('tipo_sub', 'W')->value('count'))
                        <li class="nav-item">
                            <a href="{{ route('subasta.actual') }}" @class([
                                'nav-link',
                                'lb-text-primary' => $pageName === 'subastas.presenciales',
                            ])>
                                <span>{{ trans($theme . '-app.foot.auctions') }}</span>
                            </a>
                        </li>
                    @endif

					<li class="nav-item">
						<a class="nav-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">
							<span>{{ trans($theme . '-app.foot.historico') }}</span>
						</a>
					</li>

                    <li class="nav-item">
                        <a href="{{ Routing::translateSeo('pagina') . trans("$theme-app.segre-enlaces.bid_auction_room") }}"
                            @class([
                                'nav-link',
                                'lb-text-primary' => Str::contains(
                                    $current_page_url,
                                    Routing::translateSeo('pagina') .
                                        trans("$theme-app.segre-enlaces.bid_auction_room")),
                            ])>
                            <span>
                                {{ Str::of(trans("$theme-app.foot.bid_auction_room"))->lower()->ucfirst() }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ Routing::translateSeo('pagina') . trans("$theme-app.segre-enlaces.sell_in_segre") }}"
                            @class([
                                'nav-link',
                                'lb-text-primary' => Str::contains(
                                    $current_page_url,
                                    Routing::translateSeo('pagina') .
                                        trans("$theme-app.segre-enlaces.sell_in_segre")),
                            ])>
                            <span>
                                {{ Str::of(trans("$theme-app.foot.sell_in_segre"))->words(1, '') }}
                            </span>
                        </a>
                    </li>

                    <li class="d-lg-none nav-item">
                        @if (!Session::has('user'))
                            <a class="nav-link btn_login" href="javascript:;">
                                {{ trans("$theme-app.login_register.login") }}
                            </a>
                        @else
                            <a class="nav-link" href="{{ \Routing::slug('user/panel/orders') }}">
                                {{ trans("web.user_panel.mi_cuenta") }}
                            </a>
                        @endif
                    </li>

					@if(Session::get('user.admin'))
					<li class="d-lg-none nav-item">
						<a class="nav-link" href="/admin">
							{{ trans('web.login_register.admin') }}
						</a>
					</li>
					@endif

                    <li class="d-flex gap-3 d-lg-none nav-item">
                        @foreach (Config::get('app.locales') as $key => $value)
                            @php
                                $route = '';
                                if ($key != $lang) {
                                    $translateRoute = TradLib::getRouteTranslate(
                                        substr($_SERVER['REQUEST_URI'], 4),
                                        $lang,
                                        $key,
                                    );
                                    $route = "/$key" . $translateRoute;
                                }
                            @endphp
                            <a href="{{ $route }}" title="{{ trans("$theme-app.head.language_$key") }}"
                                @class(['selected' => $key == $lang, 'nav-link']) translate="no">
                                <span translate="no">{{ trans("$theme-app.home.$key") }}</span>
                            </a>
                        @endforeach
                    </li>

                    <li class="d-lg-none nav-item">

                        <form role="search" action="{{ $searchAction }}">
							<input type="hidden" name="historic" value="1">
                            <div class="input-group py-2">

                                <input class="form-control form-control-sm" name="description" type="search"
                                    aria-label="{{ trans("$theme-app.head.search_label") }}"
                                    placeholder="{{ trans("$theme-app.head.search_label") }}">

                                <button class="input-group-text" type="submit">
                                    <x-icon.fontawesome icon="magnifying-glass" />
                                </button>

                            </div>
                        </form>
                    </li>
                </ul>

                {{-- @include('components.search', ['classes' => 'me-auto d-xxl-none']) --}}

            </div>

            <div class="flex-grow-0" id="navbarHeader2">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        @include('includes.header.language_selector')

                        @if (!Session::has('user'))
                            <button class="btn btn-link btn_login">
                                <x-icon.fontawesome type="regular" icon="user" />
                            </button>
                        @else
                            <a class="btn btn-link" href="{{ \Routing::slug('user/panel/orders') }}">
                                <x-icon.fontawesome type="regular" icon="user" />
                            </a>
                        @endif

						@if(Session::get('user.admin'))
						<a class="btn btn-link" href="/admin">
							<x-icon.boostrap icon="gear" />
						</a>
						@endif

                        <form role="search" action="{{ $searchAction }}">
							<input type="hidden" name="historic" value="1">
                            <div class="input-group input-serach-group h-100 position-relative">

                                <input class="form-control" name="description" type="search"
                                    aria-label="{{ trans("$theme-app.head.search_label") }}"
                                    placeholder="{{ trans("$theme-app.head.search_label") }}">

                                <button class="btn
                                    btn-link input-group-text"
                                    id="btnGroupAddon2" type="button" onclick="handleSearch()">
                                    <x-icon.fontawesome icon="magnifying-glass" />
                                </button>

                            </div>
                        </form>

                    </div>

                </div>

            </div>

        </div>
    </nav>

</header>


<div class="login_desktop container-fluid" style="display: none">
    <div class="h-100 d-flex justify-content-center align-content-center">
        <div class="login_desktop_content m-auto">
            <div class="only-login bg-white position-relative">
                <div class="login-content-form">
                    <img class="closedd" src="/themes/{{ $theme }}/assets/img/shape.png" alt="Close"
                        role="button">

                    <p class="h1 login_desktop_title text-black">
                        Segre
                    </p>
                    <p class="text-uppercase">{{ trans("web.login_register.login") }}</p>


                    <form class="d-flex align-items-center justify-content-center flex-column py-4"
                        id="accerder-user-form" data-toggle="validator">
                        @csrf

                        <div class="form-floating w-100 mb-3">
                            <input class="form-control" name="email" type="email" autocomplete="email"
                                placeholder="name@example.com">
                            <label for="floatingInput">
                                {{ trans("$theme-app.login_register.user") }}
                            </label>
                        </div>

                        <div class="input-group w-100 mb-3">
                            <div class="form-floating">
                                <input class="form-control" name="password" type="password" autocomplete="off"
                                    placeholder="****">
                                <label for="floatingInput">
                                    {{ trans("$theme-app.login_register.contraseña") }}
                                </label>
                            </div>
                            <span class="input-group-text view_password_floating">
                                <x-icon.boostrap class="eye-password" icon="eye" />
                            </span>
                        </div>

                        <button class="btn btn-lb-primary text-black w-100 mb-4" id="accerder-user" type="submit">
                            <span class="text">{{ trans($theme . '-app.login_register.acceder') }}</span>
                            <div class="spinner spinner-1 m-auto"></div>
                        </button>

                        <small><span class="message-error-log text-danger seo_h5"></span></small>

                        <small>
                            <a class="c_bordered text-black text-decoration-none" id="p_recovery"
                                data-ref="{{ \Routing::slug('password_recovery') }}"
                                data-title="{{ trans($theme . '-app.login_register.forgotten_pass_question') }}"
                                data-bs-toggle="modal" data-bs-target="#modalAjax" href="javascript:;"
                                onclick="cerrarLogin()">
                                {{ trans("$theme-app.login_register.forgotten_pass_question") }}
                            </a>
                        </small>

                    </form>

                    <div class="login-separator"></div>

                    <div class="create-account-link">
                        @if (empty($registration_disabled))
                            <p class="text-center mb-2">
                                <span>
                                    {{ trans($theme . '-app.login_register.not_account') }}
                                </span>
                                <a class="text-black text-decoration-none fw-bold"
                                    href="{{ \Routing::slug('register') }}"
                                    title="{{ trans($theme . '-app.login_register.register') }}">
                                    {{ trans($theme . '-app.login_register.register') }}
                                </a>
                            </p>
                        @else
                            <p class="text-center" style="color: darkred;">
                                {{ trans($theme . '-app.login_register.registration_disabled') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
