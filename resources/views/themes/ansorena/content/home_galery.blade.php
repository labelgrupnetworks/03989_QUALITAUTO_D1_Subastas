@php
    use App\Models\V5\FgSub;
    use App\Models\V5\FgAsigl0;
    use App\Models\V5\Web_Artist;
    $fgsubModel = new FgSub();

    #Cojemos la exposicion/subasta tipo E  activa que empiece antes, si no quieren que aparezca esa que la pongan en histórico
    $actualExposure = FgSub::leftJoinArtistExhibition()
        ->where('SUBC_SUB', 'S')
        ->where('TIPO_SUB', 'E')
        ->where('OPCIONCAR_SUB', 'N')
        ->orderby('DFEC_SUB', 'desc')
        ->first()
        ?->getExhibitionFieldsAttribute();

    $exposures = FgSub::leftJoinArtistExhibition()
        ->where('SUBC_SUB', 'H')
        ->where('TIPO_SUB', 'E')
        ->where('OPCIONCAR_SUB', 'N')
        ->orderby('DFEC_SUB', 'desc')
        ->limit(3)
        ->get()
        ->map(function ($exposure) {
            return $exposure->getExhibitionFieldsAttribute();
        });

    $exposuresOnline = FgSub::leftJoinArtistExhibition()
        ->whereIn('SUBC_SUB', ['S', 'H'])
        ->where('TIPO_SUB', 'E')
        ->where('OPCIONCAR_SUB', 'S')
        ->orderby('DFEC_SUB', 'desc')
        ->limit(3)
        ->get()
        ->map(function ($exposure) {
            return $exposure->getExhibitionFieldsAttribute();
        });

    $galleryCollections = FgAsigl0::select('id_artist, name_artist,num_hces1, lin_hces1, ref_asigl0')
        ->ActiveLotAsigl0()
        ->join('FGCARACTERISTICAS_HCES1', 'FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 = FGASIGL0.EMP_ASIGL0 AND NUMHCES_CARACTERISTICAS_HCES1 = FGASIGL0.NUMHCES_ASIGL0 AND LINHCES_CARACTERISTICAS_HCES1 = FGASIGL0.LINHCES_ASIGL0')
        ->join('WEB_ARTIST', 'WEB_ARTIST.EMP_ARTIST = FGCARACTERISTICAS_HCES1.EMP_CARACTERISTICAS_HCES1 AND WEB_ARTIST.ID_ARTIST =  FGCARACTERISTICAS_HCES1.IDVALUE_CARACTERISTICAS_HCES1')
        ->wherein('TIPO_SUB', ['E', 'F'])
        ->where('COMPRA_ASIGL0', 'S')
        ->where('STOCK_HCES1', '>=', '1')
        #ORDENAMOS POR DESTACADO DESC PARA QUE PONGA PRIMERO EL DESTACADO SI EXISTE, SI NO, COJERÁ EL QUE TENGA LA REFERENCIA MÁS PEQUEÑA
        ->orderby('NAME_ARTIST,DESTACADO_ASIGL0 desc, REF_ASIGL0')
        ->get()
        ->groupBy('id_artist')
        // obtener los 3 primeros artistas
        ->take(3)
        // obtener el primer item de cada artista
        ->map(function ($galleryCollection) {
            return $galleryCollection->first();
        });

    $artists = Web_Artist::select('ID_ARTIST, NAME_ARTIST')
        ->where('ACTIVE_ARTIST', '1')
        ->orderby('DBMS_RANDOM.RANDOM')
        ->limit(3)
        ->get();

@endphp

@include('includes.galery.subnav')

<div class="gallery">

	@if($actualExposure)
    <section class="next-auction-wrapper next-exposure">
        <div class="next-auction-info text-center">

            <div>
                <p class="fw-semibold text-uppercase mb-1 ls-2">{{ trans("$theme-app.galery.current_exhibition") }}</p>
                <h1 class="ff-highlight next-auction-title">
                    {{ $actualExposure->artist }}</h1>
            </div>

            <div class="v-separator"></div>

            <div class="d-flex flex-column align-items-center gap-3">
                <h2 class="ff-highlight fs-32-48 mb-0">{{ $actualExposure->title }}</h2>
                <p class="text-uppercase next-auction-date mb-2">{{ $actualExposure->initialDate }} -
                    {{ $actualExposure->finalDate }}</p>
                <a class="btn btn-lb-primary btn-medium"
                    href="{{ $actualExposure->url }}">{{ trans("$theme-app.subastas.know_more") }}</a>
            </div>

        </div>
        <div class="next-auction-image">
            <img src="{{ $actualExposure->image }}" alt="Portada de la subasta" height="758" width="950">
        </div>
    </section>
	@endif

    <section class="container medium-container landing-section">
        <h2 class="landing-section-title ff-highlight">{{ trans("$theme-app.galery.previous_exhibition") }}</h2>
        <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-4">
            @foreach ($exposures as $exposure)
                <div class="col">
					@include('includes.galery.exhibition', [
						'lazyLoad' => false,
						'exhibition' => $exposure
					])
                </div>
            @endforeach
        </div>
        <a href="{{ route('exposiciones', ['online' => 'N']) }}"
            class="btn btn-outline-lb-primary btn-medium">{{ trans("$theme-app.global.see_more") }}</a>

    </section>

    <section class="container medium-container landing-section">
        <h2 class="landing-section-title ff-highlight">
            {{ trans("$theme-app.galery.online_exhibitions") }}
        </h2>
        <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-4">
            @foreach ($exposuresOnline as $exposure)
                <div class="col">
					@include('includes.galery.exhibition', [
						'lazyLoad' => false,
						'exhibition' => $exposure
					])
                </div>
            @endforeach
        </div>
        <a href="{{ route('exposiciones', ['online' => 'S']) }}"
            class="btn btn-outline-lb-primary btn-medium">{{ trans("$theme-app.global.see_more") }}</a>

    </section>

    <section class="container-fluid" style="background-color: #F0EEE6">
        <div class="container medium-container landing-section">
            <h2 class="landing-section-title ff-highlight">
                {{ trans("$theme-app.galery.gallery_collection") }}
            </h2>
            <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-4">
                @foreach ($galleryCollections as $galleryCollection)
                    <div class="col">
						@include('includes.galery.gallery_collection', ['galleryCollection' => $galleryCollection])
                    </div>
                @endforeach
            </div>
            <a href="{{ route('fondoGaleria') }}"
                class="btn btn-outline-lb-primary btn-medium">{{ trans("$theme-app.global.see_more") }}</a>
        </div>

    </section>

    <section class="container landing-section">
        <h2 class="landing-section-title ff-highlight">
            {{ trans("$theme-app.galery.artists") }}
        </h2>
        {{-- <p class="landing-section-description">{{ trans("$theme-app.home.artists_text") }}</p> --}}
        <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-4">
            @foreach ($artists as $artist)
                <div class="col">
					@include('includes.galery.artist', ['artist' => $artist])
                </div>
            @endforeach
        </div>
        <a href="{{ route('artistasGaleria') }}"
            class="btn btn-outline-lb-primary btn-medium">{{ trans("$theme-app.global.see_more") }}</a>

    </section>
</div>
