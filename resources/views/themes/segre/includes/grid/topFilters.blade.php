@php
    use App\Models\V5\AucSessions;
    $sessions = collect([]);

    if ($auction) {
        $sessions = AucSessions::query()
            ->select(['"id_auc_sessions"', '"auction"', '"name_lang"', '"name"', '"init_lot"'])
            ->joinLang()
            ->whereAuction($auction->cod_sub)
            ->orderby('"reference"')
            ->get();
    }

@endphp

<div class="top-filters">
    <div class="container-xxxl container-top-filters justify-content-between" data-container-style>
        <div class="filters-sessions d-flex align-items-center">
            <button class="btn btn-link btn-icon px-xxl-4 gap-xxl-4" id="js-show-filters" alt="mostrar filtros"
                onclick="toogleFilters(event)">
                <x-icon.boostrap icon="funnel" size="1.2em" />
                {{-- <x-icon.fontawesome class="d-xxl-none" icon="filter" size="1.2em" /> --}}
                <span class="d-none d-xxl-inline">
                    {{ trans("web.global.filters") }}
                </span>
            </button>


            {{-- @foreach ($sessions as $sesion)
                @php
                    $sesionName = explode('-', $sesion->name);
                    $sesionName = !empty($sesionName[1]) ? trim($sesionName[1]) : $sesionName[0];
					$sesionName = Str::title($sesionName);

                    $urlSession = Tools::url_auction($sesion->auction, $sesion->name, $sesion->id_auc_sessions, '001');
                    #poner esto antes de la página a la que debe ir
                    if (empty($url)) {
                        $url = $urlSession;
                    }

                    #calculamos en que página empieza la sesion
                    $cuantosLotes = App\Models\V5\FgAsigl0::select('count(ref_asigl0) cuantos')
                        ->where('SUB_ASIGL0', $auction->cod_sub)
                        ->where('ref_asigl0', '<', $sesion->init_lot)
                        ->first();

                    $lotsPerPage = request('total', 24);

                    $pagina = intdiv($cuantosLotes->cuantos, $lotsPerPage);

                    #le sumamos 1 por que la página no empieza em 0 si no en 1
                    $pagina += 1;

                    $urlSession .= "?page={$pagina}&total={$lotsPerPage}#{$auction->cod_sub}-{$sesion->init_lot}";
                @endphp

                <div class="filters-session px-xl-3 px-xxl-4 d-none d-lg-block">
                    <a href="{{ $urlSession }}">{{ $sesionName }}</a>
                </div>
            @endforeach --}}
        </div>

        @if (!empty($auction))
            <p class="d-lg-none">{{ Str::title($auction->des_sub) }}</p>
        @endif

		<button class="btn btn-sm btn-link d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" >
			<x-icon.fontawesome icon="magnifying-glass" size="1.2em" />
			{{-- <x-icon.boostrap icon="search" size="1.2em" /> --}}
		</button>

        <div class="offcanvas-lg offcanvas-end filters-orders row row-cols-lg-auto align-items-center gap-3" id="offcanvasSearch"  tabindex="-1" aria-labelledby="offcanvasSearchLabel">

			<div class="offcanvas-header">
				<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasSearch" aria-label="Close"></button>
			</div>

            <div class="col-12 row align-items-center gx-xl-1">
                <label class="w-auto" for="order_selected">
                    {{ trans("$theme-app.lot_list.order") }}
                </label>
                <div class="col">
                    <select class="form-select form-select-sm" id="order_selected">
                        <option value="name" @if ($filters['order'] == 'name') selected @endif>
                            {{ trans($theme . '-app.lot_list.name') }}
                        </option>
                        <option value="price_asc" @if ($filters['order'] == 'price_asc') selected @endif>
                            {{ trans($theme . '-app.lot_list.price_asc') }}
                        </option>
                        <option value="price_desc" @if ($filters['order'] == 'price_desc') selected @endif>
                            {{ trans($theme . '-app.lot_list.price_desc') }}
                        </option>
                        <option value="ref" @if ($filters['order'] == 'ref' || empty($filters['order'])) selected @endif>
                            {{ trans($theme . '-app.lot_list.reference') }}
                        </option>

                        <option value="date_asc" @if ($filters['order'] == 'date_asc') selected @endif>
                            {{ trans($theme . '-app.lot_list.date_asc') }}
                        </option>
                        <option value="date_desc" @if ($filters['order'] == 'date_desc') selected @endif>
                            {{ trans($theme . '-app.lot_list.date_desc') }}
                        </option>
                        <option value="hbids" @if ($filters['order'] == 'hbids') selected @endif>
                            {{ trans($theme . '-app.lot_list.higher_bids') }}
                        </option>
                        <option value="mbids" @if ($filters['order'] == 'mbids') selected @endif>
                            {{ trans($theme . '-app.lot_list.more_bids') }}
                        </option>
                        <option value="lastbids" @if ($filters['order'] == 'lastbids') selected @endif>
                            {{ trans($theme . '-app.lot_list.last_bids') }}
                        </option>

                        @if (!empty($auction) && $auction->tipo_sub == 'O')
                            <option value="ffin" @if ($filters['order'] == 'ffin') selected @endif>
                                <b>
                                    {{ trans($theme . '-app.lot_list.more_near') }}
                                </b>
                            </option>
                        @endif
                    </select>
                </div>
            </div>

            @include('includes.grid.search_list')

        </div>
    </div>
</div>

<div class="d-none">

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
