@php
    use App\Models\V5\AucSessionsFiles;
    use App\Services\Auction\AuctionService;
    use Carbon\Carbon;

    $emp = Config::get('app.emp');
    $locale = Config::get('app.locale');
	$auctionName = $auctions->first()->name ?? '';

    $auctionService = new AuctionService();
@endphp

<section class="auction-banner container-fluid">
    <div class="row g-0 auction-banner-row">
        <div class="col-12 col-md-5 auction-banner-info-column">

            <div class="">
                <h1>{{ $auctionName }}</h1>
                @foreach ($auctions as $auction)
                    @php
                        $auctionDate = Carbon::parse($auction->session_start)->locale($locale);

                        $dateFormat = $auctionDate->isoFormat('LL');
                        $tiemeFormat = $auctionDate->isoFormat('H:mm \h');

                        $auctionImage = Tools::urlAssetsCache("/img/AUCTION_{$emp}_{$auction->cod_sub}.jpg");

                        $gridUrl = $auctionService->addUrlPageSession(route('subasta.actual'), $auction->init_lot, $auction->cod_sub);

                        $url_tiempo_real = Tools::url_real_time_auction(
                            $auction->cod_sub,
                            $auction->name,
                            $auction->id_auc_sessions,
                        );

                        $catalogLink = AucSessionsFiles::where([
                            ['"auction"', $auction->cod_sub],
                            ['"type"', 5],
                        ])->first();
                    @endphp
                    <div>
                        <h3>
                            {{ $dateFormat }}
                            <br>
                            A las {{ $tiemeFormat }}
                        </h3>

                        <div class="d-flex gap-2 flex-wrap justify-content-center">
                            <a class="btn btn-outline-lb-primary rounded-5" href="{{ $gridUrl }}">
                                {{ trans("$theme-app.subastas.go_to_the_auction") }}
                            </a>

                            @if ($url_tiempo_real)
                                <a class="btn btn-outline-lb-primary rounded-5" href="{{ $url_tiempo_real }}">
                                    {{ trans("$theme-app.lot.bid_live") }}
                                </a>
                            @endif

                            @if ($catalogLink)
                                <a class="btn btn-outline-lb-primary rounded-5" href="{{ $catalogLink->url }}"
                                    target="_blank">
                                    {{ trans("$theme-app.subastas.see_catalogue") }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
        <div class="col-12 col-md-7">
            <img src="{{ $auctionImage }}" alt="">
        </div>
    </div>
</section>
