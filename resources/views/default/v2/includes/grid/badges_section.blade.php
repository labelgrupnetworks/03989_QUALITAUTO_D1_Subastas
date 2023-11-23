@php
	$allRequest = request()->collect()->filter();
	$hasFeaturesFilter = collect($allRequest->get('features', []))->filter()->isNotEmpty();
	$filters = collect(['description', 'reference', 'liveLots', 'noAward', 'award', 'typeSub', /* 'category', 'section', 'subsection', */ 'auchouse']);
	$hasSimpleFilters = $filters->intersect($allRequest->keys())->isNotEmpty();
@endphp

@if($hasSimpleFilters || $hasFeaturesFilter)
<section class="section-badges">

	<p class="mb-1">Filtros activos</p>

	@if(!empty(request('description')))
        <span data-del_filter="#description" class="del_filter_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{request('description')}}</span>
		</span>
	@endif

	@if(!empty(request('features')) && is_array(request('features')) && $hasFeaturesFilter)

		@foreach(request('features') as $idFeature => $idValueFeature)
			@php
				$name = $features[$idFeature];
			@endphp
			@if(!empty($idValueFeature) && !is_array($idValueFeature))
				<span data-del_filter="#feature_{{$idFeature}}" class="del_filter_js badge rounded-pill badge-custom-primary">
					<span>X</span>
					<span>
						@if(!empty($featuresCount[$idFeature]) && !empty($featuresCount[$idFeature][$idValueFeature]))
							{{$featuresCount[$idFeature][$idValueFeature]["value_caracteristicas_value"]}}
						@else
							{{ trans("$theme-app.features.$name") }}
							{{$idValueFeature}}
						@endif
					</span>
				</span>
			@elseif(!empty($idValueFeature))


				@foreach ($idValueFeature as $valueFeature)
				@if(!empty($valueFeature))
				<span data-del_filter="#feature_{{$idFeature}}" class="del_filter_js badge rounded-pill badge-custom-primary">
					<span>X</span>
					<span>
						{{ trans("$theme-app.features.$name") }}
						{{$valueFeature}}
					</span>
				</span>
				@endif
				@endforeach


			@endif
		@endforeach
	@endif

	@if(!empty(request('reference')))
		<span data-del_filter="#reference" class="del_filter_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{request('reference')}}</span>
		</span>
	@endif
     @if(!empty(request('liveLots')))
        <span data-del_filter="#liveLots" class="del_filter_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{ trans("$theme-app.lot_list.live_lots_filter") }}</span>
		</span>
    @endif
    @if(!empty(request('noAward')))
        <span data-del_filter="#no-award" class="del_filter_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{ trans("$theme-app.lot_list.no_award_filter") }}</span>
		</span>
    @endif
    @if(!empty(request('award')))
        <span data-del_filter="#award" class="del_filter_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{ trans("$theme-app.lot_list.award_filter") }}</span>
		</span>
    @endif
    {{-- el filtro de tipo de subasta solo debe aparecer por categorias , no por subasta ya que no se podrá quitar --}}
    @if(!empty($filters["typeSub"]) && empty($auction))
        <span class="del_filter_typeSub_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{ $tipos_sub[$filters["typeSub"]] }}</span>
		</span>
    @endif

    @if(!empty($filters["category"]) && !empty($infoOrtsec))
        <span class="del_filter_category_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{ $infoOrtsec->des_ortsec0 }}</span>
		</span>
    @endif

    @if(!empty($filters["section"]) && !empty($infoSec))
        <span class="del_filter_section_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{ ucfirst(mb_strtolower($infoSec->des_sec)) }}</span>
		</span>
	@endif

    @if(!empty($filters["subsection"]) && !empty($infoSubSec))
        <span class="del_filter_subsection_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{ ucfirst(mb_strtolower($infoSubSec->des_subsec)) }}</span>
		</span>

    @endif
     <?php //el filtro de casa de subastas solo debe aparecer por categorias , no por subasta ya que no se podrá quitar   ?>
    @if(!empty($filters["auchouse"]) && empty($auction) && !empty($aucHouses) && !empty($aucHouses[$filters["auchouse"]]))
        <span class="del_filter_auchouse_js badge rounded-pill badge-custom-primary">
			<span>X</span><span>{{$aucHouses[$filters["auchouse"]]["rsoc_auchouse"]}}</span>
		</span>
    @endif
</section>
@endif
