@php
    $resourcesList = [];
    foreach ($lote_actual->videos ?? [] as $key => $video) {
        $resource = ['src' => $video, 'format' => 'VIDEO'];
        if (strtolower(substr($video, -4)) == '.gif') {
            $resource['format'] = 'GIF';
        }
        $resourcesList[] = $resource;
    }
@endphp
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <h1 class="titlePage">
                @php
                    $lote_actual->descweb_hces1 = str_replace('<p>', ' ', $lote_actual->descweb_hces1);
                    $lote_actual->descweb_hces1 = str_replace('</p>', ' ', $lote_actual->descweb_hces1);
                @endphp
                {{ $lote_actual->ref_asigl0 }} - {!! $lote_actual->descweb_hces1 !!}

            </h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="single">


            <div class="col-xs-12 col-md-7" style="position: relative">

                <!-- Inicio Galeria Responsive -->
                <div class="visible-xs">
                    <div class="owl-theme owl-carousel" id="owl-carousel-responsive">

                        @foreach ($lote_actual->imagenes as $key => $imagen)
                            <div
                                class="item_content_img_single"style="position: relative; height: 290px; overflow: hidden;">
                                <img class="img-responsive" data-pos="{{ $key }}"
                                    src="{{ Tools::url_img('lote_medium_large', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}"
                                    alt="{{ $lote_actual->titulo_hces1 }}"
                                    style="max-width: 100%; height: auto; position: relative; display: inherit !important;    margin: 0 auto !important;">
                            </div>
                        @endforeach
                        @foreach ($resourcesList as $resource)
                            <div class="item_content_img_single" style="position: relative;">
                                @if ($resource['format'] == 'GIF')
                                    <img class="img-responsive" src="{{ $resource['src'] }}"
                                        alt="{{ $lote_actual->titulo_hces1 }}"
                                        style="max-width: 100%; height: auto; position: relative; display: inherit !important;    margin: 0 auto !important;">
                                @elseif($resource['format'] == 'VIDEO')
                                    <video width="100%" controls>
                                        <source src="{{ $resource['src'] }}" type="video/mp4">
                                    </video>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if (Session::has('user') && $lote_actual->retirado_asigl0 == 'N')
                        <a class="btn <?= $data['subasta_info']->lote_actual->favorito ? 'hidden' : '' ?>"
                            id="add_fav" href="javascript:action_fav_modal('add')">
                            <i class="fa fa-star-o" aria-hidden="true"></i>
                        </a>
                        <a class="btn <?= $data['subasta_info']->lote_actual->favorito ? '' : 'hidden' ?>"
                            id="del_fav" href="javascript:action_fav_modal('remove')">
                            <i class="fa fa-star" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>

                <!-- Inicio Galeria Desktop -->
                <div class="hidden-xs d-flex" style="gap: 1rem">

                    <div class="" style="flex: 1;">
                        <div class="img_single_border">

                            <div class="button-follow" style="display:none;">
                                <div class="spinner">
                                    <div class="double-bounce1"></div>
                                    <div class="double-bounce2"></div>
                                </div>

                            </div>
                            @if (Session::has('user') && $lote_actual->retirado_asigl0 == 'N')
                                <a class="btn <?= $data['subasta_info']->lote_actual->favorito ? 'hidden' : '' ?>"
                                    id="add_fav" href="javascript:action_fav_modal('add')">
                                    <p>{{ trans(\Config::get('app.theme') . '-app.lot.add_to_fav') }} </p><i
                                        class="fa fa-star-o" aria-hidden="true"></i>
                                </a>
                                <a class="btn <?= $data['subasta_info']->lote_actual->favorito ? '' : 'hidden' ?>"
                                    id="del_fav" href="javascript:action_fav_modal('remove')">
                                    <p>{{ trans(\Config::get('app.theme') . '-app.lot.del_from_fav') }} </p><i
                                        class="fa fa-star" aria-hidden="true"></i>
                                </a>
                            @endif

                            @if ($lote_actual->retirado_asigl0 != 'N')
                                <div class="retired-border">
                                    <div class="retired">
                                        <span class="retired-text lang-{{ \Config::get('app.locale') }}">
                                            {{ trans(\Config::get('app.theme') . '-app.lot.retired') }}
                                        </span>
                                    </div>
                                </div>
                            @elseif(
                                $lote_actual->cerrado_asigl0 == 'S' &&
                                    (!empty($lote_actual->himp_csub) ||
                                        $lote_actual->desadju_asigl0 == 'S' ||
                                        ($lote_actual->subc_sub == 'H' && !empty($lote_actual->impadj_asigl0))))
                                <div class="retired-border">
                                    <div class="retired selled">
                                        <span class="retired-text lang-{{ \Config::get('app.locale') }}">
                                            {{ trans(\Config::get('app.theme') . '-app.subastas.buy') }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div id="toolbarDiv">
                                <div class="chevron-left-button" role="">
                                    <i class="fa fa-2x fa-chevron-left" id="chevron-left"></i>
                                </div>
                                <div class="chevron-right-button" role="">
                                    <i class="fa fa-2x fa-chevron-right" id="chevron-right"></i>
                                </div>
                            </div>

                            <div class="text-center" id="resource_main_wrapper" style="display:none"></div>
                            <div class="img-global-content position-relative">
                                <div class="img_single" id="img_main"></div>
                            </div>

                        </div>
                    </div>

                    {{-- Miniaturas imagenes --}}
                    <div class="slider-thumnail-container slider-thumnail-container-images" style="width: 75px">

                        <div class="row-up control" onClick="clickControl(this)">
                            <i class="fa fa-chevron-up" aria-hidden="true"></i>
                        </div>

                        <div class="miniImg slider-thumnail js-slider-thumnail-images">
                            @foreach ($lote_actual->imagenes as $key => $imagen)
                                <div class="col-sm-3-custom col-img">
                                    <button class="img-openDragon" data-pos="{{ $key }}"
                                        data-image="{{ $imagen }}" alt="{{ $lote_actual->titulo_hces1 }}"
                                        style="background-image:url('<?= \Tools::url_img('lote_small', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) ?>');"
                                        onclick="seed.goToPage(parseInt('{{ $key }}'))">
                                    </button>
                                </div>
                            @endforeach

                            {{-- Miniaturas recursos --}}
                            @foreach ($resourcesList as $key => $resource)
                                @php
                                    $backgroundImage =
                                        $resource['format'] == 'GIF'
                                            ? $resource['src']
                                            : asset('/img/icons/video_thumb.png');
                                @endphp
                                <div class="col-sm-3-custom col-video">
                                    <button class="img-openDragon" alt="{{ $lote_actual->titulo_hces1 }}"
                                        style="background-image:url('{{ $backgroundImage }}');"
                                        onclick="viewResourceFicha('{{ $resource['src'] }}', '{{ $resource['format'] }}')">
                                    </button>
                                </div>
                            @endforeach

                        </div>

                        <div class="row-down control" onClick="clickControl(this)">
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-xs-12 col-md-5">
                <div class="col-xs-12 col-sm-12">

                    @if ($lote_actual->retirado_asigl0 == 'N' && $lote_actual->fac_hces1 != 'D' && $lote_actual->fac_hces1 != 'R')
                        @if (
                            $lote_actual->cerrado_asigl0 == 'S' &&
                                $lote_actual->lic_hces1 != 'S' &&
                                $lote_actual->subc_sub == 'H' &&
                                (($lote_actual->cerrado_asigl0 == 'S' && empty($lote_actual->himp_csub) && $lote_actual->subc_sub == 'H') ||
                                    ($lote_actual->formatted_impsalhces_asigl0 == '0' &&
                                        $lote_actual->cerrado_asigl0 == 'N' &&
                                        $lote_actual->tipo_sub == 'W')))
                            @include('includes.ficha.consult_lot')
                        @elseif ($lote_actual->subc_sub != 'A' && $lote_actual->subc_sub != 'S')
                            @include('includes.ficha.pujas_ficha_cerrada')
                        @elseif(
                            $lote_actual->tipo_sub == 'V' &&
                                $lote_actual->cerrado_asigl0 != 'S' &&
                                strtotime($lote_actual->end_session) > date('now') &&
                                $lote_actual->desadju_asigl0 == 'N')
                            @include('includes.ficha.pujas_ficha_V')

                            <?php //si un lote cerrado no se ha vendido se podra comprar
                            ?>
                        @elseif(
                            ($lote_actual->tipo_sub == 'W' || $lote_actual->tipo_sub == 'O') &&
                                $lote_actual->cerrado_asigl0 == 'S' &&
                                empty($lote_actual->himp_csub) &&
                                $lote_actual->compra_asigl0 == 'S' &&
                                $lote_actual->fac_hces1 != 'D' &&
                                $lote_actual->desadju_asigl0 == 'N')
                            @include('includes.ficha.pujas_ficha_V')
                        @elseif($lote_actual->tipo_sub == 'W' && $lote_actual->cerrado_asigl0 != 'S')
                            @include('includes.ficha.pujas_ficha_W')
                        @elseif(($lote_actual->tipo_sub == 'O' || $lote_actual->tipo_sub == 'P') && $lote_actual->cerrado_asigl0 != 'S')
                            @include('includes.ficha.pujas_ficha_O')

                            <?php //puede que este cerrado 'S' o devuelto 'D'
                            ?>
                        @else
                            @include('includes.ficha.pujas_ficha_cerrada')
                        @endif
                    @else
                        @include('includes.ficha.pujas_ficha_cerrada')
                    @endif
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-lg-5 pull-right right_row">
                <div class="col-xs-12 col-sm-12">
                    @if (
                        (strtoupper($lote_actual->tipo_sub) == 'O' || strtoupper($lote_actual->tipo_sub) == 'P') &&
                            $lote_actual->cerrado_asigl0 != 'S' &&
                            $lote_actual->retirado_asigl0 == 'N')
                        @include('includes.ficha.history')
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-7">
                <div class="desc">
                    <div class="desc_tit">
                        {{ trans($theme . '-app.lot.description') }}
                    </div>
                    <div class="desc_content">
                        @if (\Config::get('app.descweb_hces1'))
                            <p><?= $lote_actual->descweb_hces1 ?></p>
                        @elseif (\Config::get('app.desc_hces1'))
                            <p><?= $lote_actual->desc_hces1 ?></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var seed;
    loadSeaDragon();

    $('.col-img').on('click', () => {
        $('#resource_main_wrapper').empty();
        $('#resource_main_wrapper').hide();
        $('#toolbarDiv').show();
        $('.img-global-content').show();
    })

    function loadSeaDragon(img, position) {

        var element = document.getElementById("img_main");
        seed = OpenSeadragon({
            id: "img_main",
            prefixUrl: "/img/opendragon/",
            showReferenceStrip: false,
            tileSources: [
                @foreach ($lote_actual->imagenes as $key => $imagen)
                    {
                        type: 'image',
                        url: '{{ Tools::url_img('real', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}'
                    },
                @endforeach
            ],
            tileCacheLimit: 10,
            showNavigator: false,
            sequenceMode: true,
            nextButton: "chevron-right",
            previousButton: "chevron-left",
            toolbar: "toolbarDiv",
        });
    }

    function clickControl(el) {
        $slider = $(el).parent().find('.slider-thumnail');
        var posScroll = $slider.scrollTop();
        if ($(el).hasClass('row-up')) {
            $slider.animate({
                scrollTop: posScroll - 76.40,
            }, 200);
        } else {

            $slider.animate({
                scrollTop: posScroll + 66,
            }, 200);
        }
    }

    function showOrHideControls() {
        const $imagesSlider = $('.js-slider-thumnail-images');
        const $imagesControl = $('.slider-thumnail-container-images .control');
        const $imagesSliderHeight = $imagesSlider[0] ? $imagesSlider[0].scrollHeight : 0;
        const maxImagesHeight = 486;
        $imagesSliderHeight > maxImagesHeight ? $imagesControl.show() : $imagesControl.hide();
    }

    $(document).ready(function() {
        showOrHideControls();
    });
</script>

@include('includes.ficha.modals_ficha')
