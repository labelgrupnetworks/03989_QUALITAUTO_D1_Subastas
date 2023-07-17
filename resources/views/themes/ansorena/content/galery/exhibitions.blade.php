@php
    use App\Models\V5\FgAsigl0;

    $auctions = $exhibitions->pluck('cod_sub');

    #cojer todos los primeros lotes para poder sacar las iamgenes.
    $lots = FgAsigl0::select('cod_sub, numhces_asigl0, linhces_asigl0')
        ->joinSubastaAsigl0()
        ->where('ref_asigl0', 1)
        ->wherein('cod_sub', $auctions)
        ->get();

    $artists = FgAsigl0::joinSubastaAsigl0()
        ->select('COD_SUB, NAME_ARTIST, ID_ARTIST')
        ->join('FGCARACTERISTICAS_HCES1', 'FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
        ->join('WEB_ARTIST', 'WEB_ARTIST.EMP_ARTIST = FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 AND WEB_ARTIST.ID_ARTIST =  FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1')
        ->whereIn('cod_sub', $auctions)
        ->groupBy('COD_SUB, ID_ARTIST, NAME_ARTIST')
        ->get();

    $exhibitionsForYear = $exhibitions
        ->map(function ($exposure) use ($lots, $artists) {
			$theme = Config::get('app.theme');
            $lot = $lots->where('cod_sub', $exposure->cod_sub)->first();
			$artistsOfTheAuction = $artists->where('cod_sub', $exposure->cod_sub);

            $exposure->name_artist = ($artistsOfTheAuction->count() === 1) ? $artistsOfTheAuction->first()->name_artist : trans("$theme-app.galery.collective");
            $exposureFormat = $exposure->getExhibitionFieldsAttribute();

			//Se la exposición se llama igual que el artista, no lo mostramos
			if(trim(mb_strtoupper($exposureFormat->title)) === trim(mb_strtoupper($exposureFormat->artist))) {
				$exposureFormat->title = "&nbsp;";
			}

            if ($lot) {
                $exposureFormat->image = Tools::url_img('square_medium', $lot->numhces_asigl0, $lot->linhces_asigl0);
            }
            return $exposureFormat;
        })
        ->groupBy(function ($exhibition, $key) {
            return intval(substr($exhibition->season, -2));
        });
@endphp

<div class="row search-filter-block" style="position: sticky; var(--top-sticky-sections)">
    <div class="col-lg-9 col-xl-10 offset-lg-3 offset-xl-2 gx-lg-0 pb-3">
        <form id="fromSearchExhibitions">
            <input type="hidden" name="online" value="{{ request('online') }}">

            @include('includes.components.search')
        </form>
    </div>
</div>

@foreach ($exhibitionsForYear->chunk(3) as $chunkBlock => $exhibitionsForYearChunkeds)
    @foreach ($exhibitionsForYearChunkeds as $year => $exhibitions)
        <div class="row exhibitions-rows @if ($chunkBlock) d-none @endif"
            data-row-block="{{ $chunkBlock + 1 }}">

            <div class="col-lg-3 col-xl-2 exposure-seasons" data-swipe-id="{{ $year }}">
                <div class="season">
                    <p>{{ trans("$theme-app.galery.season") }}</p>
                    <p class="ff-highlight year-season">
                        {{ str_pad($year, 2, '0', STR_PAD_LEFT) }}/{{ str_pad($year + 1, 2, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <div class="swiper-buttons swiper-buttons-{{ $year }}">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-10 swiper mySwiper-{{ $year }}">
                <div class="swiper-wrapper">
                    @foreach ($exhibitions as $exhibition)
                        <div class="swiper-slide">
                            @include('includes.galery.exhibition', ['lazyLoad' => $chunkBlock !== 0, 'exhibition' => $exhibition])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<div class="text-center">
    <button class="btn btn-outline-lb-primary btn-medium see-more-button"
        onclick="seeMore()">{{ trans("$theme-app.galery.see_more") }}</button>
</div>

{{-- @todo pasar a custom.js --}}
<script>
	document.querySelectorAll('[data-swipe-id]').forEach((swiper) => {
		var swiper = new Swiper(`.mySwiper-${swiper.dataset.swipeId}`, {
			slidesPerView: "auto",
			spaceBetween: 10,
			pagination: false,
			navigation: {
				nextEl: `.swiper-buttons-${swiper.dataset.swipeId} .swiper-button-next`,
				prevEl: `.swiper-buttons-${swiper.dataset.swipeId} .swiper-button-prev`,
			},
		});
	});

	if ($('.exhibitions-rows.d-none').length === 0) {
		$('.see-more-button').hide();
	}

	function seeMore(event) {

		const numberOfRowsToMakeVisibles = 3;

		$('.exhibitions-rows.d-none').each(function(index) {
			if (index < numberOfRowsToMakeVisibles) {
				$(this).removeClass('d-none');
			}
		});

		if ($('.exhibitions-rows.d-none').length === 0) {
			$('.see-more-button').hide();
		}
	}
</script>
