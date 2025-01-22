{{-- listamos los recursos que se hayan puesto en la carpeta de videos para mostrarlos en la imagen principal --}}
@php
    $resourcesList = [];
    foreach ($lote_actual->videos ?? [] as $key => $video) {
		if (strtolower(substr($video, -5)) == '.html') {
			continue;
		}

        $resource = ['src' => $video, 'format' => 'VIDEO'];
        if (strtolower(substr($video, -4)) == '.gif') {
            $resource['format'] = 'GIF';
        }

        $resourcesList[] = $resource;
    }

    #debemos poenr el código aqui par que lo usen en diferentes includes
    if ($subasta_web) {
        $nameCountdown = 'countdown';
        $timeCountdown = $lote_actual->start_session;
    } elseif ($subasta_venta) {
        $nameCountdown = 'countdown';
        $timeCountdown = $lote_actual->end_session;
    } elseif ($subasta_online) {
        $nameCountdown = 'countdownficha';
        $timeCountdown = $lote_actual->close_at;
    } else {
        $nameCountdown = 'countdown';
        $timeCountdown = $lote_actual->end_session;
    }

    //En la empresa 001, no se utilizan, puede que pueda eliminarlo
    /**
     * indice de caracteristicas
     * 1 - Autor; 2 - Técnica; 3 - Medidas; 4 - Fechas del autor
     * */
    $caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);

    //0.autor, 1.fechas autor, 2.Nombre de obra, 3.Medidas
    //En vehículos solo hay 1 y es description. En algunos relojes han añadido el campo de peso, pero sigue siendo parte de la descripción
    $onlyDescription = strpos($lote_actual->desc_hces1, 'class="description"') !== false || strpos($lote_actual->desc_hces1, 'class="PESO_HCES1"') !== false;
    $notCleanTags = $onlyDescription ? '<br><br />' : '<br><br /><span>';

    $htmlCleaned = strip_tags($lote_actual->desc_hces1, $notCleanTags);

    $arrayDescriptions = [];
	if($onlyDescription) {
		$htmlCleaned = str_replace('<br />', '<br>', $htmlCleaned);
		$arrayDescriptions = explode('<br>', $htmlCleaned);
	}
	else {
		$arrayDescriptions = Tools::decodeHtmlStringToArrayByTag($htmlCleaned, 'span', function ($element) use ($theme) {
            if ($element->getAttribute('class') === 'medidas') {
                $element->setAttribute('data-title', trans("$theme-app.lot.measures") . ': ');
            }
        });

	}
