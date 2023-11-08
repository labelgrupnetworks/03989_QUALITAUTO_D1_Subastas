@php
	use App\Models\V5\AucSessionsFiles;
    //eliminamos las sesiones
    $historicas = collect($data['auction_list'])
        ->unique('cod_sub')
        ->values();

    $historicasPagination = $historicas
        ->when(request()->has('search'), function ($collection) {
            return $collection->filter(function ($auction) {
                return collect($auction)->contains(request('search'));
            });
        })
        ->when(request('order_dir'), function ($collection, $order) {
            return $order === 'asc' ? $collection : $collection->reverse();
        })
        ->paginate(12, request('page'), []);
@endphp

<main class="grid-history-auction">
    <h1 class="ff-highlight grid-page-tile">
        {{ trans("$theme-app.artist.passAuctions") }}
    </h1>

    <section class="container">
        <form class="top-filters-wrapper justify-content-between py-3">

            <input type="hidden" name="page" value="{{ request('page') }}">

            @include('includes.components.order')

            @include('includes.components.search')

        </form>

        @if ($historicasPagination->isEmpty())
            <h2 class="text-center py-5">{{ trans(\Config::get('app.theme') . '-app.lot_list.no_results') }}</h2>
        @else
            <div class="row row-cols-1 row-cols-lg-3 gx-0 gy-5 gx-lg-5">
                @foreach ($historicasPagination as $auction)
                    @php
                        $image = Tools::url_img_auction('subasta_large', $auction->cod_sub);
                        $isOldest = date('Y', strtotime($auction->session_start)) < 2022;
                        if ($isOldest) {
                            $url = '/catalogos/' . $auction->cod_sub;
                        } else {
                            $url = Tools::url_auction($auction->cod_sub, $auction->name, $auction->id_auc_sessions, $auction->reference);
                        }

						$catalogUrl = "/catalogos/{$auction->cod_sub}";
						if($auction->tipo_sub == 'O'){

							$auctionFile = AucSessionsFiles::where([
								['"auction"', $auction->cod_sub],
								['"type"', 1]
							])->first();

							if($auctionFile){
								$catalogUrl = $auctionFile->publicFilePath;
							}
						}
                    @endphp
                    <div class="col">
                        <article class="card auction-card">

                            <img class="card-img-top" src="{{ $image }}" alt="" height="378"
                                width="485" loading="lazy">

                            <div class="card-body">
                                <div>
                                    <p class="ff-highlight card-title">{{ trans("$theme-app.subastas.auctions") }}
                                        {{ $auction->cod_sub }}</p>
                                    <p class="card-text">{{ $auction->des_sub }}</p>
                                </div>
                                <a href="{{ $url }}" @if ($isOldest) target="_blank" @endif
                                    class="stretched-link"></a>
                                <a href="{{ $catalogUrl }}" target="_blank"
                                    class="btn btn-outline-lb-primary h-auto" style="z-index: 1">
                                    {{ trans("$theme-app.lot_list.ver_catalogo") }}
                                </a>
                            </div>

                        </article>
                    </div>
                @endforeach
            </div>
            <div class="pagination-wrapper">
                {!! $historicasPagination->appends(Request::query())->links('front::includes.grid.paginator_pers') !!}
            </div>
        @endif
    </section>
</main>
