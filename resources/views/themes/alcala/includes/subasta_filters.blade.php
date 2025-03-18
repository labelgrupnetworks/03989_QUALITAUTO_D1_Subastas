<div class="filters-auction-content">

    <div class="form-group">
        <div class="filters-auction-title d-flex align-items-center justify-content-space-between">
            <span>{{ trans($theme . '-app.lot_list.filters') }}</span><span
                class="filters-auction-plus" id="texts" data-active='open' role="button">+</span>
        </div>
        <div class="filters-auction-texts">
            <label class="filters-auction-label"
                for="input_description"><span>{{ trans($theme . '-app.lot_list.search') }}</span></label>
            <input class="form-control input-sm filter-auction-input" id="input_description" name="description"
                type="text" value="{{ app('request')->input('description') }}"
                placeholder="{{ trans($theme . '-app.lot_list.search_placeholder') }}">
            <div class="filters-auction-divider-small"></div>
            <label class="filters-auction-label"
                for="input_reference">{{ trans($theme . '-app.lot_list.reference') }}</label>
            <input class="form-control input-sm filter-auction-input" id="input_reference" name="reference"
                type="text" value="{{ app('request')->input('reference') }}"
                placeholder="{{ trans($theme . '-app.lot_list.reference') }}">
            <div class="filter-section-checks filters-auction-label" style="margin-top: 2rem;">
                <ul>
                    <li style="align-items: center;display: flex;">
                        <input class="filled-in " id="no_award" name="no_award" type="checkbox"
                            onclick="javascript:checkfilter('no_award')"
                            <?= !empty(app('request')->input('no_award')) ? "checked='checked'" : '' ?>>
                        <label for="no_award"
                            style="font-size: 15px;font-weight: 500">{{ trans($theme . '-app.lot_list.dont_sold_lots') }}</label>
                    </li>
                    <li style="align-items: center;display: flex;">
                        <input class="filled-in" id="award" name="award" type="checkbox"
                            onclick="javascript:checkfilter('award')"
                            <?= !empty(app('request')->input('award')) ? "checked='checked'" : '' ?>>
                        <label for="award"
                            style="font-size: 15px;font-weight: 500">{{ trans($theme . '-app.lot_list.sold_lots') }}</label>
                    </li>

                </ul>
            </div>
        </div>


        <div class="filters-auction-divider-big"></div>
        <?php

        $catpers_selected = app('request')->input('catpers');
        if (!is_numeric($catpers_selected)) {
            $catpers_selected = '';
        }
        $subasta = new App\Models\Subasta();
        $subasta->select_filter = 'ORTSEC1.LIN_ORTSEC1, ORTSEC0.orden_ORTSEC0, COUNT(COD_SEC) cuantos';
        $subasta->select_filter .= ' ,NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0 ';
        $subasta->select_filter .= ' ,NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0 ';
        //$subasta->select_filter = "SEC.COD_SEC, COUNT(COD_SEC)";
        $subasta->join_filter = 'JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1 ) ';
        $subasta->join_filter .= 'JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 ) ';
        $subasta->join_filter .= 'JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = HCES1.EMP_HCES1  and ORTSEC0.lin_ortsec0 =ORTSEC1.lin_ortsec1 ) ';
        $subasta->join_filter .= "LEFT JOIN FGORTSEC0_LANG ORTSEC0_LANG ON (ORTSEC0_LANG.sub_ORTSEC0_LANG = ORTSEC1.sub_ORTSEC1 AND ORTSEC0_LANG.EMP_ORTSEC0_LANG = HCES1.EMP_HCES1  and ORTSEC0_LANG.LIN_ORTSEC0_LANG =ORTSEC1.LIN_ORTSEC1  AND ORTSEC0_LANG.LANG_ORTSEC0_LANG = '" . Config::get('app.language_complete')[Config::get('app.locale')] . "')";
        $subasta->where_filter = " AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = '" . Config::get('app.gemp') . "' ";
        $subasta->group_by = 'ORTSEC1.LIN_ORTSEC1, ORTSEC0.orden_ORTSEC0, NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0),NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0)';
        $subasta->order_by_values = 'ORTSEC0.orden_ORTSEC0';
        //para las subastas type category solo cogemos las ortsec de la subasta 0 y las que sean TIPO_SUB = p , para las normales cogemos la subasta que toca con la sesion
        $subasta->where_filter .= " AND ORTSEC1.SUB_ORTSEC1 = '0'  ";

        $subasta->where_filter .= " AND AUC.\"id_auc_sessions\" = " . $data['id_auc_sessions'] . ' ';

        $categories = $subasta->getLots('small', true);
        ?>
        @if (count($categories) > 0)
            <div class="panel-collapse collapse in" id="collapseTwo" role="tabpanel" aria-labelledby="headingTwo">
                <div class="filters-auction-title d-flex align-items-center justify-content-space-between">
                    <label
                        class="filters-auction-label">{{ trans($theme . '-app.lot_list.categories') }}</label>
                    <span></span><span class="filters-auction-plus" id="selects" data-active='open'
                        role="button">+</span>
                </div>
                <div class="filters-auction-selects">
                    <select class="form-control filters-auction-select" id="category" name="catpers">
                        @foreach ($categories as $category)
                            <option value="{{ $category->lin_ortsec1 }}"
                                <?= $catpers_selected == $category->lin_ortsec1 ? "selected='selected'" : '' ?>>
                                {{ $category->des_ortsec0 }} ({{ $category->cuantos }})</option>
                        @endforeach
                    </select>

                </div>
            </div>
        @endif
        <div class="filters-auction-divider-big"></div>

		{{-- // No se esta utilizando
		<div id="select_filters">
            @include('includes.select_filters')
        </div>
        <div class="filters-auction-divider-big"></div>
		 --}}


        <button class="btn btn-filter color-letter"
            type="submit">{{ trans($theme . '-app.lot_list.filter') }}</button>

    </div>

