<div class="tr_user_product">

    <!-- imagen -->
    <div class="product_img" id="main_lot_box">
        <div class="img" >
            <img width="100%" class="img-lot img-responsive" src="data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }}" style="display: inline">
        </div>
    </div>

    <!-- numero lote -->
    <div class="product_lot">
        <span id="lote_actual_main" class="">{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }}
            <strong><span id="info_lot_actual">
					{{ str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $data['subasta_info']->lote_actual->ref_asigl0)}}
		</span></strong>
        </span>
    </div>

    <!-- titulo y descripcion lote -->
    <div class="product_desc">
        <span id="actual_descweb" class="actual_titulo @if(config('app.tr_hidden_title', false)) hidden @endif">
			<?php echo $data['text_lang'][$data['js_item']['lang_code']]->descweb_hces1 ?>
		</span>
        <div id="actual_descripcion" class="actual_descripcion @if(config('app.tr_hidden_description', false)) hidden @endif">
            <?php echo $data['text_lang'][$data['js_item']['lang_code']]->desc_hces1 ?>
        </div>
        <div id="count_down_msg" class="hidden notranslate count_down_msg">
            <span class="final_auction">{{ trans(\Config::get('app.theme').'-app.sheet_tr.final_auction') }}</span><br>
            <p class="count"></p>
		</div>
		<div id="fairwarning" class="">
            Fair warning
        </div>
    </div>


</div>
