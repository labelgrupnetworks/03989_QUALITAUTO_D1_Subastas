@php
    $activeAuction = collect($data['auction_list'])
        ->where('subc_sub', App\Models\V5\FgSub::SUBC_SUB_ACTIVO)
        ->first();

    $sessions = App\Models\V5\AucSessions::select('"id_auc_sessions","auction","reference", nvl("name_lang","name") name, "start", "end", "init_lot", "end_lot"')
        ->joinLang()
        ->where('"auction"', $activeAuction->cod_sub)
        ->orderby('"reference"')
        ->get();

	$isFirstSessionEnded = $sessions->where('end', '<', now())->isNotEmpty();
    $emp = config('app.emp');

    //$auctionImage = Tools::url_img_auction('subasta_large', $activeAuction->cod_sub);
    $auctionImage = Tools::urlAssetsCache("/img/AUCTION_{$emp}_{$activeAuction->cod_sub}.jpg");
    $liveSession = $sessions->where('end', '>', now())->first();

    use App\Models\V5\FgAsigl0;
    $prominentSalesLots = (new FgAsigl0())->ventasDestacadas('orden_destacado_asigl0', 'asc', 0, 3);

    $histoyAuctions = App\Models\V5\FgSub::select('cod_sub')
        ->historicSub()
        ->joinLangSub()
        ->orderBy('dfec_sub', 'desc')
        ->limit(3)
        ->get();
@endphp

<section class="next-auction-wrapper">
    <div class="next-auction-info text-center">

        <div>
            <p class="fw-semibold ls-2">{{ $activeAuction->des_sub }}</p>
            <h1 class="ff-highlight next-auction-title">{{ trans("$theme-app.subastas.inf_subasta_subasta") }}
                {{ $activeAuction->cod_sub }}</h1>
        </div>

        <div class="next-auction-links">
            <a class="btn btn-outline-lb-primary btn-medium"
                href="{{ route('subasta.actual') }}">{{ trans("$theme-app.lot_list.go_to_auction") }}</a>
            @if ($liveSession)
                <a class="btn btn-outline-lb-primary btn-medium" target="_blank"
                    href="{{ Tools::url_real_time_auction($liveSession->auction, $liveSession->name, $liveSession->id_auc_sessions) }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="#ED2F2F" />
                    </svg>
                    {{ trans("$theme-app.lot_list.bid_live") }}
                </a>
            @endif
        </div>

        <div class="next-auction-catalog">
            <img src="/catalogos/{{ $activeAuction->cod_sub }}/files/assets/cover300.jpg" alt="imagén de catálogo" width="160" height="190">
            <a href="/catalogos/{{ $activeAuction->cod_sub }}" class="next-auction-catalog-link">
                {{ trans("$theme-app.lot_list.ver_catalogo") }}
            </a>
        </div>

    </div>
    <div class="next-auction-image">
        <img src="{{ $auctionImage }}" alt="Portada de la subasta" height="758" width="950">
    </div>
</section>

<section class="container landing-section">
    <h2 class="landing-section-title ff-highlight">{{ trans("$theme-app.lot_list.featured-sales") }}</h2>
    <p class="landing-section-description">{{ trans("$theme-app.subastas.featured_sales_desc") }}</p>
    <div class="section-grid-lots row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-4">
        @foreach ($prominentSalesLots as $lot)
            @php
                $titulo = trans("$theme-app.subastas.auctions") . ' ' . $lot->sub_asigl0;
				$url = Tools::url_lot($lot->sub_asigl0, $lot->auc_session, $lot->name, $lot->ref_asigl0, $lot->num_hces1, $lot->webfriend_hces1, $lot->titulo_hces1)
            @endphp

            @include('includes.grid.lot_venta_destacada')
        @endforeach
    </div>

    <a href="/{{ Routing::slugSeo('ventas-destacadas') }}"
        class="btn btn-outline-lb-primary btn-medium">{{ trans("$theme-app.global.see_more") }}</a>
