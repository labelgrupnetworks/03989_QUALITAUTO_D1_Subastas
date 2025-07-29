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
        <div class="col-xs-12">
            <div class="grid-title-wrapper">
                <h1 class="grid-title">
                    @if (\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1'))
                        {{ $lote_actual->ref_asigl0 }} - {{ $lote_actual->titulo_hces1 }}
                    @elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1'))
                        {{ $lote_actual->titulo_hces1 }}
                    @elseif(\Config::get('app.ref_asigl0'))
                        {{ trans(\Config::get('app.theme') . '-app.lot.lot-name') }} {{ $lote_actual->ref_asigl0 }}
                    @endif
                    @if ($lote_actual->isItp)
                        <span class="lot-itp-mark">*</span>
                    @endif
                </h1>

                <div class="next">
                    @if (!empty($data['previous']))
                        <a class="nextLeft" href="{{ $data['previous'] }}" title="{{ trans("$theme-app.subastas.last") }}">
                            <i class="fa fa-angle-left fa-angle-custom"></i>
                            {{ trans("$theme-app.subastas.last") }}
                        </a>
                    @endif
                    @if (!empty($data['next']))
                        <a class="nextRight" href="{{ $data['next'] }}" title="{{ trans("$theme-app.subastas.next") }}">
                            {{ trans("$theme-app.subastas.next") }}
                            <i class="fa fa-angle-right fa-angle-custom"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container lot-ficha">
    <div class="row">
        <div class="single">

            <div class="col-xs-12 col-md-7 mb-2 lot-ficha_media">

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

                </div>

                <!-- Inicio Galeria Desktop -->
                <div class="hidden-xs d-flex" style="gap: .5rem">

                    {{-- Miniaturas imagenes --}}
                    <div class="slider-thumnail-container slider-thumnail-container-images" style="width: 95px">

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
                        </div>

                        <div class="row-down control" onClick="clickControl(this)">
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </div>
                    </div>

                    {{-- Miniaturas recursos --}}
                    <div style="width: 95px" @class([
                        'slider-thumnail-container slider-thumnail-container-resources',
                        'd-none' => empty($resourcesList),
                    ])>

                        <div class="row-up control" onClick="clickControl(this)">
                            <i class="fa fa-chevron-up" aria-hidden="true"></i>
                        </div>

                        <div class="miniImg slider-thumnail js-slider-thumnail-resources">

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

                    <div class="" style="flex: 1;">
                        <div class="img_single_border">



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
                                    <button class="btn btn-link btn-chevron" id="chevron-left">
                                        <x-icon.boostrap icon="chevron-left" size="32" />
                                    </button>
                                </div>
                                <div class="chevron-right-button" role="">
                                    <button class="btn btn-link btn-chevron" id="chevron-right">
                                        <x-icon.boostrap icon="chevron-right" size="32" />
                                    </button>
                                </div>
                            </div>

                            <div class="toolbar" id="js-toolbar" style="position: absolute; z-index: 1">
                                <a id="zoom-in" href="#zoom-in" title="Zoom in">
                                    <svg class="bi" width="24" height="24" fill="currentColor">
                                        <use xlink:href="/bootstrap-icons.svg#plus-circle"></use>
                                    </svg>
                                </a>

                                <a id="zoom-out" href="#zoom-out" title="Zoom out">
                                    <svg class="bi" width="24" height="24" fill="currentColor">
                                        <use xlink:href="/bootstrap-icons.svg#dash-circle"></use>
                                    </svg>
                                </a>

                                <a id="full-page" href="#full-page" title="Toggle full page">
                                    <svg class="bi" width="24" height="24" fill="currentColor">
                                        <use xlink:href="/bootstrap-icons.svg#arrows-fullscreen"></use>
                                    </svg>
                                </a>
                            </div>

                            <div class="text-center" id="resource_main_wrapper" style="display:none"></div>
                            <div class="img-global-content position-relative">
                                <div class="img_single" id="img_main"></div>
                            </div>

                        </div>
                    </div>


                </div>


            </div>

            <div class="col-xs-12 col-md-5">
                <div class="row">
                    <div class="col-xs-12 col-sm-12">

                        @if ($lote_actual->retirado_asigl0 == 'N' && $lote_actual->fac_hces1 != 'D' && $lote_actual->fac_hces1 != 'R')
                            @if ($lote_actual->subc_sub != 'A' && $lote_actual->subc_sub != 'S')
                                @include('includes.ficha.pujas_ficha_cerrada')
                            @elseif(
                                $lote_actual->tipo_sub == 'V' &&
                                    $lote_actual->cerrado_asigl0 != 'S' &&
                                    strtotime($lote_actual->end_session) > date('now'))
                                @include('includes.ficha.pujas_ficha_V')

                                <?php //si un lote cerrado no se ha vendido se podra comprar
                                ?>
                            @elseif(
                                ($lote_actual->tipo_sub == 'W' || $lote_actual->tipo_sub == 'O') &&
                                    $lote_actual->cerrado_asigl0 == 'S' &&
                                    empty($lote_actual->himp_csub) &&
                                    $lote_actual->desadju_asigl0 == 'N' &&
                                    $lote_actual->compra_asigl0 == 'S' &&
                                    $lote_actual->fac_hces1 != 'D')
                                @include('includes.ficha.pujas_ficha_V')
                            @elseif(
                                ($lote_actual->tipo_sub == 'O' || $lote_actual->tipo_sub == 'P' || $lote_actual->subabierta_sub == 'P') &&
                                    $lote_actual->cerrado_asigl0 != 'S')
                                @include('includes.ficha.pujas_ficha_O')
                            @elseif($lote_actual->tipo_sub == 'W' && $lote_actual->cerrado_asigl0 != 'S')
                                @include('includes.ficha.pujas_ficha_W')



                                <?php //puede que este cerrado 'S' o devuelto 'D'
                                ?>
                            @else
                                @include('includes.ficha.pujas_ficha_cerrada')
                            @endif
                        @endif
                    </div>
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-lg-5 pull-right right_row">
                <div class="col-xs-12 col-sm-12">
                    @if (
                        (strtoupper($lote_actual->tipo_sub) == 'O' ||
                            strtoupper($lote_actual->tipo_sub) == 'P' ||
                            $lote_actual->subabierta_sub == 'P') &&
                            $lote_actual->cerrado_asigl0 != 'S' &&
                            $lote_actual->retirado_asigl0 == 'N')
                        @include('includes.ficha.history')
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-7">
                @if (!empty($lote_actual->contextra_hces1))
                    <div style="position:absolute;left: 502px; top:6px;z-index: 100;">
                        <a href="{{ $lote_actual->contextra_hces1 }}" target="_blank">
                            <img src="/default/img/icons/video.png" style="height:25px" />
                        </a>
                    </div>
                @endif
                <div class="desc">
                    <div class="desc_tit position-relative">
						<span>
                        	{{ trans('web.lot.description') }}
						</span>

                        @if (Session::has('user') &&  $lote_actual->retirado_asigl0 == 'N')
                            <div class="favoritos">
                                <button id="del_fav" @class(['btn btn-link', 'hidden' => !$lote_actual->favorito])
                                    onclick="action_fav_modal('remove')">
                                    <x-icon.boostrap icon="heart-fill" size="24" />
                                </button>
                                <button id="add_fav" @class(['btn btn-link', 'hidden' => $lote_actual->favorito]) onclick="action_fav_modal('add')">
                                    <x-icon.boostrap icon="heart" size="24" />
                                </button>
                            </div>
                        @endif
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
                        url: '/img/load/real/{{ $imagen }}'
                    },
                @endforeach
            ],
            showNavigator: false,
            sequenceMode: true,
            toolbar: "js-toolbar",
            zoomInButton: "zoom-in",
            zoomOutButton: "zoom-out",
            fullPageButton: "full-page",
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
        const $resourcesSlider = $('.js-slider-thumnail-resources');
        const $imagesControl = $('.slider-thumnail-container-images .control');
        const $resourcesControl = $('.slider-thumnail-container-resources .control');
        const $imagesSliderHeight = $imagesSlider[0] ? $imagesSlider[0].scrollHeight : 0;
        const $resourcesSliderHeight = $resourcesSlider[0] ? $resourcesSlider[0].scrollHeight : 0;
        const maxImagesHeight = 486;

        $imagesSliderHeight > maxImagesHeight ? $imagesControl.show() : $imagesControl.hide();
        $resourcesSliderHeight > maxImagesHeight ? $resourcesControl.show() : $resourcesControl.hide();
    }

    $(document).ready(function() {
        showOrHideControls();
    });
</script>

@include('includes.ficha.modals_ficha')
