<div class="aside adjudicaciones">
    <div id="adjudicaciones_list">
        @if (!empty($data['js_item']['user']) && !empty($data['js_item']['user']['adjudicaciones']))
        <?php foreach ($data['js_item']['user']['adjudicaciones'] as $key => $val): ?>
            <div class="adjudicaciones_model">
                <div class="adj_ref">
                    <p>{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }}</i> <span>

						{{ str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"), $val->ref_asigl1)}}


					</span></p>
                </div>
                <div class="">
                    <p><span class="adj_imp">{{ $val->imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                </div>
            </div>
        <?php endforeach; ?>
        @endif

        <div class="adjudicaciones_model hidden" id="type_adj_model">
            <div class="adj_ref">
                <p>{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }}</i> <span></span></p>
            </div>
            <div class="">
                <p><span class="adj_imp"></span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
            </div>
        </div>
    </div>
</div>

