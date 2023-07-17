@php
    $isGallery = in_array(Config::get('app.emp'), ['003', '004']);
    #Galeria


    $locale = Config::get('app.locale');

    $envioroment = Config::get('app.env');
    $domains = [
        'local' => 'http://www.newsubastas.test',
        'develop' => 'https://auctions-ansorena.labelgrup.com',
        'production' => 'https://www.ansorena.com',
    ];
    $galleryDomains = [
        'local' => "http://www.gallery.test/$locale",
        'develop' => "https://www.preprodgaleria.enpreproduccion.com/$locale",
        'production' => "https://galeria.ansorena.com/$locale",
    ];
    $domain = $domains[$envioroment];
    $galleryDomain = $galleryDomains[$envioroment];

    $pagina = Routing::translateSeo('pagina', '/', $domain);
@endphp

@if ($isGallery)
    @include('content.home_galery')
@else
    @include('content.home_subastas')
@endif
