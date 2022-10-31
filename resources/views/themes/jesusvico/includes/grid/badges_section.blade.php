<section class="section-badges">

	@if(!empty(request('description')))
        <span data-del_filter="#description" class="del_filter_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])
			<span>{{request('description')}}</span>
		</span>
	@endif

	@if(!empty(request('features')) && is_array(request('features')) && collect(request('features'))->filter()->isNotEmpty())
		@foreach(request('features') as $idFeature=> $idValueFeature)
			@if(!empty($idValueFeature))
				<span data-del_filter="#feature_{{$idValueFeature}}" class="del_filter_js badge badge-custom-primary">
					@include('components.boostrap_icon', ['icon' => 'x-circle'])
					<span>
						@if(!empty($featuresCount[$idFeature]) && !empty($featuresCount[$idFeature][$idValueFeature]))
							{{$featuresCount[$idFeature][$idValueFeature]["value_caracteristicas_value"]}}
						@else
							{{$idValueFeature}}
						@endif
					</span>

				</span>
			@endif
		@endforeach
	@endif

	@if(!empty(request('reference')))
		<span data-del_filter="#reference" class="del_filter_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{request('reference')}}</span>
		</span>
	@endif
     @if(!empty(request('liveLots')))
        <span data-del_filter="#liveLots" class="del_filter_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{ trans("$theme-app.lot_list.live_lots_filter") }}</span>
		</span>
    @endif
    @if(!empty(request('noAward')))
        <span data-del_filter="#no-award" class="del_filter_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{ trans("$theme-app.lot_list.no_award_filter") }}</span>
		</span>
    @endif
    @if(!empty(request('award')))
        <span data-del_filter="#award" class="del_filter_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{ trans("$theme-app.lot_list.award_filter") }}</span>
		</span>
    @endif
    {{-- el filtro de tipo de subasta solo debe aparecer por categorias , no por subasta ya que no se podrá quitar --}}
    @if(!empty($filters["typeSub"]) && empty($auction))
        <span class="del_filter_typeSub_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{ $tipos_sub[$filters["typeSub"]] }}</span>
		</span>
    @endif

    @if(!empty($filters["category"]) && !empty($infoOrtsec))
        <span class="del_filter_category_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{ $infoOrtsec->des_ortsec0 }}</span>
		</span>
    @endif

    @if(!empty($filters["section"]) && !empty($infoSec))
        <span class="del_filter_section_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{ ucfirst(mb_strtolower($infoSec->des_sec)) }}</span>
		</span>
	@endif

    @if(!empty($filters["subsection"]) && !empty($infoSubSec))
        <span class="del_filter_subsection_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{ ucfirst(mb_strtolower($infoSubSec->des_subsec)) }}</span>
		</span>

    @endif
     <?php //el filtro de casa de subastas solo debe aparecer por categorias , no por subasta ya que no se podrá quitar   ?>
    @if(!empty($filters["auchouse"]) && empty($auction) && !empty($aucHouses) && !empty($aucHouses[$filters["auchouse"]]))
        <span class="del_filter_auchouse_js badge badge-custom-primary">
			@include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{$aucHouses[$filters["auchouse"]]["rsoc_auchouse"]}}</span>
		</span>
    @endif
</section>
