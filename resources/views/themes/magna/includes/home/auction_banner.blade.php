@php
    use App\Models\V5\AucSessionsFiles;
    use Carbon\Carbon;

    $emp = Config::get('app.emp');
    $locale = Config::get('app.locale');

    $auctionDate = Carbon::parse($auction->session_start)->locale($locale);

    $dateFormat = $auctionDate->isoFormat('LL');
    $tiemeFormat = $auctionDate->isoFormat('H \h');

    $auctionImage = Tools::urlAssetsCache("/img/AUCTION_{$emp}_{$auction->cod_sub}.jpg");

    $url_lotes = Tools::url_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions, $auction->reference);
    $url_tiempo_real = Tools::url_real_time_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions);

    $catalogLink = AucSessionsFiles::where([['"auction"', $auction->cod_sub], ['"type"', 5]])->first();
@endphp
<section class="auction-banner container-fluid">
    <div class="row g-0 auction-banner-row">
        <div class="col-12 col-md-5 auction-banner-info-column">
            <div>
                <h1>{{ $auction->name }}</h1>
                <h3>
                    {{ $dateFormat }}
                    <br>
                    A las {{ $tiemeFormat }}
                </h3>

                <a class="btn btn-outline-lb-primary rounded-5" href="{{ $url_lotes }}">
                    {{ trans("$theme-app.subastas.go_to_the_auction") }}
                </a>

				@if ($url_tiempo_real)
					<a class="btn btn-outline-lb-primary rounded-5" href="{{ $url_tiempo_real }}">
						{{ trans("$theme-app.lot.bid_live") }}
					</a>
				@endif

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
