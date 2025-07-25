@php
    use App\Models\V5\AucSessionsFiles;

    $catalogs = AucSessionsFiles::query()->whereAuction($auction->cod_sub)->isLink()->get();

    $auctionImage = Tools::auctionImage($auction->cod_sub);
@endphp
<div class="grid-front-page container-fluid">
    <img class="grid-front-image" src="{{ $auctionImage }}" alt="">
    <div class="grid-front-page-content">
        <div class="text-center text-lg-start">
            <div class="grid-front-info">

                <p class="d-lg-none">Cátalogos</p>
                <h1>{{ $auction->des_sub }}</h1>

                <div class="d-none d-lg-inline-block">
                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>
        <div class="grid-front-catalogs gap-1 gap-lg-0">
            @foreach ($catalogs as $catalog)
                @php
                    $image = str_replace('\\', '/', $catalog->img);
                @endphp
                <a href="{{ $catalog->url }}" target="_blank">
                    <div class="grid-front-catalog">
                        <img src="{{ $image }}" alt="">
                        <div class="catalog-overlay">
                            <button class="btn btn-pill btn-outline-lb-secondary w-100">
                                {{ trans("web.lot_list.view_catalog") }}
                            </button>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
