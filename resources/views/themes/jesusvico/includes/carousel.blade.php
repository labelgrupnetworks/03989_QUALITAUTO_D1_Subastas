@php
    //para los reamates destacados de la home
    $url = Tools::url_lot($bann->sub_asigl0, $bann->id_auc_sessions, $bann->name, $bann->ref_asigl0, $bann->num_hces1, $bann->webfriend_hces1, $bann->titulo_hces1);
    $img = Tools::url_img('lote_medium', $bann->num_hces1, $bann->lin_hces1);
    $title = $bann->ref_asigl0 . '-' . $bann->descweb_hces1;
    $auction = $bann->sub_asigl0;
    $startingPrice = $bann->impsalhces_asigl0;
    $awardPrice = Tools::moneyFormat($bann->max_puja ?? 0, trans("$theme-app.subastas.euros"), 0);
@endphp

<div class="card lot-card">

    <a href="{{ $url }}">
        <img class="card-img-top" src="{{ $img }}" alt="{{ $title }}">
    </a>

    <div class="card-body">

        <h2>
			{{ trans("$theme-app.reports.auction_name") }} {{ $auction }}
            {{-- {{ trans("$theme-app.lot.lot-name") }} {{ $bann->ref_asigl0 }} --}}
        </h2>

        <h5 class="card-title max-line-2">{!! trans("$theme-app.lot.lot-name") . " " .strip_tags($title) !!}</h5>

        <div class="lot-prices">
            <h4 class="lot-salida-price text-lb-gray">

                <span>
                    {{ trans("$theme-app.lot.lot-price") }}
                </span>

                <span>{{ $startingPrice }} {{ trans("$theme-app.subastas.euros") }}</span>

            </h4>

            <h4 class="lot-buy-to">
                <span>{{ trans(\Config::get('app.theme') . '-app.subastas.buy_to') }}</span>
                <span>{{ $awardPrice }}</span>
            </h4>
        </div>

    </div>

</div>
