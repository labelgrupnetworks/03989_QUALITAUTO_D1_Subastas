@foreach ($lots as $item)
    <?php
    #transformo el array en variables para conservar los nombres antiguos
    # si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.
    foreach ($item->bladeVars as $key => $value) {
        ${$key} = $value;
    }
    $titulo = $item->descweb_hces1;
    $class_square = '';
    $codeScrollBack = '';
    ?>

    <div class="card carrousel-lot-card mx-3 border-0">
        <img class="card-img-top py-3 border" src="{{ $img }}" alt="{{ $titulo }}" loading="auto">

        <div class="card-body py-2 text-center">
            <h4>{{ trans("$theme-app.lot.lot-name") }} {{ $item->ref_asigl0 }}</h4>

			<h6 class="text-lb-gray m-0">
				{{ $subasta_venta ? trans("$theme-app.subastas.price_sale") : trans("$theme-app.lot.lot-price") }}
			</h6>
			<p class="text-lb-gray">{{$precio_salida}} {{ trans("$theme-app.subastas.euros") }}</p>
        </div>

		<div class="card-footer p-0 bg-transparent">
			<a {!! $url !!} class="btn w-100 btn-outline-lb-primary lot-btn">{{ trans("$theme-app.sheet_tr.view") }}</a>
		</div>
    </div>
@endforeach