@endphp

    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-7" id="images-wrapper">
                <div class="d-flex gap-2">

                    <div class="images">

                        @foreach ($resourcesList as $key => $resource)
                            <div class="resorce_main @if ($resource['format'] === 'GIF') resource-gif @endif"
                                id="resource_{{ $key }}">

                                @if ($resource['format'] === 'VIDEO')
                                    <div class="ratio ratio-16x9">
                                        <video width="320" height="240" controls>
                                            <source src="{{ $resource['src'] }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @elseif($resource['format'] === 'GIF')
                                    <img src="{{ $resource['src'] }}" alt="{{ $lote_actual->titulo_hces1 }}">
                                @endif

                            </div>
                        @endforeach

                        @foreach ($lote_actual->imagenes as $key => $imagen)
                            @php
                                $image = "/img/thumbs/500/001/$lote_actual->num_hces1/$imagen";
                            @endphp
                            <div class="img_main" id="image_{{ $key }}" data-image="{{ $imagen }}">

                                <div id="js-toolbar_{{ $key }}" class="toolbar image-toolbar">
                                    <a id="zoom-in_{{ $key }}" href="#zoom-in_{{ $key }}"
                                        title="Zoom in">
                                        <svg class="bi" width="24" height="24" fill="currentColor">
                                            <use xlink:href="/bootstrap-icons.svg#plus"></use>
                                        </svg>
                                    </a>

                                    <a id="zoom-out_{{ $key }}" href="#zoom-out_{{ $key }}"
                                        title="Zoom out">
                                        <svg class="bi" width="24" height="24" fill="currentColor">
                                            <use xlink:href="/bootstrap-icons.svg#dash"></use>
                                        </svg>
                                    </a>

                                    <a id="home_{{ $key }}" href="#home_{{ $key }}" title="Go home">
                                        <svg class="bi" width="24" height="24" fill="currentColor">
                                            <use xlink:href="/bootstrap-icons.svg#arrow-clockwise"></use>
                                        </svg>
                                    </a>

                                    <a id="full-page_{{ $key }}" href="#full-page_{{ $key }}"
                                        title="Toggle full page">
                                        <svg class="bi" width="24" height="24" fill="currentColor">
                                            <use xlink:href="/bootstrap-icons.svg#fullscreen"></use>
                                        </svg>
                                    </a>
                                </div>

                                <img src="{{ $image }}" alt="{{ $lote_actual->titulo_hces1 }}">
                            </div>
                        @endforeach
                    </div>

                    {{-- Miniaturas --}}
                    <div class="minis-content d-none d-lg-flex">
                        <div class="minis-content-wrapper">

                            {{-- solo mostramos las minis si hay mas de una o si tambien hay gif --}}
                            @if (count($lote_actual->imagenes) > 1 || count($resourcesList) > 0)
                                @foreach ($resourcesList as $key => $resource)
                                    <a class="mini-img-ficha no-360" href="#resource_{{ $key }}">
                                        <img src="{{ $resource['format'] == 'GIF' ? $resource['src'] : asset('/img/icons/video_thumb.png') }}"
                                            alt="">
                                    </a>
                                @endforeach

                                @foreach ($lote_actual->imagenes as $key => $imagen)
                                    <a class="mini-img-ficha no-360" href="#image_{{ $key }}">
                                        <img src="{{ Tools::url_img('lote_small', $lote_actual->num_hces1, $lote_actual->lin_hces1, $key) }}"
                                            alt="{{ $lote_actual->titulo_hces1 }}">
                                    </a>
                                @endforeach
                            @endif

                        </div>
                    </div>
					{{-- / Miniaturas --}}

                </div>

            </div>
            <div class="col-12 col-lg-4">
                <section class="ficha-info d-flex flex-column">

                    <div class="">
                        <h2 class="ff-highlight ficha-info-lot float-start">
                            {{ trans("$theme-app.lot.lot-name") . ' ' . $lote_actual->ref_asigl0 }}
                        </h2>

						<div class="float-end d-flex align-items-center gap-3">
							@if ($subasta_online && !$cerrado)
							<p class="ff-highlight ficha-info-lot mb-0">
								<span class="d-flex align-items-center gap-2" style="font-size: .9em">
									<x-icon.boostrap  icon="clock" size=".8em" />
									<span data-countdown="{{ strtotime($lote_actual->close_at) - getdate()[0] }}"
										data-format="{{ Tools::down_timer($lote_actual->close_at) }}" class="timer"></span>
								</span>
							</p>
							@endif

							@if (Session::has('user') && !$retirado)
								<div class="favoritos">
									<button id="del_fav"
										class="lb-text-primary @if (!$lote_actual->favorito) hidden @endif"
										onclick="action_fav_modal('remove')">
										<svg class="bi" width="24" height="24" fill="currentColor">
											<use xlink:href="/bootstrap-icons.svg#heart-fill"></use>
										</svg>
									</button>
									<button id="add_fav"
										class="lb-text-primary @if ($lote_actual->favorito) hidden @endif"
										onclick="action_fav_modal('add')">
										<svg class="bi" width="24" height="24" fill="currentColor">
											<use xlink:href="/bootstrap-icons.svg#heart"></use>
										</svg>
									</button>

								</div>
							@endif
						</div>

                    </div>

                    <h1 class="ficha-info-title">
                        @if (count($arrayDescriptions) > 1)
                            {!! array_shift($arrayDescriptions) !!}
                        @else
                            {{ $lote_actual->titulo_hces1 }}
                        @endif
                    </h1>
                    <div class="ficha-info-description">
                        @foreach ($arrayDescriptions as $description)
                            <p>{!! $description !!}</p>
                        @endforeach
                    </div>

                    @if (!$retirado && !$devuelto && !$fact_devuelta)
                        <div class="ficha-info-content">

                            @if ($sub_cerrada)
                                @include('includes.ficha.pujas_ficha_cerrada')
                            @elseif($subasta_venta && !$cerrado && !$end_session)
                                @include('includes.ficha.pujas_ficha_V')

                                {{-- si un lote cerrado no se ha vendido se podra comprar --}}
                            @elseif(($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) && $compra && !$fact_devuelta)
                                @include('includes.ficha.pujas_ficha_V')

                                {{-- si una subasta es abierta p solo entraremso a la tipo online si no esta iniciada la subasta --}}
                            @elseif(($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) && !$cerrado)
                                @include('includes.ficha.pujas_ficha_O')
                            @elseif($subasta_web && !$cerrado)
                                @include('includes.ficha.pujas_ficha_W')
                            @else
                                @include('includes.ficha.pujas_ficha_cerrada')
                            @endif

                        </div>
                    @endif

					<div class="prev-next-buttons">
						@if (!empty($data['previous']))
							<a class="swiper-button-prev" title="{{ trans("$theme-app.subastas.last") }}" href="{{ $data['previous'] }}">
							</a>
						@endif

						@if (!empty($data['next']))
							<a class="swiper-button-next" title="{{ trans("$theme-app.subastas.next") }}" href="{{ $data['next'] }}">
							</a>
						@endif
					</div>

					@if (($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && !$retirado)
						<div>
							@include('includes.ficha.history')
						</div>
					@endif

            </div>



            {{--  @include('includes.ficha.share') --}}

        </div>
    </div>

    {{-- Lotes destacados --}}
    <section class="container recomendados">
        <h3 class="ff-highlight section-title">{{ trans("$theme-app.lot.recommended_lots") }}</h3>

        <div id="lotes_recomendados" class="owl-theme owl-carousel"></div>
    </section>
    {{-- / Lotes destacados --}}

@php
    $replace = [
        'emp' => Config::get('app.emp'),
        'sec_hces1' => $lote_actual->sec_hces1,
        'num_hces1' => $lote_actual->num_hces1,
        'lin_hces1' => $lote_actual->lin_hces1,
        'cod_sub' => $lote_actual->cod_sub,
        'lang' => Config::get('app.language_complete')[Config::get('app.locale')],
    ];
    $lang = Config::get('app.locale');
@endphp

<script>
    let replace = @json($replace);

    $(document).ready(function() {
        ajax_newcarousel('lotes_recomendados', replace, '{{ $lang }}');
    });


    @if (count($resourcesList) > 0)
        viewResourceFicha('{{ head($resourcesList)['src'] }}', '{{ head($resourcesList)['format'] }}');
    @endif

    initImagesVisor();

    function initImagesVisor() {
        const size = window.innerWidth;
        if (size >= 992) {
            initOpen();
        } else {
            $('.images').slick({
                arrows: false,
                adaptiveHeight: true
            });

        }

    }


    function initOpen() {
        document.querySelectorAll('.img_main').forEach((element, index) => {
            OpenSeadragon({
                id: element.id,
                prefixUrl: "/img/opendragon/",
                showReferenceStrip: true,
                tileSources: [{
                    type: 'image',
                    url: "/img/load/real/" + element.dataset.image
                }],
                toolbar: `js-toolbar_${index}`,
                zoomInButton: `zoom-in_${index}`,
                zoomOutButton: `zoom-out_${index}`,
                homeButton: `home_${index}`,
                fullPageButton: `full-page_${index}`
            })
            element.querySelector('img').style.display = 'none';
        })
    }
</script>
