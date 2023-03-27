@php
    $imageSrc = "/themes/$theme/assets/img/remates/$lot->id.webp";
@endphp

<div class="card lot-card static-carousel-card">

    <img class="card-img-top" src="{{ $imageSrc }}" alt="{{ $lot->title }}" loading="lazy">

    <div class="card-body">

        <h5 class="card-title max-line-2 text-center">{{ $lot->title }}</h5>

        <div class="lot-prices">
            <h4 class="lot-salida-price text-lb-gray">
                <span>
                    {{ trans("$theme-app.lot.lot-price") }}
                </span>

                <span>{{ Tools::moneyFormat($lot->startingPrice, trans("$theme-app.subastas.euros")) }}</span>
            </h4>

            <h4 class="lot-buy-to">
                <span>{{ trans(\Config::get('app.theme') . '-app.subastas.buy_to') }}</span>
                <span>{{ Tools::moneyFormat($lot->awardPrice, trans("$theme-app.subastas.euros")) }}</span>
            </h4>
        </div>

    </div>

</div>
