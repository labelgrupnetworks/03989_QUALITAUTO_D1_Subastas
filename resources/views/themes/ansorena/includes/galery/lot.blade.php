<div class = "galLot">
	<div class="galImgLot">
		{{-- la variable $varUrl contendrá variable or get para la ficha, por ejemplo si venimos de un fondo de galeria de artista --}}
	<a href="{{ \Tools::url_lot($lot->cod_sub,$lot->id_auc_sessions,$lot->name,$lot->ref_asigl0,$lot->num_hces1,$lot->webfriend_hces1,$lot->descweb_hces1)}}{{ $varUrl?? ''}}">
		<img src="{{\Tools::url_img("square_medium", $lot->num_hces1, $lot->lin_hces1, null, true)}}">
	</a>
	</div>
</div>
