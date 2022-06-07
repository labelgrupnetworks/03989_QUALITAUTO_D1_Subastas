{{-- cargamos las secciones que dependen de este Tsec --}}
<div class="category_level__02 collapse in"  id="sections_{{$category["key_ortsec0"]}}">
    <div class="input-category d-flex align-items-center">
        <div class="radio">
            <input type="radio" name="section" id="all_sections" value="" class="filter_lot_list_js" <?=   empty($filters["section"])? 'checked="checked"' : '' ?>  />
            <label for="all_sections" class="ratio-label">
                {{trans(\Config::get('app.theme').'-app.lot_list.all_categories')}} ({{$numCategoryLots }})
            </label>
        </div>
	</div>
	<?php

		if(!empty($codSub)){

			$sections = App\Models\V5\FxSec::JoinLangFxSec()
                    ->addselect("FXSEC.COD_SEC")
                    ->addselect("max(NVL(FXSEC_LANG.KEY_SEC_LANG, FXSEC.KEY_SEC)) KEY_SEC")
                    ->addselect("max(NVL(FXSEC_LANG.DES_SEC_LANG, FXSEC.DES_SEC)) DES_SEC")
					->JoinFgOrtsecFxSec()
					->join("FGHCES1", " FGHCES1.SEC_HCES1 =  FXSEC.COD_SEC ")
					->join("FGASIGL0", " FGASIGL0.NUMHCES_ASIGL0 = FGHCES1.NUM_HCES1  AND FGASIGL0.LINHCES_ASIGL0 = FGHCES1.LIN_HCES1 ")
					->where("FGORTSEC1.LIN_ORTSEC1", $filters["category"])
					->where("FGORTSEC1.SUB_ORTSEC1", "0")
					->where("FGHCES1.EMP_HCES1", \Config::get("app.emp"))
					->where("FGASIGL0.EMP_ASIGL0", \Config::get("app.emp"))
					->where("FGASIGL0.SUB_ASIGL0", $codSub)
					->groupby("FXSEC.COD_SEC")
                    ->orderby("min(REF_ASIGL0)")
                    //->orderby("NVL(FXSEC_LANG.DES_SEC_LANG, FXSEC.DES_SEC)")
                    ->get()
					->toarray();

		}

	?>

    @foreach($sections as $sec)
     <?php $numSectionLots = Tools::showNumLots($numActiveFilters, $filters, "section", $sec["cod_sec"]); ?>
        @if($numSectionLots > 0)
            <div class="input-category d-flex align-items-center">
                <div class="radio">
                    <input type="radio" name="section" id="section_{{$sec["cod_sec"]}}" value="{{$sec["cod_sec"]}}" class="filter_lot_list_js" <?= ($sec["cod_sec"] ==  $filters["section"])?  'checked="checked"' : '' ?> />
                    <label for="section_{{$sec["cod_sec"]}}" class="radio-label">{{ $sec["des_sec"] }}  ({{Tools::numberformat($numSectionLots)}})</label>
                </div>
			</div>

			@if($sec["cod_sec"] ==  $filters["section"])
				@include('includes.grid.subsections_list')
			@endif
        @endif

    @endforeach

</div>

