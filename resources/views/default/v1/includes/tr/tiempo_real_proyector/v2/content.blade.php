<div id="main_lot_box">
    <div id="main_image_box" class="tr_proyector_content">
		<div id="count_down_msg" class="hidden notranslate count_down_msg">
            <p></p>
        </div>

		@if(Config::get("app.AutorInTR"))
		<p class="autor_tr text-center">
			<span id="autor_JS">{{ $data['subasta_info']->lote_actual->autor }} </span>
		</p>
		@endif

        <div id="img-proyector" class="img" style="background-image: url(data:image/jpg;base64,{{ $data['subasta_info']->lote_actual->imagen }})">
        </div>

    </div>
</div>
