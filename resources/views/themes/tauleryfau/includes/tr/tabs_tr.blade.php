@if (\Config::get('app.tr_show_adjudicaciones') and Session::has('user') && empty($data['js_item']['user']['is_gestor']))
<div class="started hidden zone-tabs">
    <div class="adjudicaciones-header">
        <div class="header-tr_tabs col-xs-12 no-padding">
            <div class="col-md-2 col-lg-1 col-xs-2 title-tables">{{ trans($theme.'-app.sheet_tr.lot') }}</div>
            <div class="col-xs-10 col-sm-8 col-xs-7 col-lg-10 title-tables">{{ trans($theme.'-app.sheet_tr.description') }}</div>
            <div class=" col-lg-2 col-sm-2 col-xs-3 col-lg-1  title-tables text-right">{{ trans($theme.'-app.sheet_tr.adjudicate') }}</div>
        </div>
        <div class="adjudicaciones aside col-xs-12" style="padding: 0; border: 0">
            <div id="adjudicaciones_list" class="col-xs-12 no-padding">
                @if (!empty($data['js_item']['user']) && !empty($data['js_item']['user']['adjudicaciones']))
                    <?php
                        foreach ($data['js_item']['user']['adjudicaciones'] as $key => $val): ?>
                            <div class="adjudicaciones_model">
                                <div class="col-xs-2 col-lg-1 col-sm-2 adj_ref">
                                    <p></i> <span>{{ trans($theme.'-app.sheet_tr.lot') }} {{ $val->ref_asigl1 }}</span></p>
                                </div>
                                <div class="col-xs-10 col-sm-8 col-xs-7 col-lg-10 tabs-tr-description ellipsis"></div>
                                <div class="col-lg-2 col-md-2 col-xs-3 col-lg-1  text-right">
                                    <p><span class="adj_imp">{{ $val->imp_asigl1 }}</span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                                </div>
                            </div>
                    <?php
                        endforeach;
                    ?>
                    <div class="adjudicaciones_model hidden" id="type_adj_model">
                        <div class="col-xs-2 col-lg-1 col-sm-2 adj_ref">
                            <p>{{ trans($theme.'-app.sheet_tr.lot') }}</i> <span></span></p>
                        </div>
                        <div class="col-xs-7 col-sm-8 col-lg-10 tabs-tr-description">{{ trans($theme.'-app.sheet_tr.description') }}</div>
                        <div class="col-lg-2 col-sm-2 col-xs-3 col-lg-1 text-right">
                            <p><span class="adj_imp"></span> <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span></p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
