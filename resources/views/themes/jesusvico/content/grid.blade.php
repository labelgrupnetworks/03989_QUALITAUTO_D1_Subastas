@php
    use App\Models\Cookies;
    $styleLotSeeConfiguration = (new Cookies())->getLotConfiguration();

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

<div class="info-auction-tab-contet">
    <div class="container">

        <div class="row">
            <div class="col-lg-3" id="js-filters-col">
                <aside class="section-grid-filters sticky-lg-top">
                    @include('includes.grid.leftFilters')
                </aside>
            </div>

            <div class="col-lg-9" id="js-lots-col">

                <div class="section-grid-top-filters">
                    @include('includes.grid.topFilters')
                </div>

                <div class="banner-lotes-container mb-2">
                    {!! \BannerLib::bannersPorKey(
                        'banner_lotes',
                        'banner_lotes',
                        $options = ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false],
                    ) !!}
                </div>

                @if (config('app.paginacion_grid_lotes'))
                    <div class="pagination-wrapper">
						<div class="row">
							<div class="col-12 col-lg-6">
								<div class="mb-2">
									@include('includes.grid.badges_section')
								</div>
							</div>
							<div class="col-12 col-lg-6">
								<div class="nav-grid-pages">
									{{ $paginator->links() }}
								</div>
							</div>
						</div>
                    </div>

                    <div class="section-grid-lots mb-2 {{ $styleLotSeeConfiguration }}">
                        @include('includes.grid.lots')
                    </div>

                    <div class="section-grid-pagination pagination-wrapper bottom-pagination">
                        {{ $paginator->links() }}
                    </div>
                @else
                    <div class="section-grid-lots {{ $styleLotSeeConfiguration }}">
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
</div>

@if (!isset($auction) && request('page', 1) == 1)
    <div class="home_text">
        <div class="container">
            {!! $seo_data->meta_content !!}
            {{-- Solo debe aparecer si hay categioria, en el moment oque ha seccion seleccionada no debe aparecer --}}
            @if (empty($filters['section']))
                <div class="links-sections">
                    @foreach ($sections as $sec)
						@if(!empty($sec['key_sec']))
                        <a class="mr-2"
                            href="{{ route('section', ['keycategory' => $infoOrtsec->key_ortsec0, 'keysection' => $sec['key_sec'] ?? ' ']) }}">{{ ucfirst($sec['des_sec']) }}</a>
						@endif
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
