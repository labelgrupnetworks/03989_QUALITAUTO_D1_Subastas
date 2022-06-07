<div class="col-xs-12 col-md-4 order-auction-lot">
    <select class="form-control" id="order_selected" >

        <option value="name" @if ($filters["order"] == 'name') selected @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   {{ trans(\Config::get('app.theme').'-app.lot_list.name') }}
        </option>
        <option value="price_asc" @if ($filters["order"] == 'price_asc') selected @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}
        </option>
        <option value="price_desc" @if ($filters["order"] == 'price_desc') selected @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:      {{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}
        </option>
        <option value="ref" @if ($filters["order"] == 'ref' || empty($filters["order"]) ) selected @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}
        </option>

		<!-- 12/03/21 -->
		<option value="date_asc" @if ($filters["order"] == 'date_asc') selected @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.date_asc') }}
        </option>
        <option value="date_desc" @if ($filters["order"] == 'date_desc') selected @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:      {{ trans(\Config::get('app.theme').'-app.lot_list.date_desc') }}
        </option>
		<!-- -->

        <option value="hbids" @if ($filters["order"] == 'hbids') selected  @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }}
        </option>
        <option value="mbids" @if ($filters["order"] == 'mbids') selected  @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }}
		</option>
		<option value="lastbids" @if ($filters["order"] == 'lastbids') selected  @endif >
            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.last_bids') }}
        </option>
        @if(!empty($auction) && $auction->tipo_sub == 'O'))
            <option value="ffin" @if ($filters["order"] == 'ffin') selected @endif >
                {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }} </b>
            </option>

        @endif

    </select>
</div>

<div class="col-xs-12 col-md-4">
    <select class="form-control" id="total_selected" >
		@foreach(\Config::get("app.filter_total_shown_options") as $numLots)
        	<option value="{{$numLots}}" @if (request('total') == $numLots) selected @endif >    {{ trans(\Config::get('app.theme').'-app.lot_list.see_num_lots',["num" => $numLots]) }}   </option>
		@endforeach
    </select>
</div>


<div class="col-xs-12 pt-1 d-flex align-items-center mt-1">
    <?php
    $count_lots = 0;
    foreach($tipos_sub as $typeSub =>$desType) {

        $numLots = Tools::showNumLots($numActiveFilters, $filters, "typeSub", $typeSub);

        if(empty($filters['typeSub'])){
            $count_lots += $numLots;
        }elseif($typeSub == $filters['typeSub']){
              $count_lots = $numLots;
        }
    }

       // ponemos puntos de millar            ?>
    <p class="mr-2 mb-0 cantidad-res"  >{{ Tools::numberformat($count_lots) }} {{ trans(\Config::get('app.theme').'-app.lot_list.results') }}</p>


	{{-- FILTRO DE SUBASTAS HISTÓRICAS --}}
		@if(\Config::get("app.gridHistoricoVentas"))
			{{-- estará oculto a no ser que haya lotes en el historico --}}
			<span id="seeHistoricLots_JS" class="gridFilterHistoric hidden">
				{!! trans(\Config::get('app.theme').'-app.lot_list.see_historic_lots') !!}
			</span>



				@if(request('historic'))
					<span id="seeActiveLots_JS" class="gridFilterHistoric">
						{{ trans(\Config::get('app.theme').'-app.lot_list.return_active_lots') }}
					</span>
						{{-- solo haremos la llamada si estamos en categorias y han buscado texto   && !empty(request('description')--}}
				@elseif(empty($auction))
					<script>$(function() { showHistoricLink(); })</script>
				@endif
		@endif

	{{-- FIN FILTRO DE SUBASTAS HISTÓRICAS --}}
	@if(\Config::get("app.paginacion_grid_lotes"))
	 	{{ $paginator->links() }}
 	@endif
</div>
<div class="clearfix"></div>
<div class="col-xs-12"><hr></div>
<div class="clearfix"></div>

<div class="col-xs-12">
    <?php $show_hr = false; ?>
    @if(!empty(request('description')))
        <span data-del_filter="#description" class="del_filter_js badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{request('description')}}      </span>
        <?php $show_hr = true; ?>
	@endif

	@if(!empty(request('features')) && is_array(request('features')) && collect(request('features'))->filter()->isNotEmpty())
		@foreach(request('features') as $idFeature=> $idValueFeature)
			@if(!empty($idValueFeature))
				<span data-del_filter="#feature_{{$idValueFeature}}" class="del_filter_js badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;
					@if(!empty($featuresCount[$idFeature]) && !empty($featuresCount[$idFeature][$idValueFeature]))
						{{$featuresCount[$idFeature][$idValueFeature]["value_caracteristicas_value"]}}
					@else
						{{$idValueFeature}}
					@endif
				</span>
			@endif
		@endforeach
		<?php $show_hr = true; ?>

	@endif


	@if(!empty(request('reference')))
	<span data-del_filter="#reference" class="del_filter_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{request('reference')}}      </span>
	<?php $show_hr = true; ?>
	@endif
     @if(!empty(request('liveLots')))
        <span data-del_filter="#liveLots" class="del_filter_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{trans(\Config::get('app.theme').'-app.lot_list.live_lots_filter')}}       </span>
        <?php $show_hr = true; ?>
    @endif
    @if(!empty(request('noAward')))
        <span data-del_filter="#no-award" class="del_filter_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp; {{trans(\Config::get('app.theme').'-app.lot_list.no_award_filter')}}       </span>
        <?php $show_hr = true; ?>
    @endif
    @if(!empty(request('award')))
        <span data-del_filter="#award" class="del_filter_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{trans(\Config::get('app.theme').'-app.lot_list.award_filter')}}       </span>
        <?php $show_hr = true; ?>
    @endif
    <?php //el filtro de tipo de subasta solo debe aparecer por categorias , no por subasta ya que no se podrá quitar   ?>
    @if(!empty($filters["typeSub"]) && empty($auction))
        <span class="del_filter_typeSub_js   badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{$tipos_sub[$filters["typeSub"]]}}      </span>
         <?php $show_hr = true; ?>
    @endif

    @if(!empty($filters["category"]) && !empty($infoOrtsec))
        <span class="del_filter_category_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{$infoOrtsec->des_ortsec0}}      </span>
         <?php $show_hr = true; ?>
    @endif

    @if(!empty($filters["section"]) && !empty($infoSec))
        <span class="del_filter_section_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{ucfirst(mb_strtolower($infoSec->des_sec))}}      </span>
         <?php $show_hr = true; ?>
	@endif

    @if(!empty($filters["subsection"]) && !empty($infoSubSec))
        <span class="del_filter_subsection_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{ucfirst(mb_strtolower($infoSubSec->des_subsec))}}      </span>
         <?php $show_hr = true; ?>
    @endif
     <?php //el filtro de casa de subastas solo debe aparecer por categorias , no por subasta ya que no se podrá quitar   ?>
    @if(!empty($filters["auchouse"]) && empty($auction) && !empty($aucHouses) && !empty($aucHouses[$filters["auchouse"]]))
        <span class="del_filter_auchouse_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{$aucHouses[$filters["auchouse"]]["rsoc_auchouse"]}}   </span>
         <?php $show_hr = true; ?>
    @endif

</div>

@if($show_hr)
    <div class="col-xs-12"><hr></div>
    <div class="clearfix"></div>
@endif
