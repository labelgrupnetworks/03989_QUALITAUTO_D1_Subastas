@php
    use App\libs\TradLib as TradLib;
    use App\Http\Controllers\V5\ArticleController;
    $registration_disabled = Config::get('app.registration_disabled');
    $locale = Config::get('app.locale');
    $envioroment = Config::get('app.env');

	$domains = [
        'local' => Config::get('app.url'),
        'develop' => 'https://auctions-ansorena.labelgrup.com',
        'production' => 'https://www.ansorena.com',
    ];

	$galleryDomains = [
        'local' => Config::get('app.url'),
        'develop' => "https://preprodgaleria.enpreproduccion.com",
        'production' => "https://galeria.ansorena.com",
    ];
    $domain = $domains[$envioroment];
    $galleryDomain = $galleryDomains[$envioroment];

    $pagina = Routing::translateSeo('pagina', '/', $domain);
    $isGallery = in_array(Config::get('app.emp'), ['003', '004']);
    $galleryClass = $isGallery ? 'gallery' : '';

    $cod_cli = Session::get('user.cod');
    $articlesToCart = 0;

    if (!$isGallery && $cod_cli) {
        $articlesToCart = count((new ArticleController())->loadArticleCart());
    }

	$urlSubastaOnline = '';
	if(Arr::has($global, 'subastas.S.O')) {
		$urlSubastaOnline = Routing::translateSeo('subasta-actual-online', null, $domain);
	}

    //Comprobar en que página estamos
    $isSobreNosotros = Routing::currentUrlInArray([$pagina . trans("$theme-app.links.historia"), Routing::translateSeo('equipo', null, $domain), $pagina . trans("$theme-app.links.careers")]);

    $isJoyeria = Routing::currentUrlInArray([$pagina . trans("$theme-app.links.joyas_category"), $pagina . trans("$theme-app.links.condecoraciones")]) || (!Routing::currentUrl(Routing::translateSeo('valoracion-articulos', null, $domain)) && strpos(url()->full(), 'articulos') !== false);

    $isSubastas = Routing::currentUrlInArray([Routing::translateSeo('presenciales', null, $domain), Routing::translateSeo('ventas-destacadas', null, $domain), Routing::translateSeo('subastas-historicas', null, $domain), $pagina . trans("$theme-app.links.buy_and_sell"), Routing::translateSeo('subasta-actual-online', null, $domain)]);

    $isStories = Routing::currentUrlInArray([Routing::translateSeo('blog/comunicacion', null, $domain), Routing::translateSeo('blog/joyeria', null, $domain)]);
@endphp

