<div id="main_lot_box">
    <div id="main_image_box" class="tr_proyector_content" @if(\Config::get("app.AutorInTR"))style="grid-template-rows: 1fr 10fr;"@endif>
		<div id="count_down_msg" class="hidden notranslate count_down_msg">
            <p></p>
        </div>

		@if(\Config::get("app.AutorInTR"))
		<div class="autor_tr text-center" style="margin-top: 10px;"><span id="autor_JS">{{ $data['subasta_info']->lote_actual->autor }} Test de author </span></div>
		@endif

        <div id="img-proyector" class="img" style="background-image: url(data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }})">
            {{-- <img class="img-lot img-fluid img-responsive" src="data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }}"> --}}
        </div>

    </div>
</div>
