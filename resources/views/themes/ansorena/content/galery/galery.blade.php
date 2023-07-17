@php
    use App\Models\V5\FgAsigl0;
    use App\Models\V5\FgSub;
    $exhibition = $auction->getExhibitionFieldsAttribute();
    $withMultipleArtists = count($artists) > 1;
    $artist = $artists->first();

    if (!$withMultipleArtists && count($exhibitions) > 0) {
        $auctionCods = $exhibitions->pluck('cod_sub');

        $firstLotFromExhibitions = FgAsigl0::select('cod_sub, numhces_asigl0, linhces_asigl0')
            ->JoinSubastaAsigl0()
            ->where('ref_asigl0', 1)
            ->wherein('cod_sub', $auctionCods)
            ->get();

        $imgSubastas = [];

        foreach ($firstLotFromExhibitions as $lot) {
            $imgSubastas[$lot->cod_sub] = \Tools::url_img('square_medium', $lot->numhces_asigl0, $lot->linhces_asigl0, null, true);

            $numArtist = FgAsigl0::select('count(distinct(IDVALUE_CARACTERISTICAS_HCES1)) as numArtist')
                ->leftjoin('FGCARACTERISTICAS_HCES1', "FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0 AND FGCARACTERISTICAS_HCES1.IDCAR_CARACTERISTICAS_HCES1 = '" . \Config::get('app.ArtistCode') . "'")
                ->where('COD_SUB', $lot->cod_sub)
                #ordenamos por orden, pero tambien tenemos en cuenta la referencia ya que por defecto el orden esta a nully rompia la ordenacion
                ->ActiveLotAsigl0()
                ->first();

            $artistExhibition = null;
            if ($numArtist->numartist == 1 && count($artists) == 1) {
                $artistExhibition = $artists[0];
            }
        }

        $exhibitions = $exhibitions->map(function ($exhibition) use ($imgSubastas, $artistExhibition) {
            $theme = Config::get('app.theme');
            $newExhibition = (new FgSub($exhibition->toArray()))->getExhibitionFieldsAttribute();
            $newExhibition->image = $imgSubastas[$exhibition->cod_sub] ?? \Tools::url_img_auction('subasta_medium', $exhibition->cod_sub);
            $newExhibition->name_artist = !empty($artistExhibition) ? $artistExhibition->name_artist : trans("$theme-app.galery.collective");
            return $newExhibition;
        });
    }
@endphp

<div class="mandatory-block_desactivate">

    @include('includes.galery.subnav')

    <div class="gallery-cover-image">
        <img class="img-cover" src="{{ Tools::auctionImage($auction->cod_sub) }}" alt="" height="758">
    </div>

    <div class="container gallery-exhibition d-flex flex-column align-items-center text-center">
        <h1 class="ff-highlight gallery-exhibition-title mb-4 mb-lg-3">
            @if ($withMultipleArtists)
                {{ trans("$theme-app.galery.collective") }}
            @elseif(!empty($artist))
                {{ $artist->name_artist }}
            @endif
        </h1>

        <div class="v-separator d-none d-lg-block"></div>

        <h2 class="ff-highlight fs-24-32">
            @if (!$withMultipleArtists || trim(mb_strtoupper($exhibition->title)) != trim(mb_strtoupper($artist->name_artist)))
                {{ $exhibition->title }}
            @endif
        </h2>
        <p class="gallery-exhibition-dates">{{ $exhibition->initialDate }} - {{ $exhibition->finalDate }}</p>
    </div>

</div>

