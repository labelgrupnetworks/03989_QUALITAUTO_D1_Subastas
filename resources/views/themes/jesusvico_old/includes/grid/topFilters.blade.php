
<div class="col-xs-12 col-md-4 pt-1">
    <?php $show_hr = false; ?>
    @if(!empty(request('description')))
        <span data-del_filter="#description" class="del_filter_js badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{request('description')}}      </span>
        <?php $show_hr = true; ?>
	@endif

	@if(!empty(request('features')) && is_array(request('features')))
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
        <span class="del_filter_category_js  badge hidden" style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{$infoOrtsec->des_ortsec0}}      </span>
         <?php $show_hr = true; ?>
    @endif

    @if(!empty($filters["section"]) && !empty($infoSec))
        <span class="del_filter_section_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{$infoSec->des_sec}}      </span>
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

	@if(!empty(request('myLotsProperty')))
		<span data-del_filter="#myLotsProperty" class="del_filter_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{trans(\Config::get('app.theme').'-app.lot_list.my_lots_property')}}       </span>
		<?php $show_hr = true; ?>
	@endif

	@if(!empty(request('myLotsClient')))
		<span data-del_filter="#myLotsClient" class="del_filter_js  badge " style="padding: 1rem; cursor: pointer;">X&nbsp;&nbsp;&nbsp;{{trans(\Config::get('app.theme').'-app.lot_list.my_lots_clients')}}       </span>
		<?php $show_hr = true; ?>
	@endif

</div>

<div class="col-xs-12 col-md-8 pt-1 pagination-container text-right">
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
    {{-- Tools::numberformat($count_lots) --}}
    {{-- trans(\Config::get('app.theme').'-app.lot_list.results') --}}
	@if(\Config::get("app.paginacion_grid_lotes"))
		{{-- {{ $paginator->links() }} --}}
		{{ $paginator->links('front::includes.grid.paginator_pers') }}
	@endif
</div>
<div class="clearfix"></div>
<div class="col-xs-12"><hr></div>
<div class="clearfix"></div>

