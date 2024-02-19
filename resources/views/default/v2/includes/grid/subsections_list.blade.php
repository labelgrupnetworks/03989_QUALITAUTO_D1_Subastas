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
    @foreach($subsections as $subsec)
     <?php $numsubsectionLots = Tools::showNumLots($numActiveFilters, $filters, "subsection", $subsec["cod_subsec"]); ?>
        @if($numsubsectionLots > 0)
            <div class="input-category d-flex align-items-center">
                <div class="radio">
                    <input type="radio" name="subsection" id="subsection_{{$subsec["cod_subsec"]}}" value="{{$subsec["cod_subsec"]}}" class="filter_lot_list_js" <?= ($subsec["cod_subsec"] ==  $filters["subsection"])?  'checked="checked"' : '' ?> />
                    <label for="subsection_{{$subsec["cod_subsec"]}}" class="radio-label">{{ucfirst(mb_strtolower($subsec["des_subsec"]))}}  ({{Tools::numberformat($numsubsectionLots)}})</label>
                </div>
            </div>
        @endif

    @endforeach

</div>