<div class="gallery-exhibition-swipper">
    <div class="swiper-scrollbar d-none d-lg-block"></div>

    <div class="gallery-exhibition-content">

		<p class="ff-highlight fs-24-32 text-uppercase text-center d-lg-none">{{ trans("$theme-app.subastas.in_pictures") }}</p>

        <section class="gallery-lots">

            <div class="position-relative d-none d-lg-flex" style="min-width: 500px">

                <div class="d-flex flex-column justify-content-between align-items-center h-100 w-100">
                    <p class="ff-highlight fs-24-32 text-uppercase">{{ trans("$theme-app.subastas.in_pictures") }}</p>

					<div class="">
						@include('content.galery._social_links')
					</div>

                </div>

            </div>

            @foreach ($lots as $lot)
                @include('includes.galery.lot_galery', [
                    'lot' => $lot,
                    'class' => '',
                ])
            @endforeach

            <div class="justify-content-between align-items-center px-5 py-4 position-relative d-none d-lg-flex" style="min-width: 206px">
                <section class="marquesina">
                    <div class="">
                        <span>ANSORENA</span>
                        <span>NEWSLETTER</span>
                    </div>
                    <div class="">
                        <span>ANSORENA</span>
                        <span>NEWSLETTER</span>
                    </div>
                </section>
            </div>
            <div class="justify-content-between align-items-center px-5 py-4 position-relative d-none d-lg-flex" style="min-width: 500px">
                <div>
                    @include('includes.newsletter_form')
                </div>
            </div>

        </section>

		<div class="d-lg-none position-relative">
			<div class="swiper-buttons swiper-buttons-gallery d-lg-none">
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</div>
			@include('content.galery._social_links')
		</div>
    </div>

</div>

<section class="container gallery-exhibition-info">
    <div class="row gy-5">

        @if (!$withMultipleArtists && !empty($artist))
            <div class="offset-lg-2 col-lg-4">
                <div class="gallery-exhibition-artist">
                    <p class="ff-highlight exhibition-artist-name">{{ $artist->name_artist }}</p>
                    <p class="ff-highlight exhibition-artist-info">{{ $artist->info_artist }}</p>
                    <button class="btn btn-lb-primary btn-medium mt-4" data-bs-toggle="modal"
                        data-bs-target="#biographyModal">
                        {{ trans("$theme-app.galery.see_biography") }}
                    </button>
                </div>
            </div>
        @endif

        <div
            class="exhibition-description-wrapper @if (empty($artist)) col-lg-6 mx-auto mt-4 @else col-lg-5 @endif">
            <div class="gallery-exhibition-description" data-show="false">
                @if (!empty($auction->descdet_sub))
                    {!! nl2br($auction->descdet_sub) !!}
                @endif
            </div>
            <p class="text-center mt-3">
                <a id="js-seeMoreDescription" class="lb-link-underline gallery-exhibition-seemore"
                    data-show-text="{{ trans("$theme-app.galery.keep_reading") }}"
                    data-hidden-text="{{ trans("$theme-app.galery.show_less") }}">{{ trans("$theme-app.galery.keep_reading") }}</a>
            </p>
        </div>

    </div>
</section>

@if (!$withMultipleArtists && count($exhibitions) > 0)
    <section class="container medium-container gallery-more-exhibitons">
        <h3 class="ff-highlight fs-24">{{ trans("$theme-app.galery.exposicionesArtista") }}</h3>

        <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-4 gy-4">
            @foreach ($exhibitions as $exposure)
                <div class="col mx-auto">
                    @include('includes.galery.exhibition', [
                        'lazyLoad' => false,
                        'exhibition' => $exposure,
                    ])
                </div>
            @endforeach
        </div>
    </section>
@endif

@if (!$withMultipleArtists && !empty($artist))
    <div class="modal biography-modal fade" id="biographyModal" tabindex="-1" aria-labelledby="biographyModalLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="biography-modal-content">
                        {!! str_replace('&nbsp;', '', $artist->biography_artist) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<script>
    const biographyModal = document.getElementById('biographyModal');
    biographyModal?.addEventListener('show.bs.modal', event => {
        //al abrir el modal simulamos la apertura del menu para anular el translate y que el modal se muestre correctamente;
        const menu = document.getElementById('menu-header');
        menu.classList.add('open');
    })

    document.getElementById('js-seeMoreDescription').addEventListener('click', (event) => {
        const descriptionBlock = document.querySelector('.gallery-exhibition-description');
        const isShow = descriptionBlock.dataset.show === 'true';

        descriptionBlock.dataset.show = !isShow;
        event.target.innerText = isShow ? event.target.dataset.showText : event.target.dataset.hiddenText;

        if (isShow) {
            window.scrollTo({
                top: descriptionBlock.offsetTop - 190,
                behavior: 'smooth'
            });
        }
    });
</script>
