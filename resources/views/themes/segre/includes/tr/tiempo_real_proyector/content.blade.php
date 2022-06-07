<div id="main_lot_box">
    <div id="main_image_box" class="tr_proyector_content" @if(\Config::get("app.AutorInTR"))style="grid-template-rows: 1fr 10fr;"@endif>
		<div id="count_down_msg" class="hidden notranslate count_down_msg">
            <p></p>
        </div>

		<div class="lot">
			<span id="lote_actual_main" class="">{{ trans(\Config::get('app.theme').'-app.sheet_tr.lot') }} <strong><span id="info_lot_actual">

				@if(\Config::get("app.bis") && \Config::get("app.bis")=="A")
						{{ str_replace(array(".1",".2",".3", ".4", ".5", ".6"), array("-A", "-B", "-C", "-D", "-E"),  $data['subasta_info']->lote_actual->ref_asigl0)}}
					@elseif(\Config::get("app.bis") && \Config::get("app.bis")=="B")
						{{ str_replace(array(".1",".2",".3", ".4", ".5", ".6"), array( "-B", "-C", "-D", "-E", "-F"),  $data['subasta_info']->lote_actual->ref_asigl0)}}
					@else
						{{ $data['subasta_info']->lote_actual->ref_asigl0 }}
					@endif


			</span></strong></span>
		</div>

		@if(\Config::get("app.AutorInTR"))
		<div class="autor_tr text-center" style="margin-top: 10px;"><span id="autor_JS">{{ $data['subasta_info']->lote_actual->autor }} Test de author </span></div>
		@endif

        <div id="img-proyector" class="img" style="background-image: url(data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }})">
            {{-- <img class="img-lot img-fluid img-responsive" src="data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }}"> --}}
        </div>

    </div>
</div>
