@extends('layouts.tasacion')
@include('includes.google_head')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

<body class="landing">

    <header>
        <div class="header-responsive hidden-md hidden-lg">
            <div class="col-xs-6 dch">
                <div class="hamburguer text-center">
                    <i class="fa fa-bars"></i>
                </div>
                <div class="user">
                    <a href="/es/user/panel/orders">
                        <i class="fa fa-user-circle"></i>
                    </a>
                </div>
                <div class="user">
                    <a class="btn" href="/admin"><i class="fab fa-buysellads"></i></a>
                </div>
            </div>
            <div class="col-xs-6 izq">
                <div class="hidden search-img-mobile">
                    <i class="fa fa-search"></i>
                    <i class="fa fa-times-circle"></i>
                </div>
                <div class="search-img-lenguaje lenguaje">
                    <ul>
                        <li>
                            <a title="Español" href="/en">
                                <img class="img-responsive" src="/themes/<?= \Config::get('app.theme') ?>/assets/img/flag_en.png" />
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="facebook-mobile">
                    <a target="_blank" href="tauleryfau">
                        <i class="fab fa-2x fa-facebook-square"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="search-bar-responsive hidden-md hidden-lg">
            <form role="search" action="/es/busqueda" class="search-component-form">
                <input type="text">
                <button><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="header-desktop inline-flex">
                        <div class="logo col-xs-12 col-md-12 text-center">

                            <a title="Tauler y Fau" href="/es">
                                <img class="img-responsive" src="/themes/<?= \Config::get('app.theme') ?>/img/comprar-monedas/logo.png"
                                    alt="Tauler y Fau">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="super-slogan">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="slogan-principal text-center">
                        <h1>Tienda online - Accesorios Numismáticos</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="landing-body">
        <div class="" style='position: relative'>
            <div class="banner-landing">
                <a href="/subasta/02012019-accesorios-1242" style='color:white'>
                    <div class="banner-landing-box text-center">
                        <div class="banner-landing-title">
                            ¡VISITE YA NUESTRA TIENDA!
                        </div>

                    </div>
                </a>

            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12 no-padding">
                    <div class="landing-body-header">
                        <h2>Todo para el coleccionista</h2>
                    </div>

                    <div class="landing-body-list-content ">
                        <ul class="col-xs-12 col-md-12 list-content">
                            <div>
                                <li>
                                    <img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px"> Las mejores marcas del sector</li>
                                <li><img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px"> Envío gratuito por compras superiores a
                                    50€</li>
                                <li><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIgAAAB+CAYAAAD/cVlWAAAACXBIWXMAAAsSAAALEgHS3X78AAAGMUlEQVR4nO2d/XHbRhDF15n8L6YC0RWEqcBUB3IFoiowVUGoCixWYKoDuwJLFZisIFQHZAXKYGYxg/EARwC3d7t7eL8CyAP58Pbjvj68v78TAF38gV8GhIBAQBAIBASBQEAQCAQEgUBAEAgEBIFAQBAIpDzmRLQjoqXEk0EgZbEhoj0R3Uk91Z9T/0ULYcmucS39OHAQ38yI6ImIfqYQB8FBXJPMNZpgNtcfM841vlwY+QeJJ4OD+GLBrvF3rlEjB/HDiohecoqD4CBu2EmWrkOAQGwz03CNJggxdqnyjaOmOAgCMUuVb/wioivtAUIg9qhK2G+RozpLPRVyEFtIJaN7qaeCg9igSka/a1UqIeAg+qhXKiHgILqYFgdBIKqYFwdBIGq4EAdBICq4EQdBINlxJQ7CepCs5BaHyHoQCCQf+8zOISIQhJg8ZF3kIwkEkh61tRwSQCBpWSuKYybxIRBIOqop+6+K37+Q+BAIJA0L3q/iHghEnrqc1V7sAwcxigVxEHIQm1gqZ+cSHwKByLEyVs6KCASdVBkWhkJLzVkizEAg8ViegItutyPExPNkuI0efcoQBBKHtbzjd6LzEAhkPHMHzTAIRJGdsaS0DYQYJardb58cjDO6m4oqZjgL3jfrhb+I6DR2rHCQ4eycjTcqzEAgw9g4XBkWFWYQYvrjLbScucp6igkx2JvbH0+hZctuN1oYNRBIP9ZOQssrN++OUh+IEHOZOW9ZsNzzOLMwvkt/MJLUyzwZF8eWRSwuDoKDXGTJ56Bb5K1xdmoyPDmIyBrLgVhNTJ8ba1CS4kEgS84BXqTWWfakqgKSHpQ/girX+MzOEV2h9MGyQGb8Bv/kCuIq4+zpnCsXSxzYNZLkGl1YFciaS7Xf11rcSa21vMDGWGK6bRysmxVrAqnDydfAH7TJMAYri4CqkHKv6WZWqpi+d6DUfEz4Nr0YmcqvqpRbyTNPx2DBQerOX19xUMJcZGlEHHW+oSoOUnaQev/q2D/kJkGZdzRQuTzzS2MCDQepL+L7Ffm2SuciKwPieLQkDlJwkJVw61rSRbTd495iYy6Xg9Rdv2/C5aOUi2i6R938Mtm1Te0gQ6uTMUi8eVrucW6U9iZJKRDpcNLFW2TzbCVwP8sYzIuDEoWYVOGki+vIxC51460NF+IgYYFIVSdjGPsna+QebsRBggIZ0+yS5HpkOzq3e7gSBwnkILHNLknOnIv0nQbPnXu4EwdFOIhmOOniaqCL5GxIuRQHjXSQXNXJGPq6SM6lhG7FQSMc5DZjdTKGvi6S0z1WXsVBIx1kzsmd5YNTQssBqvH/l2kcJtvnQxiTgxz5rbjhjToWCVUnudzj0bs4SKiTajUn6XKRU4axmpqyj0GiD7Jj297qPUYrbS6yyiCO11LEQYKNshMnhze8GsoCdy17aW4Tj+uQ4TuykmqybsOC0Q47r40DVFInp67L2S5SrQfZ8NurncR+aggkte27Lme7SLlg6Mh/zgO/XVrUuUhKgTzk3tCUi1xLDuvd51pnbDwkvP2pmIqljdxrUqu3+d+cX5iYA7tkln2yGmhse6j3l1rbGD2Us9Z2yJxobHvY8w/7Q++xRbgtXRykuLPuxD/wvXICO5aHHGdzWMDC3lxvIedHac2wEBb25noKOYeSK5Y2rBz/UIecRwNj6eKc82QfK1g7H2TDeYlF1iV2Si9h9ZRDa5cEFt0MC2H5GEwrIjkonbBoAsuH2NXJq+bygfOUKpY2rB+DWU/4aYlE9Nxzj3g4J/WkJJIq7yhyhnYIno7iznmBcfGTcH3xdBR3LieZZL+jC2+3PeQQyWaK/Y4uvN72MOPkUboEntQ8Sx88Xwci3ScZejrAJPB8odCew43UcgHkHS14v3FqL9QC36KkbaeUG6diDoN543AF92ihlDvrdhFLBRBaApR0qeGGu59D2E5l6eBYSrzUcN+z24rQ0oMSr0Vd8p9/CYSWHpQokHr5Yqj8fUZo6UfJ9+Z2VTZoiA2g5Ju3uyqbNcTRnyncvN28g655XgjowRQEMuPKptqY9Q9maodRcoipqZPWZ4hjOFNwEBDBFBwERACBgCAQCAgCgYAgEAgIAoGAIBAICAKBgG6I6H+XYX81bhJJaQAAAABJRU5ErkJggg=="
                                        width='25' style="margin-left: 10px"> Ofertas exclusivas</li>
                            </div>
                        </ul>
                    </div>



                </div>
                <div class="col-md-6 col-xs-12 no-padding">
                    <div class="landing-body-header">
                        <h2>Material Numismático</h2>
                    </div>

                    <div class="landing-body-list-content ">
                        <ul class="col-xs-12 col-md-7 col-md-offset-3 ul-list-landing list-content">
                            <div>
                                <li>
                                    <p>Los mejores descuentos en accessorios numismáticos</p>
                                </li>
                                <li>
                                    <p>Maletines, estuches y funcdas de alta calidad perfectas para conservar su
                                        colección de monedas antiguas</p>
                                </li>
                                <li>Somos distribuidores oficiales de Abafil en España</li>

                            </div>

                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </section>
    <section class="carousel-fake">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="item_lot lot">
                        <div class="discount d-10">
                            <div>10%</div>
                            <div>DESCUENTO</div>
                        </div>
                        <div class="lot-wrapper">
                            <a title="5  -  Accesorios" href="/es/lote/02012019-accesorios-1242/5-506-accesorios"></a>
                            <span class="more-picture" href="#" data-toggle="tooltip" title="Más fotos">
                                <span>Más fotos</span>
                                <img src="/themes/tauleryfau/assets/img/more-picture.png">
                            </span>
                            <div class="lot-title">
                                <a title="5  -  Accesorios" href="/es/lote/02012019-accesorios-1242/5-506-accesorios">
                                    <span>Lote 5</span>
                                </a>
                            </div>
                            <div class="lot-img">
                                <a title="5  -  Accesorios" href="/es/lote/02012019-accesorios-1242/5-506-accesorios">
                                    <img class="img-responsive lazy" alt="5  -  Accesorios" src="/img/load/lote_medium/001-506-5.jpg"
                                        style="display: block;">
                                </a>
                            </div>
                            <div class="data-desc">
                                <p><span class="subperiod_1">Abafil - MiniFloc 6</span>. <span class="technical_description">Práctico
                                        portamonedas en formato "Minor", en fieltro de plástico rojo que tiene 6
                                        huecos. Medidas: 17,5x12x3 cm</span>. Est...<span class="IMPTAS_HCES1">20,00</span>.
                                </p>
                            </div>
                            <div class="data-content">
                                <div class="price-content">
                                    <p class="puja">Precio venta</p>
                                    <p class="price tachado gold">20 €</p>
                                </div>
                                <div class="price-content">
                                    <p class="puja">Nuestro Precio</p>
                                    <p class="price gold">18 €</p>
                                </div>

                                <div class="btn-pujar">
                                    <a class="btn btn-custom" href="/es/lote/02012019-accesorios-1242/5-506-accesorios">Comprar
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                </div>
                                <div class="timeLeft"></div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                    <div class="item_lot lot">

                        <div class="discount d-10">
                            <div>10%</div>
                            <div>DESCUENTO</div>
                        </div>
                        <div class="lot-wrapper">
                            <a title="2  -  Accesorios" href="/es/lote/02012019-accesorios-1242/2-506-accesorios"></a>
                            <span class="more-picture" href="#" data-toggle="tooltip" title="Más fotos">
                                <span>Más fotos</span>
                                <img src="/themes/tauleryfau/assets/img/more-picture.png">
                            </span>
                            <div class="lot-title">
                                <a title="2  -  Accesorios" href="/es/lote/02012019-accesorios-1242/2-506-accesorios">
                                    <span>Lote 2</span>
                                </a>
                            </div>
                            <div class="lot-img">
                                <a title="2  -  Accesorios" href="/es/lote/02012019-accesorios-1242/2-506-accesorios">
                                    <img class="img-responsive lazy" alt="2  -  Accesorios" src="/img/load/lote_medium/001-506-3.jpg"
                                        style="display: block;">
                                </a>
                            </div>
                            <div class="data-desc">
                                <p><span class="subperiod_1">Abafil - MiniBring 4x3</span>. <span class="technical_description">Práctico
                                        y elegante estuche fabricado con materiales de alta calidad que contiene 2
                                        bandejas de 4x3 huecos. El tamaño de los estuches mignon los convierte en la
                                        solución ideal tanto para el transporte como para el almacenamiento de su
                                        colección. Medidas: 25x19,5x4 cm</span>. Est...<span class="IMPTAS_HCES1">110,00</span>.
                                </p>
                            </div>
                            <div class="data-content">
                                <div class="price-content">
                                    <p class="puja">Precio venta</p>
                                    <p class="price tachado gold">110 €</p>
                                </div>
                                <div class="price-content">
                                    <p class="puja">Nuestro Precio</p>
                                    <p class="price gold">99 €</p>
                                </div>

                                <div class="btn-pujar">
                                    <a class="btn btn-custom" href="/es/lote/02012019-accesorios-1242/2-506-accesorios">Comprar
                                        <i class="fa fa-shopping-cart"></i></a>

                                </div>
                                <div class="timeLeft"></div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                    <div class="item_lot lot">
                        <div class="lot-wrapper">
                            <a title="10  -  Accesorios" href="/es/lote/02012019-accesorios-1242/10-506-accesorios"></a>
                            <div class="lot-title">
                                <a title="10  -  Accesorios" href="/es/lote/02012019-accesorios-1242/10-506-accesorios">
                                    <span>Lote 10</span>
                                </a>
                            </div>
                            <div class="lot-img">
                                <a title="10  -  Accesorios" href="/es/lote/02012019-accesorios-1242/10-506-accesorios">
                                    <img class="img-responsive lazy" alt="10  -  Accesorios" src="/img/load/lote_medium/001-506-12.jpg"
                                        style="display: block;">
                                </a>
                            </div>
                            <div class="data-desc">
                                <p><span class="subperiod_1">Lindner - Nimbus 100</span>. <span class="technical_description">Marco
                                        Expositor 100x100x25 mm. Así es como funciona: El marco se puede abrir
                                        completamente. el objeto coleccionable se coloca en una de las dos membranas de
                                        silicona y el marco se puede cerrar. El objeto se mantendrá en su lugar por las
                                        membranas de silicona circundantes, manteniéndolo en su posición. Un cierre
                                        mágico mantiene el marco unido</span>. Est...<span class="IMPTAS_HCES1">20,00</span>.
                                </p>
                            </div>
                            <div class="data-content">
                                <div class="price-content">
                                    <p class="puja">Precio estimado</p>
                                    <p class="price  gold">20 €</p>
                                </div>
                                <div class="price-content">
                                    <p class="puja">Precio venta</p>
                                    <p class="price gold">20 €</p>
                                </div>

                                <div class="btn-pujar">
                                    <a class="btn btn-custom" href="/es/lote/02012019-accesorios-1242/10-506-accesorios">Comprar
                                        <i class="fa fa-shopping-cart"></i></a>

                                </div>
                                <div class="timeLeft"></div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                    <div class="item_lot lot">

                        <div class="discount d-10">
                            <div>13%</div>
                            <div>DESCUENTO</div>
                        </div>
                        <div class="lot-wrapper">
                            <a title="8  -  Accesorios" href="/es/lote/02012019-accesorios-1242/8-506-accesorios"></a>
                            <div class="lot-title">
                                <a title="8  -  Accesorios" href="/es/lote/02012019-accesorios-1242/8-506-accesorios">
                                    <span>Lote 8</span>
                                </a>
                            </div>
                            <div class="lot-img">
                                <a title="8  -  Accesorios" href="/es/lote/02012019-accesorios-1242/8-506-accesorios">
                                    <img class="img-responsive lazy" alt="8  -  Accesorios" src="/img/load/lote_medium/001-506-9.jpg"
                                        style="display: block;">
                                </a>
                            </div>
                            <div class="data-desc">
                                <p><span class="subperiod_1">Abafil - Fundas BD 40</span>. <span class="technical_description">Pack
                                        de 100 fundas de plástico muy resistentes especialmente fabricadas para la
                                        protección de la moneda. Para piezas de hasta 40 mm de diámetro</span>. Est...<span
                                        class="IMPTAS_HCES1">16,00</span>. </p>
                            </div>
                            <div class="data-content">
                                <div class="price-content">
                                    <p class="puja">Precio venta</p>
                                    <p class="price tachado gold">16 €</p>
                                </div>
                                <div class="price-content">
                                    <p class="puja">Nuestro Precio</p>
                                    <p class="price gold">14 €</p>
                                </div>

                                <div class="btn-pujar">
                                    <a class="btn btn-custom" href="/es/lote/02012019-accesorios-1242/8-506-accesorios">Comprar
                                        <i class="fa fa-shopping-cart"></i></a>

                                </div>
                                <div class="timeLeft"></div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="landing-slogan">
        <div class="container">
            <div class="row">
                {{-- <div class="col-xs-12">
                    <p>"Combinamos la experiencia de los profesionales con la pasión de los coleccionistas"</p>
                </div> --}}
                <div class="col-xs-12 aditional-text" style="    margin: 60px 0;padding: 0 20px;text-align: justify;">
                    <p style="margin-bottom: 20px;">Todo coleccionista de monedas quiere preservar su colección de la manera más segura posible y que además le permita disfrutar de ella a diario. En Tauler&Fau disponemos de todos los accesorios necesarios para el coleccionista de monedas antiguas.
                    </p>

                    <p style="margin-bottom: 20px;">Contamos con multitud opciones para su colección numismática:

                        </p>
                    <ul style="    list-style: unset; padding-left: 70px">
                        <li>Maletines y estuches fabricados con materiales de altísima calidad con diferente número de bandejas que protegerá su colección y le permitirá transportarla con total seguridad.</li>
                        <li>Portamonedas perfectos para poder transportar una pequeña parte de su colección.</li>
                        <li>Fundas resistentes y especialmente fabricadas para la protección de la moneda.
                        </li>
                        <li>Expositores, lupas y mucho más.
                        </li>

                    </ul>
                </div>
            </div>
            <div class="col-xs-12">
                <p class="second-slogan">Visite la sección de Accesorios en la tienda online de Tauler&Fau y elija entre todos nuestros modelos. Aproveche ya nuestras ofertas exclusivas.

                    </p>

            </div>
        </div>
    </div>


    <style>
    .list-content {
        display: flex;
        justify-content: center;
        font-size: 17px;
    }

    .second-slogan {
        color: #283847;
        font-size: 16px;
        font-weight: 100;
    }

    .aditional-text {
        font-size: 14px;
        font-weight: 100
    }

    header {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .landing-body .row {
        margin-right: 0 !important;
        margin-left: 0 !important;
    }

    .lots {
        margin-bottom: 35px;
    }

    .landing-slogan {
        padding: 40px 0;
        color: #283847;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
    }

    .destacado {
        position: absolute;
        /* background: #f1ece6; */
        border-radius: 50%;
        opacity: 1;
        left: 25px;
        top: 0px;
        /* border: 4px solid #a37a4c; */
        z-index: 2;
        /* box-shadow: 0px 0px 6px rgba(0,0,0,.5); */
    }

    .carousel-fake-cat,
    .carousel-fake-desc {
        color: #5C5C5C !important;
        font-style: italic !important;
        font-size: 14px;
        padding: 0 15px;
    }

    .carousel-fake-desc {
        color: #5C5C5C !important;
        font-style: italic !important;
        font-size: 14px;
        display: block;
        display: -webkit-box;
        height: 32px;
        font-size: 14px;
        line-height: 1.2;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
        padding: 0 15px;
        font-style: italic;
    }

    .lot-img {
        position: relative;
        padding: 0 5px;
        text-align: center;
        cursor: pointer;
        text-align: center;
        height: 145px;
        transition: .2s all linear;
        cursor: pointer;
        padding: 0 5px;
    }

    .lot-img img {
        margin: 0 auto;
        max-height: 145px;
    }

    .color-letter {
        color: #283847;
    }

    .carouse-fake-title {
        font-size: 20px;
        color: #283847;
        text-align: center;
    }

    .carousel-fake {
        padding: 100px 0;
        padding-bottom: 0px;
    }

    .landing-body-list-content {
        background-color: #283847;
        color: white;
        height: 290px;
        font-size: 20px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;



    }

    .ul-list-landing {
        padding-left: 30px;
    }

    .landing-body-list-content li i {
        margin-right: 5px;



    }

    .landing-body-list-content li {
        margin-bottom: 10px;



    }

    .landing-body-header {
        height: 60px;
        font-size: 20px;
        font-weight: 900;
        color: #283847;
        text-align: center;
        padding-top: 17px;
        background: white;
    }

    .landing-body-header h2 {
        font-size: 24px;
        font-weight: 900;
        color: #283847;
        margin: 0;
    }

    body.landing header {
        min-height: 80px;
    }

    body.landing .slogan-principal {
        font-weight: 900;
        font-size: 25px;
    }

    body.landing .super-slogan {
        background-color: #283847;
    }

    body.landing .super-slogan .slogan-principal {
        font-weight: 900;
        font-size: 32px;
        margin: 15px 0;
        color: white;
    }

    .slogan-principal h1 {
        font-weight: 600;
        margin-top: 10px;
        font-size: 32px;
    }

    body.landing .header-desktop {
        margin: 0;
    }

    .ventajas-landing .ventajas-title {
        height: 60px;
        font-size: 20px;
        font-weight: 900;
        color: #283847;
        text-align: center;
        padding-top: 17px;
        background: white;
    }

    .ventajas-landing .ventajas-title h2 {
        font-size: 24px;
        font-weight: 900;
        color: #283847;
        margin: 0;
    }

    .href-cell {
        color: white;
        text-decoration: underline;

    }

    .box-align {
        width: 315px;
        margin: 0 auto;
    }

    body.landing header .logo a img {
        width: 260px;
    }

    .ventajas-landing {
        min-height: 375px;
        background-color: #283847;
        position: relative;
    }

    .banner-landing {
        padding: 18px 20px;
        background: #a88c69;
        width: 35%;
        position: absolute;
        bottom: -45px;
        color: white;
        padding-right: 30px;
        padding-top: 40px;
        padding-bottom: 40px;
        z-index: 10;
    }

    .banner-landing-title,
    .banner-landing-cell {
        font-size: 30px;
        font-weight: 900;
    }

    @media screen and (max-width: 1200px) {
        .destacado {

            left: 0;

        }

        .banner-landing-title,
        .banner-landing-cell {
            font-size: 25px;
            font-weight: 900;
        }

        .banner-landing {
            padding: 18px 20px;
            background: #a88c69;
            width: 35%;
            position: absolute;
            bottom: -45px;
            color: white;
            padding-right: 30px;
            padding-top: 20px;
            padding-bottom: 20px;
            z-index: 10;
        }
    }

    @media screen and (max-width: 1200px) {}

    @media screen and (max-width: 991px) {
        .landing-body-list-content {

            text-align: center;
            font-size: 20px;

        }

        .banner-landing {
            padding: 18px 20px;
            width: 598px;
            padding-top: 40px;
            padding-bottom: 40px;
            z-index: 10;
        }
    }

    @media screen and (max-width: 768px) {
        .banner-landing {

            width: 400px;

        }

        .landing-body-list-content {

            height: 267px;

        }

        .landing-body-list-content {
            font-size: 20px;
            text-align: center;
        }
    }

    @media screen and (max-width: 576px) {
        .slogan-principal h1 {
            font-weight: 600;
            margin-top: 10px;
            font-size: 20px;
        }

        .landing-body-header h2 {
            font-size: 18px;
            font-weight: 900;
            color: #283847;
            margin: 0;
        }

        .landing-body-list-content {
            font-size: 16px;
        }

        .banner-landing {

            width: 235px;
        }

        .banner-landing-title,
        .banner-landing-cell {
            font-size: 15px;
        }

        .landing-slogan {
            padding: 0px 0;

        }

        .destacado {
            left: 35px;
        }

        .landing-body .row {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        .landing-body-list-content {

            height: 200px;

        }

        .banner-landing {
            position: fixed;
            bottom: 43px;
            left: 0;
        }

        .carousel-fake {
            padding: 30px 0;
        }
    }
    </style>

</body>
@stop