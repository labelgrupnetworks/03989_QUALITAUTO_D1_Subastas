{{-- Tambien los usa la subasta inversa --}}
<div class="insert-bid-input">

    @if (Session::has('user') && Session::get('user.admin'))
        <div>
            <input class="form-control" id="ges_cod_licit" name="ges_cod_licit" type="text" type="text" value=""
                style="border: 1px solid red;" placeholder="Código de licitador">

			{{-- @if ($subasta_abierta_P)
                <input id="tipo_puja_gestor" type="hidden" value="abiertaP">
            @endif --}}

        </div>
    @endif
	<input id="tipo_puja_gestor" type="hidden" value="firme">
    <p class="mt-4">{{ trans($theme . '-app.lot.insert_max_puja') }}</p>
    <div class="input-group">
        <input class="form-control" id="bid_amount" type="text" value="{{ $data['precio_salida'] }}"
            aria-describedby="button-bid" placeholder="{{ $data['precio_salida'] }}">
        <span class="input-group-text currency-input">{{ trans($theme . '-app.subastas.euros') }}</span>
        <button id="button-bid" data-from="modal" type="button" @class([
            'lot-action_pujar_on_line btn btn-lb-primary',
            'add_favs' => Session::has('user'),
        ])
            ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">
            {{ trans($theme . '-app.lot.pujar') }}
        </button>
    </div>


</div>
