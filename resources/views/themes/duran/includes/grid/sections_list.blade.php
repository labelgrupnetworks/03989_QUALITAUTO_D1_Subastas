
<div class="sub-filtro">
	<div class="input-radio-div d-flex- align-items-center hidden">
		<input type="radio" name="section" id="all_sections" value="" <?=   empty($filters["section"])? 'checked="checked"' : '' ?>  />
	</div>
	@foreach($sections as $sec)
		<?php $numSectionLots = Tools::showNumLots($numActiveFilters, $filters, "section", $sec["cod_sec"]); ?>

		@if($numSectionLots > 0)
			<div class="input-radio-div d-flex- align-items-center">
				<div class="radio">
					<input class="radio-filtro filter_lot_list_js" type="radio"  name="section" id="section_{{$sec["cod_sec"]}}" value="{{$sec["cod_sec"]}}" <?= ($sec["cod_sec"] ==  $filters["section"])?  'checked="checked"' : '' ?> >
				<label for="section_{{$sec['cod_sec']}}" class="radio-label-filtro <?= ($sec["cod_sec"] ==  $filters["section"])?  'del_filter_section_js' : '' ?> ">{{ $sec["des_sec"] /*ucfirst(mb_strtolower($sec["des_sec"])) */}}  ({{Tools::numberformat($numSectionLots)}})</label>
				</div>
			</div>

		@endif
	@endforeach

</div>
