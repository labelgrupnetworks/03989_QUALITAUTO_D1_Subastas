@extends('layouts.tasacion')
@include('includes.google_head')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
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
                                <img class="img-responsive" src="/themes/<?= $theme ?>/assets/img/flag_en.png" />
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
                                <img class="img-responsive" src="/themes/<?= $theme ?>/img/comprar-monedas/logo.png"
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
                        <h1>Tienda online - Libros Numismática</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="landing-body">
        <div class="" style='position: relative'>
            <div class="banner-landing">
                <a href="subasta/03012019-libros-1261" style='color:white'>
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
                        <h2>Biblioteca Numismática</h2>
                    </div>

                    <div class="landing-body-list-content ">
                        <ul class="col-xs-12 col-md-12 list-content">
                            <div>
                                <li><img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px"> Los mejores libros de numismática</li>
                                <li><img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px"> Catálogo de monedas y billetes</li>
                                <li><img src="data:image/png;base64,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"
                                        width='25' style="margin-left: 10px"> Ofertas exclusivas</li>
                            </div>
                        </ul>
                    </div>



                </div>
                <div class="col-md-6 col-xs-12 no-padding">
                    <div class="landing-body-header">
                        <h2>Los mejores catálogos especializados</h2>
                    </div>

                    <div class="landing-body-list-content ">
                        <ul class="col-xs-12 col-md-8 col-md-offset-2 ul-list-landing list-content">
                            <div>
                                <li>
                                    <p><strong>El Oro el Medievo Español.</strong> Por Rafael Tauler Fesser</p>
                                </li>
                                <li>
                                    <p><strong>Los Maravedís de los Borbones. Tipos y variantes.</strong> Por Juan Luis
                                        López de la Fuente.</p>
                                </li>
                                <li>
                                    <p><strong>Oro Macuquino. Por Rafael Tauler Fesser<strong></p>
                                </li>
                                <li>
                                    <p><strong>Ancient Coinage of the Iberian Peninsula (ACIP).<strong> Por Leandre
                                                Villaronga y Jaume Benages<p>
                                </li>
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
                        <div class="lot-wrapper">
                            <a title="1001  -  Libros" href="/es/lote/03012019-libros-1261/1001-347-libros"></a>
                            <div class="destacado" role="button">
                                <img class="img-responsive" src="/themes/tauleryfau/assets/img/destacado.png" alt="Tauler y Fau"
                                    style="width:85px; margin-bottom: 20px; position:relative">
                            </div>
                            <div class="lot-title">
                                <a title="1001  -  Libros" href="/es/lote/03012019-libros-1261/1001-347-libros">
                                    <span>Lote 1001</span>
                                </a>
                            </div>
                            <div class="lot-img">
                                <a title="1001  -  Libros" href="/es/lote/03012019-libros-1261/1001-347-libros">
                                    <img class="img-responsive lazy" alt="1001  -  Libros" src="/img/load/lote_medium/001-347-161.jpg"
                                        style="display: block;">
                                </a>
                            </div>
                            <div class="data-desc">
                                <p><span class="period">Libros</span><br><span class="technical_description">El Oro en
                                        el Medievo Español. Época cristiana (1018-1516). Catálogo con todas las monedas
                                        áureas dividido en tres partes: Libro I. Castilla-León; Libro II. Reinos de
                                        Aragón, Navarra, Mallorca, Valencia, Nápoles, Sicilia y Condados catalanes;
                                        Libro III. Fernando el Católico, tránsito a la Edad Moderna. Con 260 páginas a
                                        todo color y más de 800 monedas reseñadas, catalogadas y fotografiadas. Precios
                                        reales obtenidos de las subastas numismáticas del mundo entero. Por Rafael
                                        Tauler Fesser. Madrid, 2019</span>. Est. <span class="IMPTAS_HCES1">80,00</span>.
                                    <br><span class="IMPSAL_HCES1">80,00</span></p>
                            </div>
                            <div class="data-content">
                                <div class="price-content">
                                    <p class="puja">Precio estimado</p>
                                    <p class="price  gold">80 €</p>
                                </div>
                                <div class="price-content">
                                    <p class="puja">Precio venta</p>
                                    <p class="price gold">80 €</p>
                                </div>

                                <div class="btn-pujar">
                                    <a class="btn btn-custom" href="/es/lote/03012019-libros-1261/1001-347-libros">Comprar
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
                            <a title="1037  -  Libros" href="/es/lote/03012019-libros-1261/1037-347-libros"></a>
                            <div class="destacado" role="button">
                                <img class="img-responsive" src="/themes/tauleryfau/assets/img/destacado.png" alt="Tauler y Fau"
                                    style="width:85px; margin-bottom: 20px; position:relative">
                            </div>
                            <div class="lot-title">
                                <a title="1037  -  Libros" href="/es/lote/03012019-libros-1261/1037-347-libros">
                                    <span>Lote 1037</span>
                                </a>
                            </div>
                            <div class="lot-img">
                                <a title="1037  -  Libros" href="/es/lote/03012019-libros-1261/1037-347-libros">
                                    <img class="img-responsive lazy" alt="1037  -  Libros" src="/img/load/lote_medium/001-347-163.jpg"
                                        style="display: block;">
                                </a>
                            </div>
                            <div class="data-desc">
                                <p><span class="period">Libros</span><br><span class="technical_description">Los
                                        Maravedís de los Borbones (1701-1858), tipos y variantes, con 1253 monedas
                                        referenciadas. Consta de 550 páginas con fotografías en blanco y negro. Por
                                        Juan Luis López de la Fuente. Torredonjimeno, 2019<br>&nbsp;</span>. Est. <span
                                        class="IMPTAS_HCES1">35,00</span>. <br><span class="IMPSAL_HCES1">35,00</span></p>
                            </div>
                            <div class="data-content">
                                <div class="price-content">
                                    <p class="puja">Precio estimado</p>
                                    <p class="price  gold">35 €</p>
                                </div>
                                <div class="price-content">
                                    <p class="puja">Precio venta</p>
                                    <p class="price gold">35 €</p>
                                </div>

                                <div class="btn-pujar">
                                    <a class="btn btn-custom" href="/es/lote/03012019-libros-1261/1037-347-libros">Comprar
                                        <i class="fa fa-shopping-cart"></i></a>

                                </div>
                                <div class="timeLeft"></div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                    <div class="item_lot lot">

                        <div class="discount d-50">
                            <div>20%</div>
                            <div>DESCUENTO</div>
                        </div>
                        <div class="lot-wrapper">
                            <a title="1022  -  Libros" href="/es/lote/03012019-libros-1261/1022-347-libros"></a>
                            <div class="lot-title">
                                <a title="1022  -  Libros" href="/es/lote/03012019-libros-1261/1022-347-libros">
                                    <span>Lote 1022</span>
                                </a>
                            </div>
                            <div class="lot-img">
                                <a title="1022  -  Libros" href="/es/lote/03012019-libros-1261/1022-347-libros">
                                    <img class="img-responsive lazy" alt="1022  -  Libros" src="/img/load/lote_medium/001-347-35.jpg"
                                        style="display: block;">
                                </a>
                            </div>
                            <div class="data-desc">
                                <p><span class="period">Libros</span><br><span class="technical_description">Oro
                                        Macuquino. Catálogo con todas las monedas áureas españolas de 1, 2, 4 y 8
                                        escudos macuquinos (1474-1756). Con 574 páginas a todo color y más de 2.000
                                        monedas reseñadas, catalogadas y fotografiadas. Precios reales obtenidos de las
                                        subastas numismáticas del mundo entero. Por Rafael Tauler Fesser. Madrid, 2011</span>.
                                    Est. <span class="IMPTAS_HCES1">100,00</span>. <br><span class="IMPSAL_HCES1">80,00</span></p>
                            </div>
                            <div class="data-content">
                                <div class="price-content">
                                    <p class="puja">Precio venta</p>
                                    <p class="price tachado gold">100 €</p>
                                </div>
                                <div class="price-content">
                                    <p class="puja">Nuestro Precio</p>
                                    <p class="price gold">80 €</p>
                                </div>

                                <div class="btn-pujar">
                                    <a class="btn btn-custom" href="/es/lote/03012019-libros-1261/1022-347-libros">Comprar
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
                            <a title="1027  -  Libros" href="/es/lote/03012019-libros-1261/1027-347-libros"></a>
                            <div class="destacado" role="button">
                                <img class="img-responsive" src="/themes/tauleryfau/assets/img/destacado.png" alt="Tauler y Fau"
                                    style="width:85px; margin-bottom: 20px; position:relative">
                            </div>
                            <div class="lot-title">
                                <a title="1027  -  Libros" href="/es/lote/03012019-libros-1261/1027-347-libros">
                                    <span>Lote 1027</span>
                                </a>
                            </div>
                            <div class="lot-img">
                                <a title="1027  -  Libros" href="/es/lote/03012019-libros-1261/1027-347-libros">
                                    <img class="img-responsive lazy" alt="1027  -  Libros" src="/img/load/lote_medium/001-347-157.jpg"
                                        style="display: block;">
                                </a>
                            </div>
                            <div class="data-desc">
                                <p><span class="period">Libros</span><br><span class="technical_description">ANCIENT
                                        COINAGE OF THE IBERIAN PENINSULA: Greek / Punic / Iberian / Roman. Por Leandre
                                        Villaronga y Jaume Benages. Barcelona, 2011. Con 802 páginas y más de 4.300
                                        monedas referenciadas y fotografiadas en blanco y negro. Este trabajo comienza
                                        con las emisiones griegas del siglo V a.C. y, después de enumerar las
                                        acuñaciones púnicas, ibéricas y romanas, concluye con las problemáticas
                                        particulares de Barcino. Las extensas obras de referencia de Leandre Villaronga
                                        son la base de este libro y están muy bien complementadas por el conocimiento
                                        del experto en acuñación de Tarragona, Jaume Benages. Los archivos de
                                        Villaronga, que contienen más de 60.000 registros reunidos a lo largo de toda
                                        su vida, han sido el punto de partida de este libro</span>. Est. <span class="IMPTAS_HCES1">100,00</span>.
                                    <br><span class="IMPSAL_HCES1">100,00</span></p>
                            </div>
                            <div class="data-content">
                                <div class="price-content">
                                    <p class="puja">Precio estimado</p>
                                    <p class="price  gold">100 €</p>
                                </div>
                                <div class="price-content">
                                    <p class="puja">Precio venta</p>
                                    <p class="price gold">100 €</p>
                                </div>

                                <div class="btn-pujar">
                                    <a class="btn btn-custom" href="/es/lote/03012019-libros-1261/1027-347-libros">Comprar
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
                <div class="col-xs-12">
                    {{-- <p>"Combinamos la experiencia de los profesionales con la pasión de los coleccionistas"</p> --}}
                </div>
                <div class="col-xs-12 aditional-text" style="    margin: 60px 0;padding: 0 20px;text-align: justify;">
                    <p style="margin-bottom: 20px;">La afición a la numismática está estrechamente ligada al estudio y lectura de documentación sobre libros y monedas. La numismática abarca muchos campos, como la historia, el mercado, el análisis, la legislación…. Y es de vital importancia conocerlos todos.
                    </p>
                    <p style="margin-bottom: 20px;">
                            El coleccionista numismático necesita consultar catálogos y libros de monedas. Estos libros son muy variados y abarcan todas las épocas de la historia:

                        </p>


                    <ul style="    list-style: unset; padding-left: 70px">
                        <li>
                                Catálogos que se centran en un tipo de moneda, como por ejemplo, “Los Maravedís de los Borbones”, “Oro Macuquino”,
                        </li>
                        <li>“Los reales de los Reyes Católicos” y muchos más.
                            </li>
                        <li>Catálogos que se centran en un periodo específico, como por ejemplo, “El Oro en el Medievo Español”, “Ancient Coinage of the Iberian Peninsula”,  “ Enciclopedia de la moneda medieval románica en los reinos de León y Castilla” y muchos más.
                        </li>
                        <li>Catálogos World Coins, que engloban la numismática de todos los países.

                        </li>
                        <li>Expositores, lupas y mucho más.

                        </li>
                    </ul>


                </div>
            </div>
            <div class="col-xs-12">
                <p class="second-slogan">Visite la sección de Libros en la tienda online de Tauler&Fau y elija entre todos nuestros catálogos especializados. Aproveche ya nuestras ofertas exclusivas.

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
        left: 0px;
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
        padding-bottom: 0;
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
        font-size: 17px;
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
