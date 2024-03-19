
<div class="top-filters-wrapper">

    <div class="pagination-wrapper">
        @if (\Config::get('app.paginacion_grid_lotes'))
            {{ $paginator->links('front::includes.grid.paginator_pers') }}
        @endif
    </div>

    <div class="order-auction-lot">
        <select class="form-control" id="order_selected">

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
                )
                <option value="ffin" @if ($filters['order'] == 'ffin') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}: <b> {{ trans($theme . '-app.lot_list.more_near') }}
                    </b>
                </option>
            @else
                <option value="ffin" @if ($filters['order'] == 'ffin') selected @endif>
                    {{ trans($theme . '-app.lot_list.order') }}: <b> {{ trans($theme . '-app.lot_list.date_asc') }}
                    </b>
                </option>
            @endif

            <option value="date_desc" @if ($filters['order'] == 'date_desc') selected @endif>
                {{ trans($theme . '-app.lot_list.order') }}: {{ trans($theme . '-app.lot_list.date_desc') }}
            </option>

        </select>
    </div>

    <div>
        <select class="form-control" id="total_selected">
            @foreach (\Config::get('app.filter_total_shown_options') as $numLots)
                <option value="{{ $numLots }}" @if (request('total') == $numLots) selected @endif>
                    {{ trans($theme . '-app.lot_list.see_num_lots', ['num' => $numLots]) }} </option>
            @endforeach
        </select>
    </div>

</div>

<div class="top-filters-badges">

    @if (!empty(request('description')))
        <span class="del_filter_js badge"
            data-del_filter="#description">X&nbsp;&nbsp;&nbsp;{{ request('description') }} </span>
    @endif

    @if (!empty(request('features')) && is_array(request('features')))
        @foreach (request('features') as $idFeature => $idValueFeature)
            @if (!empty($idValueFeature))
                <span class="del_filter_js badge" data-del_filter="#feature_{{ $idValueFeature }}">X&nbsp;&nbsp;&nbsp;
                    @if (!empty($featuresCount[$idFeature]) && !empty($featuresCount[$idFeature][$idValueFeature]))
                        {{ $featuresCount[$idFeature][$idValueFeature]['value_caracteristicas_value'] }}
                    @else
                        {{ $idValueFeature }}
                    @endif
                </span>
            @endif
        @endforeach
    @endif


    @if (!empty(request('reference')))
        <span class="del_filter_js badge" data-del_filter="#reference">X&nbsp;&nbsp;&nbsp;{{ request('reference') }}
        </span>
    @endif
    @if (!empty(request('liveLots')))
        <span class="del_filter_js badge"
            data-del_filter="#liveLots">X&nbsp;&nbsp;&nbsp;{{ trans($theme . '-app.lot_list.live_lots_filter') }}
        </span>
    @endif
    @if (!empty(request('noAward')))
        <span class="del_filter_js badge" data-del_filter="#no-award">X&nbsp;&nbsp;&nbsp;
            {{ trans($theme . '-app.lot_list.no_award_filter') }} </span>
    @endif
    @if (!empty(request('award')))
        <span class="del_filter_js badge"
            data-del_filter="#award">X&nbsp;&nbsp;&nbsp;{{ trans($theme . '-app.lot_list.award_filter') }}
        </span>
    @endif
    <?php //el filtro de tipo de subasta solo debe aparecer por categorias , no por subasta ya que no se podrá quitar
    ?>
    {{--
	@if (!empty($filters['typeSub']) && empty($auction))
        <span class="del_filter_typeSub_js  badge" >X&nbsp;&nbsp;&nbsp;{{$tipos_sub[$filters["typeSub"]]}}      </span>
         {{$show_hr = true;}}
	@endif
	--}}

    @if (!empty($filters['category']) && !empty($infoOrtsec))
        <span class="del_filter_category_js badge">X&nbsp;&nbsp;&nbsp;{{ $infoOrtsec->des_ortsec0 }} </span>
    @endif

    @if (!empty($filters['section']) && !empty($infoSec))
        <span class="del_filter_section_js badge">X&nbsp;&nbsp;&nbsp;{{ ucfirst(mb_strtolower($infoSec->des_sec)) }}
        </span>
    @endif

    @if (!empty($filters['subsection']) && !empty($infoSubSec))
        <span
            class="del_filter_subsection_js badge">X&nbsp;&nbsp;&nbsp;{{ ucfirst(mb_strtolower($infoSubSec->des_subsec)) }}
        </span>
    @endif
    <?php //el filtro de casa de subastas solo debe aparecer por categorias , no por subasta ya que no se podrá quitar
    ?>
    @if (!empty($filters['auchouse']) && empty($auction) && !empty($aucHouses) && !empty($aucHouses[$filters['auchouse']]))
        <span
            class="del_filter_auchouse_js badge">X&nbsp;&nbsp;&nbsp;{{ $aucHouses[$filters['auchouse']]['rsoc_auchouse'] }}
        </span>
    @endif

</div>
