@php
	use App\Models\articles\FgArt0;

    $titulo = $article->model_art0;
    $img = $article->img;
    $description = $article->des_art0;
    $images = $article->images;
    $images[] = '/themes/ansorena/assets/img/pages/generico-joyeria.jpg';

    $relatedArticles = FgArt0::select('FGART0.ID_ART0, FGART0.PVP_ART0, FGART0.SEC_ART0')
		->selectRaw('NVL(FGART0_LANG.MODEL_ART0_LANG, FGART0.MODEL_ART0) as model_art0')
		->leftJoinFgArt0Lang()
        ->where('FGART0.ID_ART0', '!=', $article->id_art0)
        ->where('FGART0.SEC_ART0', '=', $article->sec_art0)
        ->activo()
        ->orderby(' DBMS_RANDOM.VALUE')
        ->take(3)
        ->get();
@endphp

<div class="container">
    <div class="row">
        <div class="col-12 col-lg-7" id="images-wrapper">
            <div class="d-flex gap-2">

                <div class="images">
                    @foreach ($images as $key => $image)
                        <div class="img_main" id="image_{{ $key }}" data-image="{{ $image }}">

                            <div id="js-toolbar_{{ $key }}" class="toolbar image-toolbar">
                                <a id="zoom-in_{{ $key }}" href="#zoom-in_{{ $key }}" title="Zoom in">
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

                            <img src="{{ $image }}" alt="{{ $titulo }}"
                                style="background-blend-mode: multiply">
                        </div>
                    @endforeach
                </div>

                {{-- Miniaturas --}}
                <div class="minis-content d-none d-lg-flex">
                    <div class="minis-content-wrapper">
                        @foreach ($images as $key => $imagen)
                            <a class="mini-img-ficha no-360" href="#image_{{ $key }}">
                                <img src="{{ $imagen }}" alt="{{ $titulo }}">
                            </a>
                        @endforeach
                    </div>
                </div>
                {{-- / Miniaturas --}}

            </div>
        </div>

        <div class="col-12 col-lg-4">
            <form id="articleForm">
                @csrf
                <input type="hidden" name="idArt0" value="{{ $article->id_art0 }}">
                <section class="ficha-info">
                    <h1 class="ficha-info-title">
                        {{ $titulo }}
                    </h1>

                    <h2 class="ficha-info-description">
                        {{ $description }}
                    </h2>
                    @foreach ($variantes as $keyVariante => $variante)
                        <div class="@if (!$loop->first) hidden @endif">

                            <div class="ficha-article-select-wrapper">
                                <div id="tallaColor_{{ $keyVariante }}_container" class="ficha-article-select d-none">
                                    <select data-label="{{trans("$theme-app.articles.select_size")}}" name="tallaColor[{{ $keyVariante }}]"
                                        class="form-select tallaColor_JS  @if ($loop->first) PrincipalTallaColor_JS @endif ">
                                        {{-- se rellena por json --}}
                                    </select>
                                </div>
                            </div>

                            @if ($variante == 'TALLAS')
                                {!! trans($theme . '-app.articles.consultar_tallas') !!}</a>
                            @endif
                        </div>
                    @endforeach

                    <div class="ficha-article-prices">
                        @if ($article->imp > 0)
                            <span id="art-original_JS"
                                class="art-price_JS">{{ Tools::moneyFormat($article->imp, '', 0) }}
                            </span>
                            @foreach ($precioArticulos as $keyArticulo => $precioArticulo)
                                <span id="art-{{ $keyArticulo }}_JS"
                                    class="art-price_JS hide">{{ Tools::moneyFormat($precioArticulo, '', 0) }} </span>
                            @endforeach
                            {{ trans("$theme-app.subastas.euros") }}
                        @else
                            <p class="mt-3">{!! trans("$theme-app.articles.consultarPrecio") !!}</p>
                        @endif
                    </div>

                    <div class="ficha-article-buttons mt-1">
                        {{-- 07-07-22 Mónica ha pedido que los articulos de la seccion joyas únicas muestre siempre el cotactenos, lo saco del circuito de js que mlo muestra is hay stock  --}}
                        @if ($article->sec_art0 == 'JX' || $article->imp == 0)
                            <p class="descriptionArticlePage">
                                {!! trans($theme . '-app.articles.contactenos') !!}
                            </p>
                        @else
                            {{-- Es posible que no haya variamtes, en ese caso miramso el stock del único articulo y lo usamos para hacer que se vea o no el botón de compra --}}
                            <button
                                class="btn btn-lb-primary btn-medium w-100 addArticleCard_JS siStock_JS @if (!$article->stock) hidden @endif">
                                {{ trans("$theme-app.articles.addCart") }}
                            </button>

                            {{-- Ansorena Han pedido que no aparezca el no disponible --}}
                            {{-- <button style="width: 100%;"  disabled="disabled" type="button" >{{ trans($theme.'-app.articles.outStock') }}</button> --}}
                            <p class="noStock_JS @if ($article->stock) hidden @endif">
                                {!! trans($theme . '-app.articles.contactenos') !!}
                            </p>
                        @endif

                        <div class="ficha-article-links">
                            <a class="btn btn-outline-lb-primary btn-medium"
                                href="{{ trans("$theme-app.articles.hrefMoreInfo", ['joya' => $titulo]) }}">
                                {{ trans("$theme-app.articles.moreInfo") }}
                            </a>

                            <a class="btn btn-outline-lb-primary btn-medium"
                                href="{{ trans("$theme-app.articles.hrefCreatujoya", ['joya' => $titulo]) }}">
                                {{ trans("$theme-app.articles.creaTujoya") }}
                            </a>
                        </div>
                    </div>

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

                </section>
            </form>
        </div>

    </div>

    <section class="recomendados relatedArticles">
        <p class="ff-highlight section-title">{{ trans("$theme-app.articles.more") }}</p>

        <div class="Grid articles-container">
            @foreach ($relatedArticles as $relatedArticle)
                @include('includes/articles/relatedArticle')
            @endforeach
        </div>
    </section>

</div>

<script>
    getTallasColoresFicha();
    /*
    // no tiene que aparecer marcada ningun rsultado por defecto ya que no apareceran el resto de selects
    $("select ").each(function(){
    				$(this).val("");
    		})
    */

    initImagesVisor();

    function initImagesVisor() {
        const size = window.innerWidth;
        console.log({
            size
        });
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
                    url: element.dataset.image
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
