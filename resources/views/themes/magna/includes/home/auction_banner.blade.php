@php
    use App\Models\V5\AucSessionsFiles;
    use Carbon\Carbon;

    $emp = Config::get('app.emp');

    $dateFormat = Carbon::parse($auction->session_start)
        ->locale(config('app.locale'))
        ->isoFormat('LL');

    $auctionImage = Tools::urlAssetsCache("/img/AUCTION_{$emp}_{$auction->cod_sub}.jpg");

    $url_lotes = Tools::url_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions, $auction->reference);

    $catalogLink = AucSessionsFiles::where([['"auction"', $auction->cod_sub], ['"type"', 5]])->first();
@endphp
<section class="auction-banner container-fluid">
    <div class="row g-0 auction-banner-row">
        <div class="col-12 col-md-5 auction-banner-info-column">
            <div>
                <h1>{{ $auction->name }}</h1>
				<h3>{{ $dateFormat }}</h3>

                <a class="btn btn-outline-lb-primary rounded-5" href="{{ $url_lotes }}">
					{{ trans("$theme-app.subastas.go_to_the_auction") }}
                </a>

                @if ($catalogLink)
                    <a class="btn btn-outline-lb-primary rounded-5" href="{{ $catalogLink->url }}" target="_blank">
                        {{ trans("$theme-app.subastas.see_catalogue") }}
                    </a>
                @endif
            </div>
        </div>
        <div class="col-12 col-md-7">
            <img src="{{ $auctionImage }}" alt="">
        </div>
    </div>
</section>