<header>
    <div class="container {{ $galleryClass }}">

        <div class="select-searching">

            <button class="navbar-toggler flex-center" type="button" aria-expanded="false" aria-label="Menú"
                onclick="toogleMenu(this)"></button>

            <div class="select-container" id="select-container">
                <select name="" id="locale-select">
                    @foreach (array_keys(Config::get('app.locales')) as $lang)
                        <option
                            value="{{ "/$lang" . TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), \App::getLocale(), $lang) }}"
                            @if ($locale === $lang) selected @endif>
                            {{ $lang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <script>
                $('#locale-select').select2({
                    minimumResultsForSearch: Infinity,
                    width: 'resolve',
                    dropdownParent: $('#select-container')
                })
            </script>

            @if (!$isGallery)
                <div class="search-component">
                    <form action="{{ Routing::translateSeo('subasta-actual') }}#grid-lots">
                        <input type="search" name="description"
                            placeholder="{{ trans("$theme-app.global.write_search") }}">
                        <span class="icon flex-center">
                            <img src="/themes/ansorena/assets/img/vectors/search.svg"
                                alt="{{ trans("$theme-app.global.write_search") }}">
                        </span>
                    </form>

                </div>
            @endif

        </div>

        @if (Route::current()->getName() === 'home')
            <h1>
                <a class="logo-link" title="{{ Config::get('app.name') }}" href="{{ $domain . '/' . $locale }}">
                    <img src="/themes/ansorena/assets/img/vectors/logo.svg" alt="{{ Config::get('app.name') }}">
                </a>
            </h1>
        @else
            <a class="logo-link" title="{{ Config::get('app.name') }}" href="{{ $domain . '/' . $locale }}">
                <img src="/themes/ansorena/assets/img/vectors/logo.svg" alt="{{ Config::get('app.name') }}">
            </a>
        @endif

        @if ($isGallery)
            <div class="search-gallery-wrapper">
                <div class="search-component">
                    <form action="{{ Routing::translateSeo('exposiciones') }}">
                        <input type="search" name="search"
                            placeholder="{{ trans("$theme-app.global.write_search") }}">
                        <span class="icon flex-center">
                            <img src="/themes/ansorena/assets/img/vectors/search.svg"
                                alt="{{ trans("$theme-app.global.write_search") }}">
                        </span>
                    </form>
                </div>
            </div>
        @else
            <div class="login-wrapper d-flex">
                @if (!Session::has('user'))
                    <button class="btn btn-white btn-header-sm btn_login flex-center">
                        <svg width="20" height="20" viewBox="0 0 18 21" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.639 6.00943C4.639 3.52379 6.65401 1.50878 9.13965 1.50878C11.6253 1.50878 13.6403 3.52379 13.6403 6.00943C13.6403 8.49507 11.6253 10.5101 9.13965 10.5101C6.65401 10.5101 4.639 8.49507 4.639 6.00943ZM9.13965 0.491241C6.09204 0.491241 3.62146 2.96182 3.62146 6.00943C3.62146 9.05704 6.09204 11.5276 9.13965 11.5276C12.1873 11.5276 14.6578 9.05704 14.6578 6.00943C14.6578 2.96182 12.1873 0.491241 9.13965 0.491241ZM4.13088 12.7812C2.12076 12.7812 0.491226 14.4107 0.491226 16.4208V20.1779H1.50876V16.4208C1.50876 14.9727 2.68273 13.7987 4.13088 13.7987H14.1497C15.5979 13.7987 16.7718 14.9727 16.7718 16.4208V20.1779H17.7894V16.4208C17.7894 14.4107 16.1598 12.7812 14.1497 12.7812H4.13088Z" />
                        </svg>
                        <span>{{ trans("$theme-app.login_register.generic_name") }}</span>
                    </button>
                @else
                    <a href="{{ \Routing::slug('user/panel/orders') }}"
                        class="btn btn-white btn-header-sm flex-center">
                        <svg width="20" height="20" viewBox="0 0 18 21" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.639 6.00943C4.639 3.52379 6.65401 1.50878 9.13965 1.50878C11.6253 1.50878 13.6403 3.52379 13.6403 6.00943C13.6403 8.49507 11.6253 10.5101 9.13965 10.5101C6.65401 10.5101 4.639 8.49507 4.639 6.00943ZM9.13965 0.491241C6.09204 0.491241 3.62146 2.96182 3.62146 6.00943C3.62146 9.05704 6.09204 11.5276 9.13965 11.5276C12.1873 11.5276 14.6578 9.05704 14.6578 6.00943C14.6578 2.96182 12.1873 0.491241 9.13965 0.491241ZM4.13088 12.7812C2.12076 12.7812 0.491226 14.4107 0.491226 16.4208V20.1779H1.50876V16.4208C1.50876 14.9727 2.68273 13.7987 4.13088 13.7987H14.1497C15.5979 13.7987 16.7718 14.9727 16.7718 16.4208V20.1779H17.7894V16.4208C17.7894 14.4107 16.1598 12.7812 14.1497 12.7812H4.13088Z" />
                        </svg>
                        <span>{{ trans("$theme-app.login_register.my_panel") }}</span>
                    </a>
                @endif

                @if (Session::get('user.admin'))
                    <a href="/admin" target="_blank" class="btn btn-white btn-header-sm flex-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor"
                            class="bi bi-person-gear" viewBox="0 0 16 16">
                            <path
                                d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                        </svg>
                        <span>{{ trans("$theme-app.login_register.admin") }}</span>
                    </a>
                @endif

                <a href="{{ route('showArticleCart', ['lang' => \Config::get('app.locale')]) }}"
                    class="btn btn-white btn-header-xs flex-center shopping-cart-btn">

                    @if ($articlesToCart)
                        <span class="articles-cart">{{ $articlesToCart }}</span>
                    @endif
                </a>
            </div>
        @endif
    </div>
</header>

<nav id="menu-header" class="menu-header open open-lg">

    <ul>
        <div>
            <li>
                <a @if ($isSobreNosotros) class="lb-link-underline" @endif href="#nav-sobrenosotros"
                    role="tab">{{ trans("$theme-app.foot.about_us") }}</a>
            </li>
        </div>

        <div>
            <li class="d-none d-lg-block">
                <a @if ($isJoyeria) class="lb-link-underline" @endif href="#subnav-joyeria"
                    role="tab">{{ trans("$theme-app.foot.joyeria") }}</a>
            </li>
			<li class="d-lg-none">
                <a @if ($isJoyeria) class="lb-link-underline" @endif href="{{ $pagina . trans("$theme-app.links.joyas_category") }}">{{ trans("$theme-app.foot.joyeria") }}</a>
            </li>
            <li>
                <a @if ($isSubastas) class="lb-link-underline" @endif href="#subnav-subastas"
                    role="tab">{{ trans("$theme-app.subastas.auctions") }}</a>
            </li>
            <li>
                <a @if ($isGallery) class="lb-link-underline" @endif
                    href="{{ "$galleryDomain/$locale" }}">{{ trans("$theme-app.galery.galery") }}</a>
            </li>

            <li>
                <a @if (Routing::currentUrl(Routing::translateSeo('valoracion-articulos', null, $domain))) class="lb-link-underline" @endif
                    href="{{ Routing::translateSeo('valoracion-articulos', null, $domain) }}">{{ trans("$theme-app.home.free-valuations") }}</a>
            </li>
        </div>

        <div>
            <li>
                <a @if ($isStories) class="lb-link-underline" @endif
                    title="{{ trans("$theme-app.blog.principal_title") }}" role="tab"
                    href="#subnav-stories">{{ trans("$theme-app.blog.principal_title") }}</a>
            </li>

            <li>
                <a @if (Routing::currentUrl(Routing::translateSeo(trans("$theme-app.links.contact"), null, $domain))) class="lb-link-underline" @endif
                    title="{{ trans("$theme-app.foot.contact") }}"
                    href="{{ Routing::translateSeo(trans("$theme-app.links.contact"), null, $domain) }}">{{ trans("$theme-app.foot.contact") }}</a>
            </li>
        </div>

    </ul>

	<ul class="menu-header__langs">
		@foreach(Config::get('app.locales') as $key => $value)
			@if(Config::get('app.locale') == $key)
			<li>
				<p class="lb-link-underline" style="text-transform: uppercase">{{ $key }}</p>
			</li>
			@else
			<li>
				<a title="{{ trans($theme.'-app.head.language_'.$key) }}" href="/{{ $key }}">
					<p style="text-transform: uppercase">{{ $key }}</p>
				</a>
			</li>
			@endif

			@if($loop->first)
			<li>
				<span>|</span>
			</li>
			@endif
		@endforeach
	</ul>

</nav>

<div id="submenu-header" class="submenu-wrapper">

    <div class="container position-relative">
        <button type="button" class="btn-close" aria-label="Close" onclick="closeSubmenu()"></button>
    </div>

    <div class="submenu-block">
        <nav class="subment-nav tab-content">
            <div role="tabpanel" id="nav-sobrenosotros">
                <div class="d-flex flex-column gap-4">
                    <p class="position-relative subnav-title">
                        <button type="button" class="btn-close" aria-label="Close"
                            onclick="closeSubmenu()"></button>
                        <span>{{ trans("$theme-app.foot.about_us") }}</span>
                    </p>
                    <a
                        href="{{ $pagina . trans("$theme-app.links.historia") }}">{{ trans("$theme-app.foot.history") }}</a>
                    <a
                        href="{{ Routing::translateSeo('equipo', null, $domain) }}">{{ trans("$theme-app.foot.team") }}</a>
                    <a
                        href="{{ $pagina . trans("$theme-app.links.careers") }}">{{ trans("$theme-app.foot.work_with_us") }}</a>
                </div>
            </div>
            <div role="tabpanel" id="nav-joyeria-subastas">
                <div role="tabpanel" id="subnav-joyeria">
                    <div class="d-flex flex-column gap-4">

                        <p class="position-relative subnav-title">
                            <button type="button" class="btn-close" aria-label="Close"
                                onclick="closeSubmenu()"></button>
                            <span>{{ trans("$theme-app.foot.joyeria") }}</span>
                        </p>

                        <a href="{{ $pagina . trans("$theme-app.links.joyas_category") }}">
                           {{ trans("$theme-app.foot.jewellery_catalog") }}
                            <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="6.2006" height="0.688955"
                                    transform="matrix(0.707107 -0.707107 -0.707107 -0.707107 0.739258 9.00781)"
                                    fill="#0F0E0D" />
                                <rect x="4.38477" y="4.875" width="6.2006" height="0.688955"
                                    transform="rotate(-135 4.38477 4.875)" fill="#0F0E0D" />
                            </svg>
                        </a>

                        <div class="subnav-joyeria-content">
                            <div>

                                <p class="ff-highlight subnav-title-highlight">
                                    {{ trans("$theme-app.subastas.joyas") }}
                                </p>
                                <div class="d-grid grid-2 subnav-list">
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/solitarios', null, $domain) }}">
                                        {{ trans("$theme-app.articles.solitaires") }}
                                    </a>
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/sortijas', null, $domain) }}">
                                        {{ trans("$theme-app.articles.rings") }}
                                    </a>
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/pendientes', null, $domain) }}">
                                        {{ trans("$theme-app.articles.earrings") }}
                                    </a>
                                    <a href="{{ Routing::translateSeo('articulos-joyeria/broches', null, $domain) }}">
                                        {{ trans("$theme-app.articles.broochs") }}
                                    </a>
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/collares', null, $domain) }}">
                                        {{ trans("$theme-app.articles.neckclaces") }}
                                    </a>
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/colgantes', null, $domain) }}">
                                        {{ trans("$theme-app.articles.Pendants") }}
                                    </a>
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/pulseras', null, $domain) }}">
                                        {{ trans("$theme-app.articles.bracelets") }}
                                    </a>
                                    <a href="{{ Routing::translateSeo('articulos-joyeria/gemelos', null, $domain) }}">
                                        {{ trans("$theme-app.articles.Cufflinks") }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <p class="ff-highlight subnav-title-highlight">
                                    {{ trans("$theme-app.articles.engagement") }}
                                </p>
                                <div class="d-flex flex-column subnav-list">
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/solitarios', null, $domain) }}">
                                        {{ trans("$theme-app.articles.solitaires") }}
                                    </a>
                                    <a
                                        href="{{ Routing::translateSeo('articulos-compromiso/alianzas-bodas', null, $domain) }}">
                                        {{ trans("$theme-app.articles.rings_a") }}
                                    </a>
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/pendientes', null, $domain) }}">
                                        {{ trans("$theme-app.articles.earrings") }}
                                    </a>
                                    <a
                                        href="{{ Routing::translateSeo('articulos-joyeria/pulseras', null, $domain) }}">
                                        {{ trans("$theme-app.articles.bracelets") }}
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex flex-column justify-content-between gap-4">
                                <div>
                                    <p class="ff-highlight subnav-title-highlight">
										 {{-- {{ trans("$theme-app.articles.high_jelwelry") }} --}}
										Casilda se casa
                                    </p>
                                    <div class="d-flex flex-wrap subnav-list">
										<a href="{{ Routing::translateSeo('articulos-joyeria/sortijas', null, $domain) . '?search=casilda+se+casa' }}">
                                        	{{ trans("$theme-app.subastas.see-all") }}
                                    	</a>
                                        {{-- <a
                                            href="{{ Routing::translateSeo('articulos', null, $domain) . '?ortsec=11&sec=JX' }}">
												{{ trans("$theme-app.subastas.see-all") }}
											</a> --}}
                                    </div>
                                </div>

                                <div>
                                    <p class="ff-highlight subnav-title-highlight">
                                        {{ trans("$theme-app.foot.condecoraciones") }}
                                    </p>
                                    <div class="d-flex flex-wrap subnav-list">
                                        <a
                                            href="{{ $pagina . trans("$theme-app.links.condecoraciones") }}">{{ trans("$theme-app.subastas.see-all") }}</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" id="subnav-subastas">

                    <div class="d-flex flex-column gap-4">
                        <p class="position-relative subnav-title">
                            <button type="button" class="btn-close" aria-label="Close"
                                onclick="closeSubmenu()"></button>
                            <span>{{ trans("$theme-app.subastas.auctions") }}</span>
                        </p>

                        <a href="{{ Routing::translateSeo('presenciales', null, $domain) }}">
                            {{ trans("$theme-app.home.home") }} {{ trans("$theme-app.subastas.auctions") }}
                            <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="6.2006" height="0.688955"
                                    transform="matrix(0.707107 -0.707107 -0.707107 -0.707107 0.739258 9.00781)"
                                    fill="#0F0E0D" />
                                <rect x="4.38477" y="4.875" width="6.2006" height="0.688955"
                                    transform="rotate(-135 4.38477 4.875)" fill="#0F0E0D" />
                            </svg>
                        </a>

						@if($urlSubastaOnline)
						<a href="{{ $urlSubastaOnline }}">
                            Subasta online
                        </a>
						@endif


                        <a href="{{ Routing::translateSeo('ventas-destacadas', null, $domain) }}">
                            {{ trans("$theme-app.lot_list.featured-sales") }}
                        </a>
                        <a href="{{ Routing::translateSeo('subastas-historicas', null, $domain) }}">
                            {{ trans("$theme-app.artist.passAuctions") }}
                        </a>
                        <a href="{{ $pagina . trans("$theme-app.links.buy_and_sell") }}">
                            {{ trans("$theme-app.foot.buy_and_sell") }}
                        </a>

                    </div>

                </div>
            </div>
            <div role="tabpanel" id="subnav-stories">
                <div class="d-flex flex-column gap-4">
                    <p class="position-relative subnav-title">
                        <button type="button" class="btn-close" aria-label="Close"
                            onclick="closeSubmenu()"></button>
                        <span>{{ trans("$theme-app.blog.principal_title") }}</span>
                    </p>

					<a href="{{ Routing::translateSeo('blog', null, $domain) }}">
						{{ trans("$theme-app.home.home") }} STORIES
						<svg width="6" height="10" viewBox="0 0 6 10" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<rect width="6.2006" height="0.688955"
								transform="matrix(0.707107 -0.707107 -0.707107 -0.707107 0.739258 9.00781)"
								fill="#0F0E0D" />
							<rect x="4.38477" y="4.875" width="6.2006" height="0.688955"
								transform="rotate(-135 4.38477 4.875)" fill="#0F0E0D" />
						</svg>
					</a>

					<a href="{{ Routing::translateSeo('blog/joyeria', null, $domain) }}">{{ trans("$theme-app.foot.joyeria") }}</a>

					<a href="{{ Routing::translateSeo('blog/comunicacion', null, $domain) }}">{{ trans("$theme-app.subastas.auctions") }}</a>

					<a href="{{ Routing::translateSeo('blog/noticias', null, $galleryDomain) }}">{{ trans("$theme-app.galery.galery") }}</a>



                </div>
            </div>
        </nav>
    </div>
</div>

<div class="login_desktop container-fluid" style="display: none">
    <div class="h-100 d-flex justify-content-center align-content-center">
        <div class="login_desktop_content m-auto">
            <div class="only-login bg-white position-relative">
                <div class="text-center">
                    <button type="button" class="btn-close closedd" aria-label="Close"></button>

                    <p class="login_desktop_title h1">{{ trans($theme . '-app.login_register.login') }}</p>

                    <form data-toggle="validator" id="accerder-user-form">
                        @csrf

                        <div class="form-floating">
                            <input type="email" class="form-control" id="floatingInput" name="email"
                                placeholder="email@example.com">
                            <label for="floatingInput">{{ trans("$theme-app.login_register.ph_user") }}</label>
                        </div>

                        <div class="form-floating input-group">
                            <input type="password" name="password" class="form-control" id="floatingPassword"
                                placeholder="contraseña">
                            <label for="floatingPassword">{{ trans("$theme-app.login_register.password") }}</label>
                            <span class="input-group-text view_password">
                                <img class="eye-password"
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAQAAAD8x0bcAAAAxUlEQVR4AcWQIQxBURSGvyF5EwiSINMDNlU3sxmaLtoMk5iIRhAFM8Vkm170LOgU4Ozu7D7P63vfH+79z/23c+4hSJK0GYo6lAiDnyJrnnysLjT5Y24eHsyoiGYa3+FgWZnSkzyQEkFBYwdCGFraYAlM5HwzAhZa7SPEuKqtk7ETZanr7U4cEtzU1kjbUFqcGxJ6bju993/ajTGE2PsGz/EytTNRFIeNXUFVNNW/nYjhocGFj2eZAxx8RCjRZcuRHWVxQfEFCcppAFXu2JUAAAAASUVORK5CYII=">
                            </span>
                        </div>

                        <p class="message-error-log text-danger d-none"></p>

                        <button id="accerder-user" class="btn btn-lb-primary btn-fluid" type="submit">
                            <span class="text">{{ trans($theme . '-app.login_register.acceder') }}</span>
                            <div class="spinner spinner-1 m-auto"></div>
                        </button>

                    </form>

                    <div class="d-flex flex-column gap-3">

                        <a onclick="cerrarLogin();" class="c_bordered fs-16"
                            data-ref="{{ \Routing::slug('password_recovery') }}" id="p_recovery"
                            data-title="{{ trans($theme . '-app.login_register.forgotten_pass_question') }}"
                            href="javascript:;" data-bs-toggle="modal" data-bs-target="#modalAjax"
                            data-toggle="modal" data-target="#modalAjax">
                            {{ trans($theme . '-app.login_register.forgotten_pass_question') }}
                        </a>

                        <div class="login-separator" data-content=""></div>

                        <p class="text-center mb-2 fs-16">{{ trans($theme . '-app.login_register.not_account') }}</p>

                        <div>
                            @if (empty($registration_disabled))
                                <a class="btn btn-lb-primary btn-medium"
                                    title="{{ trans("$theme-app.login_register.register") }}"
                                    href="{{ \Routing::slug('register') }}">
                                    {{ trans("$theme-app.login_register.register") }}
                                </a>
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
</div>
