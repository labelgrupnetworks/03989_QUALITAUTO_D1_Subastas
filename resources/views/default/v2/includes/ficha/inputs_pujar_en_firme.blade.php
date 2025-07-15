@php
    $isNecesaryRequestDeposit = Session::has('user') && Config::get('app.withRepresented', false) && !$deposito;
    $codCli = Session::has('user') ? Session::get('user')['cod'] : '';
    $codSub = $lote_actual->cod_sub;
    $ref = $lote_actual->ref_asigl0;
@endphp

{{-- Tambien los usa la subasta inversa --}}
<div class="insert-bid-input">

    <input id="isNecesaryRequestDeposit" type="hidden" value="{{ $isNecesaryRequestDeposit }}">
	<input type="hidden" id="tipo_puja_gestor" value="firme" >

    {{-- Selector de representante --}}
    @if (!empty($representedArray))
        <div class="mb-3">
            <label for="representante">Pujar por:</label>
            <select class="form-select" id="representante">
                <option value="N">
                    {{ $data['usuario']->fisjur_cli === 'F' ? $data['usuario']->nom_cli : $data['usuario']->rsoc_cli }}
                </option>
                @foreach ($representedArray as $id => $representedName)
                    <option value="{{ $id }}">{{ $representedName }}</option>
                @endforeach
            </select>

            <small class="mt-1">
                <a href="{{ route('panel.represented.list', ['lang' => config('app.locale')]) }}">
                    {{ trans('web.lot.add_representative') }}
                    <x-icon.boostrap class="ms-1" icon="plus" />
                </a>
            </small>
        </div>
    @else
        <input id="representante" type="hidden" value="N">
    @endif

    <p class="mt-4">{{ trans('web.lot.insert_max_puja') }}</p>
    <div class="input-group">
        <input class="form-control control-number" id="bid_amount" type="text" value="{{ $data['precio_salida'] }}"
            aria-describedby="button-bid" placeholder="{{ $data['precio_salida'] }}">
        <span class="input-group-text currency-input">{{ trans('web.subastas.euros') }}</span>

        <button id="button-bid"
			data-from="modal"
			data-codcli="{{ $codCli }}"
			data-ref="{{ $ref }}"
            data-codsub="{{ $codSub }}"
			data-lang="{{ config('app.locale') }}"
			data-tipoPuja="firme"
			type="button"
            @class([
                'btn btn-lb-primary',
                'add_favs' => Session::has('user'),
                'lot-action_pujar_on_line' => !$isNecesaryRequestDeposit,
                'lot-action_pujar_no_licit' => $isNecesaryRequestDeposit,
            ]) ref="{{ $ref }}" codsub="{{ $codSub }}">
            {{ trans('web.lot.pujar') }}
        </button>
    </div>

</div>
