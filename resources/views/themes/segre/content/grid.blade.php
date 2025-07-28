<section>
    @includeWhen(!empty($auction), 'includes.grid.grid-front')
</section>

<section class="section-grid-top-filters">
    @include('includes.grid.topFilters')
</section>

<div class="info-auction-tab-contet">
    <div class="container" data-container-style>

        @if (!empty($auction) && $auction->tipo_sub == App\Models\V5\FgSub::TIPO_SUB_PRESENCIAL)
            <div class="row">
                <div class="col-12 col-lg-9 ms-auto">
					<x-button.next_live_session :codSub="$auction->cod_sub" />
                </div>
            </div>
        @endif

        <div class="row grid-row">
            <div class="col-lg-3 offcanvas-lg offcanvas-start" id="js-filters-col">
                <div class="offcanvas-header">
                    <button class="btn-close" type="button" onclick="toggleOffCanvasFilters()"></button>
                </div>
                <aside class="section-grid-filters sticky-lg-top">
                    @include('includes.grid.leftFilters')
                </aside>
            </div>

            <div class="col-lg-9" id="js-lots-col">

                @if (config('app.paginacion_grid_lotes'))
                    <div class="section-grid-lots">
                        @include('includes.grid.lots')
                    </div>
                @else
                    <div class="section-grid-lots">
                        <div class="section-grid-lots" id="lotsGrid"></div>
                    </div>

                    <div id="endLotList"></div>
                    <div class=" text-center" id="loading">
                        <img src="/default/img/loading.gif" alt="Loading…" />
                    </div>
                @endif

                {{-- El formulari odebe estar fuera para que funcione el ver histórico --}}
                <form id="infiniteScrollForm" autocomplete="off">
                    {{ csrf_field() }}
                    @foreach ($filters as $nameFilter => $valueFilter)
                        @if (is_array($valueFilter))
                            @foreach ($valueFilter as $kFilter => $vFilter)
                                @if (is_array($vFilter))
                                    @foreach ($vFilter as $valuesMultipleFilter)
                                        <input name="{{ $nameFilter }}[{{ $kFilter }}][]" type="hidden"
                                            value="{{ $valuesMultipleFilter }}">
                                    @endforeach
                                @else
                                    <input name="{{ $nameFilter }}[{{ $kFilter }}]" type="hidden"
                                        value="{{ $vFilter }}">
                                @endif
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

        @if (config('app.paginacion_grid_lotes'))
            <div class="row">
                <div class="col-lg-9 ms-auto">
                    <div class="section-grid-pagination pagination-wrapper">
                        {{-- {{ $paginator->links() }} --}}
                        {{ $paginator->links('front::includes.grid.paginator_pers') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if (!isset($auction) && request('page', 1) == 1)
    <div class="home_text">
        <div class="container">
            {!! $seo_data->meta_content !!}
            {{-- Solo debe aparecer si hay categioria, en el moment oque ha seccion seleccionada no debe aparecer --}}
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
    var url_lots = "{{ route('getAjaxLots', ['lang' => config('app.locale')]) }}";
</script>

@if (empty(\Config::get('app.paginacion_grid_lotes')))
    <script src="{{ Tools::urlAssetsCache('/js/default/grid_scroll.js') }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache('/js/default/grid_filters.js') }}"></script>
