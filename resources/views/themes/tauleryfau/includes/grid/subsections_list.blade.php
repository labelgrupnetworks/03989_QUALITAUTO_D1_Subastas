{{-- cargamos las secciones que dependen de este Tsec --}}
<div class="category_level__03 collapse in" style="padding-left: 2rem;" id="subsections_{{$sec["key_sec"]}}">
    <div class="input-category d-flex align-items-center hidden">
        <div class="radio">
            <input type="radio" name="subsection" id="all_subsections" value="" <?=   empty($filters["subsection"])? 'checked="checked"' : '' ?>  />
            <label for="all_subsections" class="ratio-label">
                {{trans($theme.'-app.lot_list.all_subsubsection')}} ({{$numCategoryLots }})
            </label>
        </div>
	</div>
	<?php

		if(!empty($codSub)){

			$subsections =App\Models\V5\FxSubSec::JoinLangFxSubSec()
                    ->addselect("FXSUBSEC.COD_SUBSEC")
					->addselect("max(NVL(FXSUBSEC_LANG.KEY_SUBSEC_LANG, FXSUBSEC.KEY_SUBSEC)) KEY_SUBSEC")
                    ->addselect("max(NVL(FXSUBSEC_LANG.DES_SUBSEC_LANG, FXSUBSEC.DES_SUBSEC)) DES_SUBSEC ")
					->JoinFxsecSubSec()
					->join("FGHCES1", " FGHCES1.SUBFAM_HCES1 =  FXSUBSEC.COD_SUBSEC ")
					->join("FGASIGL0", " FGASIGL0.NUMHCES_ASIGL0 = FGHCES1.NUM_HCES1  AND FGASIGL0.LINHCES_ASIGL0 = FGHCES1.LIN_HCES1 ")
					->where("FXSEC_SUBSEC.CODSEC_SEC_SUBSEC", $filters["section"])
					->where("FGHCES1.EMP_HCES1", \Config::get("app.emp"))
					->where("FGASIGL0.EMP_ASIGL0", \Config::get("app.emp"))
					->where("FGASIGL0.SUB_ASIGL0", $codSub)
					->groupby("FXSUBSEC.COD_SUBSEC")
                    ->orderby("min(REF_ASIGL0)")
                    ->get()
					->toarray();
					#forzamos la session actual del bucle para que el contador lo haga bien
			$filters["session"]=$ses->reference;

		}


	?>
    @foreach($subsections as $subsec)
     <?php $numsubsectionLots = Tools::showNumLots($numActiveFilters, $filters, "subsection", $subsec["cod_subsec"]); ?>
        @if($numsubsectionLots > 0)
            <div class="input-category d-flex align-items-center">
                <div class="radio">
                    <input type="radio" name="subsection" id="subsection_{{$subsec["cod_subsec"]}}" @php echo !empty($ses)? 'data-session="'.$ses->reference.'"': '' @endphp  value="{{$subsec["cod_subsec"]}}" class="filter_lot_list_js" <?= ($subsec["cod_subsec"] ==  $filters["subsection"])?  'checked="checked"' : '' ?> />
                    <label for="subsection_{{$subsec["cod_subsec"]}}" class="radio-label">{{ $subsec["des_subsec"] }}  ({{Tools::numberformat($numsubsectionLots)}})</label>
                </div>
            </div>
        @endif

    @endforeach

</div>

