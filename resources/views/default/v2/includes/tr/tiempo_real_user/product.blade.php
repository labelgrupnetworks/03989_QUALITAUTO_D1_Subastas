  <!-- numero lote -->
  <div class="product_lot">
      <h2 class="" id="lote_actual_main">{{ trans(\Config::get('app.theme') . '-app.sheet_tr.lot') }}
          <span id="info_lot_actual">
              {{ str_replace(['.1', '.2', '.3', '.4', '.5'], ['-A', '-B', '-C', '-D', '-E'], $data['subasta_info']->lote_actual->ref_asigl0) }}
          </span>
      </h2>
  </div>

  {{-- imagen --}}
  {{-- el id main_lot_box y la clase img-responsive se deben mantener poque se utilizán para actualizar la imagén --}}
  <div class="product_img" id="main_lot_box">
      <div class="img h-100 border">
          <img class="img-lot img-contain img-responsive"
              src="data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }}">
      </div>
  </div>

  <!-- titulo y descripcion lote -->
  <div class="product_desc border p-1 position-relative">
      <h4 class="actual_titulo fw-light max-lines @if (config('app.tr_hidden_title', false)) hidden @endif" id="actual_descweb"
          style="--max-lines: 1;">
          <?php echo $data['text_lang'][$data['js_item']['lang_code']]->descweb_hces1; ?>
      </h4>
      <div class="actual_descripcion max-lines with-scroll lb-scroll @if (config('app.tr_hidden_description', false)) hidden @endif"
          id="actual_descripcion" style="--max-lines: 3;">
          <h6 class="text-lb-gray">{!! $data['text_lang'][$data['js_item']['lang_code']]->desc_hces1 !!}</h6>
      </div>
      <div class="hidden notranslate count_down_msg" id="count_down_msg">
          <span class="final_auction">{{ trans(\Config::get('app.theme') . '-app.sheet_tr.final_auction') }}</span><br>
          <p class="count"></p>
      </div>
      <div class="" id="fairwarning">
          Fair warning
      </div>
  </div>
