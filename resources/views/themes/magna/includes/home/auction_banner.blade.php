@php
    use App\Models\V5\AucSessionsFiles;
    $emp = Config::get('app.emp');
    $auctionImage = Tools::urlAssetsCache("/img/AUCTION_{$emp}_{$auction->cod_sub}.jpg");

    $url_lotes = Tools::url_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions, $auction->reference);

    $catalogLink = AucSessionsFiles::where([['"auction"', $auction->cod_sub], ['"type"', 5]])->first();
@endphp
<section class="auction-banner container-fluid">
    <div class="row g-0 auction-banner-row">
        <div class="col-12 col-md-5 auction-banner-info-column">
            <div>
                <h1>{{ $auction->name }}</h1>

                <a class="btn btn-outline-lb-primary rounded-5" href="{{ $url_lotes }}">
                    Ir a la subasta
                </a>

				@if($catalogLink)
                <a class="btn btn-outline-lb-primary rounded-5" href="{{ $catalogLink->url }}" target="_blank">
                    Ver catálogo
                </a>
				@endif
            </div>
        </div>
        <div class="col-12 col-md-7">
            <img src="{{ $auctionImage }}" alt="">
        </div>
    </div>
</section>
