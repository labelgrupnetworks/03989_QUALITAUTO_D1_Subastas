<div class="orders-auction" id="{{ $all_inf['inf']->cod_sub }}">

    <div class="table-grid table-grid_header orders-auction_header">
        <div class="orders-auction_title">
            <p>
                {{ $all_inf['inf']->name }}
            </p>
        </div>

        <div>
            <p>{{ trans($theme . '-app.user_panel.lot') }}</p>
        </div>

        <div>
            <p>{{ trans($theme . '-app.lot.description') }}</p>
        </div>

        <div>
            <p>{{ trans($theme . '-app.lot.lot-price') }}</p>
        </div>

        <div>
            <p>
                @if ($subasta_finalizada)
                    {{ trans($theme . '-app.user_panel.award_price') }}
                @else
                    {{ trans($theme . '-app.lot.puja_actual') }}
                @endif
            </p>
        </div>

        <div>
            <p>Mi puja actual</p>
        </div>
    </div>

    @foreach ($all_inf['lotes'] as $inf_lot)
        @include('pages.panel.orders.lot', [
            'inf_lot' => $inf_lot,
            'subasta_finalizada' => $subasta_finalizada,
        ])
    @endforeach

</div>