</section>

<section class="container landing-section">
    <h2 class="landing-section-title ff-highlight">{{ trans("$theme-app.artist.passAuctions") }}</h2>
    <p class="landing-section-description">{{ trans("$theme-app.subastas.previous_auctions_desc") }}</p>
    <div class="row row-cols-1 row-cols-lg-3 gx-0 gx-lg-5 gy-4">
        @foreach ($histoyAuctions as $auction)
            @php
                $image = Tools::urlAssetsCache("/img/AUCTION_{$emp}_{$auction->cod_sub}.jpg");
                if (date('Y', strtotime($auction->session_start)) < 2022) {
                    $url = '/catalogos/' . $auction->cod_sub;
                } else {
                    $url = Tools::url_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions, $auction->reference);
                }
            @endphp
            <div class="col">
                <article class="card auction-card">
                    <img class="card-img-top" src="{{ $image }}" alt="">
                    <div class="card-body">
                        <div>
                            <p class="ff-highlight card-title">{{ trans("$theme-app.subastas.auctions") }}
                                {{ $auction->cod_sub }}</p>
                            <p class="card-text">{{ $auction->des_sub }}</p>
                        </div>
						<a href="{{ $url }}" class="stretched-link"></a>
                        <button class="btn btn-outline-lb-primary h-auto">
                            {{ trans("$theme-app.lot_list.ver_catalogo") }}
                        </button>
                    </div>
                    <a href=""></a>
                </article>
            </div>
        @endforeach
    </div>
    <a href="{{ route('subastas.historicas') }}"
        class="btn btn-outline-lb-primary btn-medium">{{ trans("$theme-app.global.see_more") }}</a>

</section>

