<div class="hidden started">
    <div>

        <div class="aside" id="loteAjax">
            <h2 class="full-left">{{ trans('web.sheet_tr.lot') }} <span id="slote_title">
				{{ str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $data['subasta_info']->lote_siguiente->ref_asigl0)}}
			</span></h2>

            <div class="search-lot">
                <div class="input-group" style="margin-top:0;">
                    <input type="text" id="search_item_field" class="form-control" placeholder="{{ trans('web.sheet_tr.insert_item') }}">
                    <span class="input-group-btn">
                        <button id="search_item" class="btn btn-primary" type="button">{{ trans('web.sheet_tr.view') }}</button>
                    </span>
                </div>
            </div>

            <div class="row">

               <div class="row">
                    <div class="col-lg-6">
                        <button id="left" class="controles btn btn-primary pull-left" type="button"><i class="fa fa-angle-left fa-lg"></i></button>
                    </div>
                    <div class="col-lg-6">
                        <button id="right" class="controles btn btn-primary pull-right" type="button"><i class="fa fa-angle-right fa-lg"></i></button>
                    </div>
                </div>

                <div class="col-lg-4">

                    <div class="img2">

                        <img class="img-responsive img-lot2" src="{{ \Tools::url_img("lote_small", $data['subasta_info']->lote_siguiente->num_hces1, $data['subasta_info']->lote_siguiente->lin_hces1) }}">
                    </div>

                </div>

                <div class="col-lg-6">
                    <span>


					@if( !empty($data['subasta_info']->lote_siguiente->titulo_hces1))
					<h4 >{{ $data['subasta_info']->lote_siguiente->titulo_hces1 }} </h4>
					@else
						<span id="desc_web"> {{ $data['subasta_info']->lote_siguiente->descweb_hces1 }}</span><br>
					@endif

                        {{ trans('web.sheet_tr.start_price') }}: <span class="precio">{{ \Tools::moneyFormat($data['subasta_info']->lote_siguiente->impsalhces_asigl0) }}</span>
                        <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
                    </span><br>

                    <span class="lot-msg_adjudicado @if ($data['subasta_info']->lote_siguiente->cerrado_asigl0 != 'S' || ( $data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S' && $data['subasta_info']->lote_siguiente->max_puja == 0 ) ) hidden @endif"><b><i class="fa fa-exclamation" aria-hidden="true"></i> {{ trans('web.sheet_tr.awarded') }}:</b> <span class="imp_adj"></span></span>

                    <span class="lot-msg_ensubasta @if ($data['subasta_info']->lote_siguiente->ref_asigl0 != $data['subasta_info']->lote_actual->ref_asigl0) hidden @endif"><b><i class="fa fa-exclamation" aria-hidden="true"></i> {{ trans('web.sheet_tr.in_auction') }}</b>  </span>

                    <button data-from="buscador" class="lot-action_comprar btn btn-primary pull-left @if ($data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'J' || $data['subasta_info']->lote_siguiente->cerrado_asigl0 != 'S' || ( $data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S' && $data['subasta_info']->lote_siguiente->max_puja != 0 ) ) hidden @endif" type="button" ref="{{ $data['subasta_info']->lote_siguiente->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_siguiente->cod_sub }}">{{ trans('web.sheet_tr.buy') }}</button>
					@if ( $data['subasta_info']->lote_actual->subabierta_sub != 'P')
                    	<button data-from="buscador" class="lot-order_importe btn btn-primary pull-right @if ($data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S' || $data['subasta_info']->lote_siguiente->ref_asigl0 == $data['subasta_info']->lote_actual->ref_asigl0) hidden @endif" type="button">{{ trans('web.sheet_tr.import_order') }}</button>
				    @endif

					<div class="clearfix"></div>
                    @if(!empty($data['js_item']['user']['is_gestor']))

                            <button id="lot-pausar" style="margin-right:3px;" class="btn btn-danger pull-left pausarLote pausarLote{{ $data['subasta_info']->lote_siguiente->ref_asigl0 }} <?= $data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S'? 'hidden' : '' ?>" data-orden="{{ $data['subasta_info']->lote_siguiente->orden_hces1 }}" ref="{{ $data['subasta_info']->lote_siguiente->ref_asigl0 }}" type="button">{{ trans('web.sheet_tr.stop_lot') }}</button>

                            <button id="abrirLote" style="margin-right:3px;" class="btn btn-danger pull-left <?= $data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'N' && $data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'J'? 'hidden' : '' ?>"   type="button">{{ trans('web.sheet_tr.open_lot') }}</button>

                    @endif

					<div class="clearfix"></div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="add_to_fav" data-from="buscador"> {{ trans('web.sheet_tr.add_to_fav') }}
                        </label>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
