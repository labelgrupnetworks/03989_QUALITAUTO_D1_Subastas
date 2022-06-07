{{-- cargamos las secciones que dependen de este Tsec --}}
<div class="category_level__02 collapse in" style="padding-left: 2rem;" id="sections_{{$category["key_ortsec0"]}}">
    <div class="input-category d-flex align-items-center hidden">
        <div class="radio">
            <input type="radio" name="section" id="all_sections" value="" <?=   empty($filters["section"])? 'checked="checked"' : '' ?>  />
            <label for="all_sections" class="ratio-label">
                {{trans(\Config::get('app.theme').'-app.lot_list.all_subcategories')}} ({{$numCategoryLots }})
            </label>
        </div>
    </div>
    @foreach($sections as $sec)
     <?php $numSectionLots = Tools::showNumLots($numActiveFilters, $filters, "section", $sec["cod_sec"]); ?>

	@if($filters["section"] == $sec["cod_sec"] && $numSectionLots > 0 )

		<div class="input-category d-flex align-items-center justify-content-space-between" role="button" data-toggle="collapse" href="#subsections_{{$sec["key_sec"]}}" aria-expanded="true" aria-controls="subsections_{{$sec["key_sec"]}}">
			<div class="radio">
				<input type="radio" name="section" id="section_{{$sec["cod_sec"]}}" value="{{$sec["cod_sec"]}}" class="filter_lot_list_js" <?= ($sec["cod_sec"] ==  $filters["section"])?  'checked="checked"' : '' ?> />
				<label for="section_{{$sec["cod_sec"]}}" class="radio-label">{{ucfirst(mb_strtolower($sec["des_sec"]))}}  ({{Tools::numberformat($numSectionLots)}})</label>
			</div>
			<i role="button" data-toggle="collapse" href="#subsections_{{$sec["key_sec"]}}" aria-expanded="true" aria-controls="subsections_{{$sec["key_sec"]}}" class="fa fa-sort-down"></i>
		</div>

		@if(!empty($filters["section"]) && $filters["section"] == $sec["cod_sec"])
			@include('includes.grid.subsections_list')
		@endif

	@elseif($numSectionLots > 0)
            <div class="input-category d-flex align-items-center">
                <div class="radio">
                    <input type="radio" name="section" id="section_{{$sec["cod_sec"]}}" value="{{$sec["cod_sec"]}}" class="filter_lot_list_js" <?= ($sec["cod_sec"] ==  $filters["section"])?  'checked="checked"' : '' ?> />
                    <label for="section_{{$sec["cod_sec"]}}" class="radio-label">{{ucfirst(mb_strtolower($sec["des_sec"]))}}  ({{Tools::numberformat($numSectionLots)}})</label>
                </div>
			</div>
    @endif

    @endforeach

</div>