</div>

<script>
    $("#category").on('change', function() {
        //borrar los names de los selectores para que no se envien al cambiar de subcategoria
        $("[name$='_select']").attr("name", "");
        $("#form_lotlist").submit();
    });

    $("#subcategory").on('change', function() {
        //borrar los names de los selectores para que no se envien al cambiar de subcategoria
        $("[name$='_select']").attr("name", "");
        $("#form_lotlist").submit();
    });

    let originalHeight;
    $('.filters-auction-plus').click(function() {
        var id = $(this).attr('id')
        var degrees = 0

        if ($(this).attr('data-active') === 'open') {
            originalHeight = $(`.filters-auction-${id}`).css('height')
            $(`.filters-auction-${id}`).css('height', degrees)
            $(this).css({
                '-webkit-transform': 'rotate(' + degrees + 'deg)',
                '-moz-transform': 'rotate(' + degrees + 'deg)',
                '-ms-transform': 'rotate(' + degrees + 'deg)',
                'transform': 'rotate(' + degrees + 'deg)'
            });
            $(this).attr('data-active', 'close')

        } else {
            degrees = 45
            $(`.filters-auction-${id}`).css('height', originalHeight)
            $(this).css({
                '-webkit-transform': 'rotate(' + degrees + 'deg)',
                '-moz-transform': 'rotate(' + degrees + 'deg)',
                '-ms-transform': 'rotate(' + degrees + 'deg)',
                'transform': 'rotate(' + degrees + 'deg)'
            });
            $(this).attr('data-active', 'open')

        }
    })


    function checkfilter(check_name) {

        if (check_name == 'open') {
            $("#award").attr("checked", false);
            $("#no_award").attr("checked", false);
        }
        if (check_name == 'award') {
            $("#no_award").attr("checked", false);
            $("#open").attr("checked", false);
        }
        if (check_name != 'exclusive_offers') {
            $("#exclusive_offers").attr("checked", false);
        }
        if (check_name == 'no_award') {
            $("#award").attr("checked", false);
            $("#open").attr("checked", false);
        }
        if (check_name != 'discounts') {
            $("#discounts").attr("checked", false);
        }
        if (check_name == 'my_lots_property') {
            $("#my_lots_client").attr("checked", false)
        }
        if (check_name == 'my_lots_client') {
            $("#my_lots_property").attr("checked", false);
        }

        $("#form_lotlist").submit();
    }
</script>
