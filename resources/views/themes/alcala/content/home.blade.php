{!! \BannerLib::bannersPorKey('HOME_SUPERIOR', 'banner_home') !!}
<div class="text-center mt-2 mb-2">
    <a href="{{ trans($theme . '-app.home.see_catalog_url') }}" class="button-principal"
        target="_blank">{{ trans($theme . '-app.home.see_catalog') }}</a>
</div>

<div class="container-fluid inner">
    <br>
    <div class="row pd-top-40">
        <div class="col-xs-12 col-lg-3 col-md-4 col-sm-4">
            <div id="home-proxima-subasta">

                {!! \BannerLib::bannersPorKey('HOME_FOTO_TEXTO', 'banner_home_left') !!}
                <br><br>

                <?php
                /*                * ***OBTENER PROXIMA SUBASTA W****
                                $subastas = new App\Http\Controllers\SubastaController;
                                $subasta_banner = $subastas->listaSubastasSesiones('S', 'W', false);

                                if (!empty($subasta_banner['auction_list'])) {
                                    $subasta_banner = $subasta_banner['auction_list'];
                                    $url_lotes = \Tools::url_auction($subasta_banner[0]->cod_sub, $subasta_banner[0]->name, $subasta_banner[0]->id_auc_sessions);
                                }
                                ?>
                ?>
                @if (!empty($subasta_banner[0]))
                    <div id="home-proxima-subasta-titulo">
                        <p>
                            <a class="color-letter" href="{{ $url_lotes }}"
                                title="{{ $subasta_banner[0]->des_sub }}">{{ $subasta_banner[0]->des_sub }}</a>
                        </p>
                    </div>
                    <div id="home-proxima-subasta-imagen">
                        <center>
                            <a class="enlace" href="{{ $url_lotes }}" title="{{ $subasta_banner[0]->des_sub }}">
                                <img class="img-responsive"
                                    src="{{ \Tools::url_img_session('subasta_medium', $subasta_banner[0]->cod_sub, $subasta_banner[0]->reference) }}"
                                    alt="{{ $subasta_banner[0]->name }}">
                            </a>
                        </center>
                    </div>
                @endif
                <?php */?>
            </div>

        </div>

        <!--    TOUR    -->

        @if (Config::get('app.video_home', 0))
            <div class="col-xs-12 col-md-12 col-lg-9">

                <div class="row tour-container d-flex align-items-center flex-wrap justify-content-center">

                    <div class="text-center tour-video">
                        <div class="tour-video-wrapper">
                            <iframe src='{{ trans(\Config::get('app.theme') . '-app.home.video_matteport_9') }}'
                                frameborder='0' allowfullscreen allow='xr-spatial-tracking'></iframe>
                            {{-- <iframe src="https://www.youtube.com/embed/M9IHbzEukIE" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
                        </div>
                    </div>
                    <div class="text-center tour-video">
                        <div class="tour-video-wrapper">
                            <iframe src='{{ trans(\Config::get('app.theme') . '-app.home.video_matteport_10') }}'
                                frameborder='0' allowfullscreen allow='xr-spatial-tracking'></iframe>
                            {{-- <iframe src="https://www.youtube.com/embed/M9IHbzEukIE" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
                        </div>
                    </div>

                    <div class="tour-access d-flex flex-column align-items-center">
                        <h2>{{ trans(\Config::get('app.theme') . '-app.home.tour_title') }}</h2>
                        <div>
                            <a href="{{ trans(\Config::get('app.theme') . '-app.home.video_matteport_9') }}"
                                class="button-principal" target="_blank">Núñez de Balboa 9</a>
                            <a href="{{ trans(\Config::get('app.theme') . '-app.home.video_matteport_10') }}"
                                class="button-principal" target="_blank">Núñez de Balboa 10</a>
                        </div>
                    </div>

                </div>

            </div>
        @endif

        <!--    CARROUSEL    -->

        <?php

        $res = DB::table('WEB_BLOCK')
            ->where('KEY_NAME', 'lotes_destacados')
            ->where('ENABLED', 1)
            ->where('ID_EMP', \Config::get('app.emp'))
            ->first();
        $res->products = str_replace('[lang]', "'" . Config::get('app.lang') . "'", $res->products);
        $lotes_aux = DB::select($res->products);

        if ($lotes_aux && count($lotes_aux) > 1) {
            [$lotes1, $lotes2] = array_chunk($lotes_aux, ceil(count($lotes_aux) / 2));
        } else {
            $lotes1 = [];
            $lotes2 = [];
        }

        ?>

        <div class="col-xs-12 col-md-8">

            <!--   CONTROLES CARROUSEL    -->
            <div class="heading" style="position: relative">
                <div class="line"></div>
                <h3 class="background extra-font-family">
                    {{ trans(\Config::get('app.theme') . '-app.lot_list.lotes_destacados') }}</h3>
                <br><br>
            </div>

            <div id="lotes_destacados" class="JS_lotes_destacados_home">
                @foreach ($lotes1 as $bann)
                    <div class="item">
                        @include('includes.carousel')

                    </div>
                @endforeach
            </div>

            <br><br>

            <div id="lotes_destacados2" class="JS_lotes_destacados_home">
                @foreach ($lotes2 as $bann)
                    <div class="item">
                        @include('includes.carousel')
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            $(".JS_lotes_destacados_home").slick({

                slidesToShow: 4,
                slidesToScroll: 1,
                infinite: true,
                autoplay: true,
                dots: true,
                responsive: [{
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 3,

                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2,

                        }
                    },
                    {
                        breakpoint: 900,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        }
                    },

                ]
            });
        </script>

    </div>
    <br>
    <br>
    <div class="shady bott-27"></div>
</div>
<br>

<!-------------------------------------------------------------------------------
-------------------------   NEWSLETTER AND NEWS ---------------------------------
--------------------------------------------------------------------------------->

<div class="container container-custom inner">
    <div class="row pd-top-40 pd-bottom-40">

        <div class="col-md-3 col-xs-12">
            <div class="heading" style="position: relative">
                <div class="line"></div>
                <h3 class="background extra-font-family">
                    {{ trans(\Config::get('app.theme') . '-app.foot.newsletter_title') }}</h3>
            </div>
            <p class="grey-color" style="font-size: 12px">
                {{ trans(\Config::get('app.theme') . '-app.login_register.recibir_newsletter') }}
            </p>
            @include('includes.newsletter')
        </div>
        <div class="noticias-news col-md-9">
            <div class="col-xs-12 col-md-6 p-0">
                {!! BannerLib::bannersPorKey('BANNER_NEWS_1', '') !!}
            </div>
            <div class="col-xs-12 col-md-6 p-0">
                {!! BannerLib::bannersPorKey('BANNER_NEWS_2', '') !!}
            </div>
            <div class="col-xs-12 col-md-6 p-0">
                {!! BannerLib::bannersPorKey('BANNER_NEWS_3', '') !!}
            </div>
            <div class="col-xs-12 col-md-6 p-0">
                {!! BannerLib::bannersPorKey('BANNER_NEWS_4', '') !!}
            </div>

        </div>
    </div>
</div>

<div class="clearfix"></div>
<br><br>
