<div class="hidden started lotes-content">
    <div class="aside buscador" id="loteAjax">

        <div class="search-lot">
            <div class="input-group" style="margin-top:0;">
                <input type="text" id="search_item_field" class="form-control" placeholder="{{ trans(\Config::get('app.theme').'-app.sheet_tr.insert_item') }}">
                <span class="input-group-btn">
                    <button id="search_item" class="btn btn-primary" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.view') }}</button>
                </span>
            </div>
        </div>

        <div class="btn-previous">
            <button id="left" class="controles btn btn-primary pull-left" type="button"><i class="fa fa-angle-left fa-lg"></i></button>
        </div>

        <div class="btn-next">
            <button id="right" class="controles btn btn-primary pull-right" type="button"><i class="fa fa-angle-right fa-lg"></i></button>
        </div>

        <div class="num-lot-search">
            <h2 class="">
				{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }}
				<span id="slote_title">
				{{ str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $data['subasta_info']->lote_siguiente->ref_asigl0)}}
				</span>
				<span class="lot-itp-mark {{ $isITP ? '' : 'hidden' }}">*</span></h2>
        </div>

        <div class="infoLot"></div>

        <div class="img2">
        <img class="img-responsive img-lot2" src="{{ \Tools::url_img("lote_small",$data['subasta_info']->lote_siguiente->num_hces1, $data['subasta_info']->lote_siguiente->lin_hces1)  }}" >
        </div>

        <div class="desc-search">
            <span class="desc">

				@if( !empty($data['subasta_info']->lote_siguiente->titulo_hces1))
				<h4 class="desc-title">{{ $data['subasta_info']->lote_siguiente->titulo_hces1 }} </h4>
				@else
					<span id="desc_web"> {{ $data['subasta_info']->lote_siguiente->descweb_hces1 }}</span>
				@endif

            </span>
            <br>
        </div>

        <div class="price-search">
            {{ trans(\Config::get('app.theme').'-app.sheet_tr.start_price') }}: <span class="precio">{{ \Tools::moneyFormat($data['subasta_info']->lote_siguiente->impsalhces_asigl0) }}</span>
            <span>{{ $data['js_item']['subasta']['currency']->symbol }}</span>
        </div>

        <div class="checkbox checkbox-favorite">
            <label>
                @if(Session::has('user'))
                <input type="checkbox" class="" data-from="buscador"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.add_to_fav') }}
                @else
                <input type="checkbox" class="" data-from="buscador" onclick="initSesion();"> {{ trans(\Config::get('app.theme').'-app.sheet_tr.add_to_fav') }}
                @endif
            </label>
        </div>

        <div class="button-search">

            <span class="lot-msg_adjudicado @if ($data['subasta_info']->lote_siguiente->cerrado_asigl0 != 'S' || ( $data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S' && $data['subasta_info']->lote_siguiente->max_puja == 0 ) ) hidden @endif">
                <b><i class="fa fa-exclamation" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.awarded') }}:</b> <span class="imp_adj"></span>
            </span>

            <span class="lot-msg_ensubasta @if ($data['subasta_info']->lote_siguiente->ref_asigl0 != $data['subasta_info']->lote_actual->ref_asigl0) hidden @endif">
                <b><i class="fa fa-exclamation" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.sheet_tr.in_auction') }}</b>
            </span>

			@if ( $data['subasta_info']->lote_actual->subabierta_sub != 'P')
            	@if(Session::has('user'))
				<button data-from="buscador" class="lot-action_comprar btn btn-primary @if ($data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'J' || $data['subasta_info']->lote_siguiente->cerrado_asigl0 != 'S' || ( $data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S' && $data['subasta_info']->lote_siguiente->max_puja != 0 ) ) hidden @endif" type="button" ref="{{ $data['subasta_info']->lote_siguiente->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_siguiente->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.sheet_tr.buy') }}</button>
				<button data-from="buscador" class="lot-order_importe btn btn-primary @if ($data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S' || $data['subasta_info']->lote_siguiente->ref_asigl0 == $data['subasta_info']->lote_actual->ref_asigl0) hidden @endif" type="button">{{ trans(\Config::get('app.theme').'-app.sheet_tr.import_order') }}</button>
            	@else
            	<button data-from="buscador" class="btn btn-primary @if ($data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'J' || $data['subasta_info']->lote_siguiente->cerrado_asigl0 != 'S' || ( $data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S' && $data['subasta_info']->lote_siguiente->max_puja != 0 ) ) hidden @endif" type="button" ref="{{ $data['subasta_info']->lote_siguiente->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_siguiente->cod_sub }}" onclick="initSesion();">{{ trans(\Config::get('app.theme').'-app.sheet_tr.buy') }}</button>
            	<button data-from="buscador" class="btn btn-primary @if ($data['subasta_info']->lote_siguiente->cerrado_asigl0 == 'S' || $data['subasta_info']->lote_siguiente->ref_asigl0 == $data['subasta_info']->lote_actual->ref_asigl0) hidden @endif" type="button" onclick="initSesion();">{{ trans(\Config::get('app.theme').'-app.sheet_tr.import_order') }}</button>
				@endif
			@endif
        </div>

    </div>
</div>
