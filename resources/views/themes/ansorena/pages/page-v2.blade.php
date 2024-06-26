@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @php
        $html = $data['data']->content_web_page;
        if (request('static')) {
            $page = request('static');
            if (view()->exists("pages.static.$page")) {
                $html = view("pages.static.$page")->render();
            }
        }

        $menuEstaticoHtml = null;
        #recogemos el valor del banner en $matches[1], en $matches[0] esta todo el código escrito [*BANNER-x*]
        $menusEstaticos = ['MENUCONDECORACIONES', 'MENUANSORENA', 'MENUJOYAS', 'MENUSUBASTAS', 'MENUJOYERIA'];

        foreach ($menusEstaticos as $key) {
            #si aun no ha encontrado un menu que sustituir
            if (empty($menuEstaticoHtml)) {
                $menuEstatico = strpos($html, '[*' . $key . '*]');

                if ($menuEstatico !== false) {
                    $pagina = new App\Models\Page();

                    $menuEstaticoHtml = $pagina->getPagina($data['lang'], $key);
                    #borramos la clave [*MENUCONDECORACIONES*], por que la pondremos a mano en la página

                    $html = str_replace('[*' . $key . '*]', $menuEstaticoHtml->content_web_page, $html);
                }
            }
        }

        #recogemos el valor del banner en $matches[1], en $matches[0] esta todo el código escrito [*BANNER-x*]
        preg_match_all('/\[\*BANNER\-(.*?)\*\]/', $html, $matches);

        #reemplazamos las claves en el texto
        foreach ($matches[0] as $key => $replace) {
            $options = [
                'dots' => false,
                'pauseOnHover' => false,
                'arrows' => false,
                'infinite' => true,
                'autoplay' => $key === 'HISTORIA',
            ];

			$bannerName = '';
			$view = null;

			if(strpos($matches[1][$key], '-VIEW:') !== false) {
				[$bannerName, $view] = explode('-VIEW:', $matches[1][$key]);
				$banner = BannerLib::bannerWithView($bannerName, $view, [], $options);
			}
			else {
				$banner = \BannerLib::bannersPorKey($matches[1][$key], 'BANNER_' . $matches[1][$key], $options);
			}

            //
			//BannerLib::bannerWithView('home-top-banner', 'fluid', [], ['autoplay' => false]);
			//$bannerContent = BannerLib::getOnlyContentForBanner($matches[1][$key]);


            $html = str_replace($replace, $banner, $html);
        }

		preg_match_all('/\[\*MENU\-(.*?)\*\]/', $html, $menuMatches);
		foreach ($menuMatches[0] as $key => $replace) {
			$menu = (new App\Models\Page())->getPagina($data['lang'], "MENU-{$menuMatches[1][$key]}");
			$html = str_replace($replace, $menu->content_web_page, $html);
		}

		preg_match_all('/\[\*CARROUSEL\-(.*?)\*\]/', $html, $carrouselMatches);
		foreach ($carrouselMatches[0] as $key => $replace) {
			$namePage = $carrouselMatches[1][$key];
			if (view()->exists("includes.carrousel.$namePage")) {
				$render = view("includes.carrousel.$namePage")->render();
				$html = str_replace($replace, $render, $html);
			}
		}

        #reemplazamos las redes sociales
        $redesSociales = strpos($html, '[*REDESSOCIALES*]');

        if ($redesSociales !== false) {
            $html = str_replace('[*REDESSOCIALES*]', '', $html);
        }

    @endphp

    <div id="pagina-{{ $data['data']->id_web_page }}" class="contenido contenido-web">
        {!! $html !!}
        @if ($redesSociales !== false)
            @include('includes.sharePage')
        @endif
    </div>
@stop
