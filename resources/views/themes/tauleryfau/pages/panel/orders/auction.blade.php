<div class="orders-auction" id="{{ $all_inf['inf']->cod_sub }}">

    <div class="panel-lots_header-wrapper">
        <div class="table-grid table-grid_header orders-auction_header">

            <div class="orders-auction_title">
                <p>
                    {!! str_replace('-', '<br>', $all_inf['inf']->name) !!}
                </p>
            </div>

            <div class="orders-auction-ref">
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
                        {{ trans($theme . '-app.user_panel.awarded') }}
                    @else
                        {{ trans($theme . '-app.user_panel.mi_puja') }}
                    @endif
                </p>
            </div>

            <div>
                <p>{{ trans("$theme-app.user_panel.my_current_bid") }}</p>
            </div>
        </div>
    </div>

    @foreach ($all_inf['lotes'] as $inf_lot)
        @include('pages.panel.orders.lot', [
            'inf_lot' => $inf_lot,
            'subasta_finalizada' => $subasta_finalizada,
        ])
    @endforeach

</div>
