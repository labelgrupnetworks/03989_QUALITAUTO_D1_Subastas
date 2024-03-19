@php
    $withExpo = $auction && $codSub;
@endphp


<div id="lots">
    <div class="row">
        <div @class([
            'col-xs-12 col-md-3 auction-lots-view',
            'auction-lots-view-expo' => $withExpo,
        ])>
            @include('includes.grid.leftFilters')

        </div>
        <div class="col-xs-12 col-md-9">

			<div class="top-filters">
            	@include('includes.grid.topFilters')
			</div>

            @if (\Config::get('app.paginacion_grid_lotes'))
                <div class="lots-grid">
                    @include('includes.grid.lots')
                </div>

                <div class="foot-pagination-grid">
                    {{ $paginator->links('front::includes.grid.paginator_pers') }}
                </div>
            @else
                <div class="clearfix"></div>

                <div class="col-xs-12 p-0">
                    <div class="lots-grid" id="lotsGrid">
                    </div>
                </div>

                {{-- Código scroll infinito --}}

                <div class="clearfix"></div>
                <div id="endLotList"></div>
                <div class=" text-center" id="loading">
                    <img src="/default/img/loading.gif" alt="Loading…" />

                </div>

                {{-- Fin código de scroll infinito --}}
            @endif
            {{-- El formulari odebe estar fuera para que funcione el ver histórico --}}
            <form id="infiniteScrollForm" autocomplete="off">
                {{ csrf_field() }}
                @foreach ($filters as $nameFilter => $valueFilter)
                    @if (is_array($valueFilter))
                        @foreach ($valueFilter as $kFilter => $vFilter)
                            <input name="{{ $nameFilter }}[{{ $kFilter }}]" type="hidden"
                                value="{{ $vFilter }}">
                        @endforeach
                    @else
                        <input name="{{ $nameFilter }}" type="hidden" value="{{ $valueFilter }}">
                    @endif
                @endforeach
                <input id="actualPage" name="actualPage" type="hidden" value="1">

                <input name="codSub" type="hidden" value="{{ $codSub }}">
                <input name="refSession" type="hidden" value="{{ $refSession }}">

                <input name="historic" type="hidden" value="{{ request('historic') }}">

                {{-- Página que buscamos en este momento --}}
                <input id="searchingPage" type="hidden" value="0">
                <input id="lastLot" type="hidden" value="false">
            </form>
        </div>
    </div>
</div>


@if (!isset($auction) && (!isset($_GET['page']) || $_GET['page'] == 1))
    <div class="home_text">
        <div class="container">
            {!! $seo_data->meta_content !!}
            <?php
            #Solo debe aparecer si hay categioria, en el moment oque ha seccion seleccionada no debe aparecer
            ?>
            @if (empty($filters['section']))
                <div class="links-sections">
                    @foreach ($sections as $sec)
                        <a class="mr-2"
                            href="{{ route('section', ['keycategory' => $infoOrtsec->key_ortsec0, 'keysection' => $sec['key_sec'] ?? ' ']) }}">{{ ucfirst($sec['des_sec']) }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endif


<script>
    var url_lots = "{{ route('getAjaxLots', ['lang' => \Config::get('app.locale')]) }}";
</script>

@if (empty(\Config::get('app.paginacion_grid_lotes')))
    <script src="{{ Tools::urlAssetsCache('/js/default/grid_scroll.js') }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache('/js/default/grid_filters.js') }}"></script>
