@php
    use App\Models\V5\FgSub;
    $xmasUrl = config('app.locale') == 'es' ? '/es/subasta' : '/en/auction';
    $mustShowXmas = now()->lessThanOrEqualTo('2025-01-06 00:00:00');

    $existOnlineAuction = FgSub::query()
        ->where('subc_sub', App\Models\V5\FgSub::SUBC_SUB_ACTIVO)
        ->onlineAuctions()
        ->exists();
@endphp

<div class="section-nav d-flex">
    <ul class="ff-highlight">

        @if ($mustShowXmas)
            <li>
                <a class="btn btn-outline-lb-primary btn-xmas"
                    href="{{ "$xmasUrl/joyas-y-relojes_442-003?purchasable=1&noAward=1" }}">
                    Xmas Gifts
                </a>
            </li>
        @endif


        <li>
            <a href="{{ route('subasta.actual') }}">
                {{ trans("$theme-app.subastas.current_auction") }}
            </a>
        </li>


        @if ($existOnlineAuction)
            <li >
                <a href="{{ route('subasta.actual-online') }}" >
                    {!! trans("$theme-app.foot.online_auction")."<br>ARTE AFRICANO" !!}
                </a>
            </li>
        @endif

        <li>
            <a href="{{ route('custom.ventas-destacadas') }}">
                {{ trans("$theme-app.lot_list.featured-sales") }}
            </a>
        </li>
        <li>
            <a href="{{ route('subastas.historicas') }}">
                {{ trans("$theme-app.subastas.previous_auctions") }}
            </a>
        </li>
        <li>
            <a href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.buy_and_sell") }}">
                {{ trans("$theme-app.foot.buy_and_sell") }}
            </a>
        </li>
    </ul>
</div>