<section class="how-to-buy-section">
    <svg class="mb-3" width="62" height="60" viewBox="0 0 62 60" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#a)" fill="#0F0E0D">
            <path
                d="M55.093 51.069a4.5 4.5 0 0 1-4.495-4.496 4.5 4.5 0 0 1 4.495-4.495 4.5 4.5 0 0 1 4.495 4.495 4.5 4.5 0 0 1-4.495 4.496Zm0-7.13a2.637 2.637 0 0 0-2.634 2.634 2.637 2.637 0 0 0 2.634 2.635 2.637 2.637 0 0 0 2.634-2.635 2.637 2.637 0 0 0-2.634-2.634Zm-16.672 7.13a4.5 4.5 0 0 1-4.495-4.496 4.5 4.5 0 0 1 4.495-4.495 4.5 4.5 0 0 1 4.495 4.495 4.5 4.5 0 0 1-4.495 4.496Zm0-7.13a2.637 2.637 0 0 0-2.634 2.634 2.637 2.637 0 0 0 2.634 2.635 2.637 2.637 0 0 0 2.634-2.635 2.637 2.637 0 0 0-2.634-2.634Z" />
            <path
                d="M44.033 59.995a.93.93 0 0 1-.93-.93v-2.952c0-2.581-2.1-4.682-4.682-4.682a4.688 4.688 0 0 0-4.681 4.682v2.952a.93.93 0 0 1-1.861 0v-2.952a6.55 6.55 0 0 1 6.542-6.543 6.55 6.55 0 0 1 6.543 6.543v2.952a.93.93 0 0 1-.93.93Zm-20.671-8.926a4.5 4.5 0 0 1-4.495-4.496 4.5 4.5 0 0 1 4.495-4.495 4.5 4.5 0 0 1 4.496 4.495 4.5 4.5 0 0 1-4.496 4.496Zm0-7.13a2.637 2.637 0 0 0-2.634 2.634 2.637 2.637 0 0 0 2.634 2.635 2.637 2.637 0 0 0 2.635-2.635 2.637 2.637 0 0 0-2.635-2.634Z" />
            <path
                d="M28.97 59.995a.93.93 0 0 1-.93-.93v-2.952c0-2.581-2.1-4.682-4.681-4.682a4.689 4.689 0 0 0-4.682 4.682v2.952a.93.93 0 0 1-1.86 0v-2.952a6.55 6.55 0 0 1 6.542-6.543 6.55 6.55 0 0 1 6.542 6.543v2.952a.93.93 0 0 1-.93.93Zm31.74.009a.93.93 0 0 1-.93-.93v-2.952a4.688 4.688 0 0 0-4.682-4.682 4.77 4.77 0 0 0-1.965.413 2.971 2.971 0 0 1-3.406-.673l-1.43-1.544a.93.93 0 0 1-.249-.633v-6.372a.6.6 0 0 0-1.199 0v6.838c0 .294.084.58.242.826l3.18 4.972c.095.15.145.324.145.501v3.303a.93.93 0 0 1-1.86 0v-3.03l-3.033-4.742a3.39 3.39 0 0 1-.535-1.829v-6.838a2.463 2.463 0 0 1 2.46-2.46 2.464 2.464 0 0 1 2.461 2.46v6.008l1.183 1.276a1.11 1.11 0 0 0 1.275.241 6.6 6.6 0 0 1 2.73-.578 6.55 6.55 0 0 1 6.542 6.543v2.951a.93.93 0 0 1-.93.931ZM47.45 37.237a3.536 3.536 0 0 1-3.532-3.533 3.536 3.536 0 0 1 3.532-3.532 3.536 3.536 0 0 1 3.533 3.532 3.536 3.536 0 0 1-3.533 3.533Zm0-5.204a1.672 1.672 0 0 0 0 3.343 1.673 1.673 0 0 0 0-3.343Z" />
            <path
                d="M47.454 42.024a.93.93 0 0 1-.93-.93v-4.796a.93.93 0 0 1 1.86 0v4.795a.93.93 0 0 1-.93.93ZM6.21 51.092a4.292 4.292 0 0 1-4.288-4.288 4.292 4.292 0 0 1 4.288-4.288 4.292 4.292 0 0 1 4.288 4.288 4.292 4.292 0 0 1-4.288 4.288Zm0-6.715a2.43 2.43 0 0 0-2.427 2.427A2.43 2.43 0 0 0 6.21 49.23a2.43 2.43 0 0 0 2.427-2.426 2.43 2.43 0 0 0-2.427-2.427Z" />
            <path
                d="M12.146 59.995a.93.93 0 0 1-.93-.93V55.76c0-.177.05-.35.146-.5l3.18-4.973c.148-.232.23-.498.24-.773l-.972-7.402a.596.596 0 0 0-.702-.442.597.597 0 0 0-.468.708l.01.053.925 6.43a.929.929 0 0 1-.239.765l-1.43 1.544a2.971 2.971 0 0 1-3.406.673 4.755 4.755 0 0 0-1.965-.413 4.687 4.687 0 0 0-4.682 4.681v2.952a.93.93 0 0 1-1.86 0v-2.952a6.55 6.55 0 0 1 6.542-6.542c.961 0 1.88.195 2.73.578.443.2.956.103 1.275-.242l1.129-1.218-.857-5.967a2.447 2.447 0 0 1 .363-1.819 2.445 2.445 0 0 1 1.56-1.055 2.444 2.444 0 0 1 1.85.358c.548.362.923.917 1.054 1.56.005.022.008.044.011.065l.987 7.51c.005.039.007.08.007.12 0 .651-.185 1.282-.534 1.83l-3.033 4.743v3.03a.93.93 0 0 1-.93.93v.002Zm-.708-22.953a3.538 3.538 0 0 1-3.46-2.826 3.51 3.51 0 0 1 .514-2.654 3.509 3.509 0 0 1 2.24-1.513 3.535 3.535 0 0 1 4.168 2.753 3.51 3.51 0 0 1-.514 2.654 3.509 3.509 0 0 1-2.24 1.514 3.547 3.547 0 0 1-.708.072Zm.005-5.204a1.661 1.661 0 0 0-1.397.75 1.662 1.662 0 0 0-.243 1.256 1.669 1.669 0 0 0 3.031.587c.245-.372.332-.819.243-1.256a1.66 1.66 0 0 0-.716-1.06 1.663 1.663 0 0 0-.918-.276v-.001Z" />
            <path
                d="M12.921 41.692a.931.931 0 0 1-.91-.745l-.96-4.697a.93.93 0 1 1 1.823-.372l.96 4.697a.93.93 0 0 1-.913 1.117ZM31.77 14.8a4.006 4.006 0 0 1-4-4.002 4.006 4.006 0 0 1 4-4.001 4.006 4.006 0 0 1 4.002 4.001 4.006 4.006 0 0 1-4.001 4.002Zm0-6.143c-1.179 0-2.14.96-2.14 2.14 0 1.18.961 2.14 2.14 2.14 1.18 0 2.141-.96 2.141-2.14 0-1.18-.96-2.14-2.14-2.14Z" />
            <path
                d="M36.117 26.345a.93.93 0 0 1-.93-.93v-5.961a.93.93 0 0 1 .482-.815l4.96-2.727 1.862-6.146a.66.66 0 0 0-.066-.533.664.664 0 0 0-1.199.13l-1.663 4.993a.933.933 0 0 1-.572.583l-3.846 1.365a.932.932 0 0 1-.623-1.755l3.418-1.213 1.521-4.563a2.529 2.529 0 0 1 2.89-1.677 2.526 2.526 0 0 1 1.92 3.207L42.3 16.811a.938.938 0 0 1-.442.546l-4.81 2.645v5.41a.93.93 0 0 1-.93.93v.003Z" />
            <path
                d="M42.44 31.306H19.263a.93.93 0 0 1-.931-.93v-4.969a.93.93 0 0 1 .93-.93H42.44a.93.93 0 0 1 .93.93v4.968a.93.93 0 0 1-.93.93Zm-22.247-1.861H41.51v-3.107H20.193v3.107Z" />
            <path
                d="M22 40.73a.93.93 0 0 1-.924-.833l-.993-9.424a.932.932 0 0 1 .925-1.028H40.69a.928.928 0 0 1 .924 1.028l-.992 9.424a.929.929 0 0 1-1.024.828.929.929 0 0 1-.828-1.022l.885-8.397H22.04l.885 8.397A.93.93 0 0 1 22 40.73Zm4.485-14.507a.93.93 0 0 1-.93-.93v-6.934a4.26 4.26 0 0 1 2.012-3.632l.288-.18a.93.93 0 0 1 .986 1.578l-.287.18a2.408 2.408 0 0 0-1.138 2.053v6.933a.93.93 0 0 1-.93.93v.002Zm9.679-18.966a.93.93 0 0 1-.68-.295L33.45 4.786a.934.934 0 0 1 .045-1.317L36.94.251a.93.93 0 0 1 1.315.045l2.033 2.176a.935.935 0 0 1-.044 1.316l-3.446 3.219a.928.928 0 0 1-.635.25Zm-.719-3.062.762.817 2.086-1.948-.762-.817-2.086 1.948Z" />
            <path
                d="M41.02 9.001a.925.925 0 0 1-.68-.295l-2.796-2.992a.93.93 0 1 1 1.36-1.27l2.795 2.991a.93.93 0 0 1-.68 1.566Z" />
        </g>
        <defs>
            <clipPath id="a">
                <path fill="#fff" d="M0 0h61.644v60H0z" />
            </clipPath>
        </defs>
    </svg>
    <h2 class="landing-section-title ff-highlight mb-5">{{ trans("$theme-app.foot.buy_and_sell") }}</h2>

    <a href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.buy_and_sell") }}"
        class="btn btn-outline-lb-primary btn-medium">{{ trans("$theme-app.subastas.know_more") }}</a>
</section>


@if($isFirstSessionEnded && !Session::has('user'))
<script>
	window.setTimeout(showRematesModal, 3500);
</script>
@endif
