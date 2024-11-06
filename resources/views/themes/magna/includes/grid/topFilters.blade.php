@php
    $count_lots = 0;
    foreach ($tipos_sub as $typeSub => $desType) {
        $numLots = Tools::showNumLots($numActiveFilters, $filters, 'typeSub', $typeSub);

        if (empty($filters['typeSub'])) {
            $count_lots += $numLots;
        } elseif ($typeSub == $filters['typeSub']) {
            $count_lots = $numLots;
        }
    }
@endphp

<div class="top-filters-selects row row-cols-md-4 gy-2 align-content-end mb-2">

    <div>
        <select class="form-select form-select-sm" id="order_selected">
            <option value="name" @if ($filters['order'] == 'name') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.name') }}
            </option>
            <option value="price_asc" @if ($filters['order'] == 'price_asc') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.price_asc') }}
            </option>
            <option value="price_desc" @if ($filters['order'] == 'price_desc') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.price_desc') }}
            </option>
            <option value="ref" @if ($filters['order'] == 'ref' || empty($filters['order'])) selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.reference') }}
            </option>

            <option value="date_asc" @if ($filters['order'] == 'date_asc') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.date_asc') }}
            </option>
            <option value="date_desc" @if ($filters['order'] == 'date_desc') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.date_desc') }}
            </option>
            <option value="hbids" @if ($filters['order'] == 'hbids') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.higher_bids') }}
            </option>
            <option value="mbids" @if ($filters['order'] == 'mbids') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.more_bids') }}
            </option>
            <option value="lastbids" @if ($filters['order'] == 'lastbids') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.last_bids') }}
            </option>

            @if (!empty($auction) && $auction->tipo_sub == 'O')
                <option value="ffin" @if ($filters['order'] == 'ffin') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}: <b> {{ trans($theme . '-app.lot_list.more_near') }}
                    </b>
                </option>
            @endif
        </select>
    </div>

    <div class="">
        <select class="form-select form-select-sm" id="total_selected">
            @foreach (\Config::get('app.filter_total_shown_options') as $numLots)
                <option value="{{ $numLots }}" @if (request('total') == $numLots) selected @endif>
                    {{ trans($theme . '-app.lot_list.see_num_lots', ['num' => $numLots]) }} </option>
            @endforeach
        </select>
    </div>
    <div class="ms-auto">
        <button class="btn btn-icon btn-outline-lb-primary justify-content-between w-100">
            Como pujar
            <x-icon.boostrap icon="info-circle-fill" size="16" />
        </button>
    </div>

</div>

<div class="top-filters-info">

    <p class="cantidad-res">
        {{ Tools::numberformat($count_lots) }} {{ trans($theme . '-app.lot_list.results') }}
    </p>

    @if (config('app.paginacion_grid_lotes'))
        <div class="pagination-wrapper">
            {{ $paginator->links() }}
        </div>
    @endif

</div>

<div class="top-filters-labels">
	@include('includes.grid.badges_section')
</div>

<div class="col-xs-12 pt-1 d-flex align-items-center mt-1">

    {{-- FILTRO DE SUBASTAS HISTÓRICAS --}}
    @if (\Config::get('app.gridHistoricoVentas'))
        @php
            /**
             * estará oculto a no ser que haya lotes en el historico
             * @todo seeHistoricLots_JS modificar clases para d-none d-block
             * */
        @endphp

        <span class="gridFilterHistoric d-none" id="seeHistoricLots_JS">
            {!! trans($theme . '-app.lot_list.see_historic_lots') !!}
        </span>

        @if (request('historic'))
            <span class="gridFilterHistoric" id="seeActiveLots_JS">
                {{ trans($theme . '-app.lot_list.return_active_lots') }}
            </span>
            {{-- solo haremos la llamada si estamos en categorias y han buscado texto   && !empty(request('description') --}}
        @elseif(empty($auction))
            <script>
                $(function() {
                    showHistoricLink();
                })
            </script>
        @endif
    @endif

    {{-- FIN FILTRO DE SUBASTAS HISTÓRICAS --}}
</div>
