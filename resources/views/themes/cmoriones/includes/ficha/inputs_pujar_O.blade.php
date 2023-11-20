@php
    $isNecesaryRequestDeposit = Session::has('user') && !$deposito;
    $siguientesEscalados = $lote_actual->siguientes_escalados;
    $codCli = Session::has('user') ? Session::get('user')['cod'] : '';
    $codSub = $lote_actual->cod_sub;
    $ref = $lote_actual->ref_asigl0;
@endphp
<div class="insert-bid-input mt-3">

    @if (Session::has('user') && Session::get('user.admin'))
        <div class="mb-3">
            <input class="form-control" id="ges_cod_licit" name="ges_cod_licit" type="text" type="text" value=""
                style="border: 1px solid red;" placeholder="Código de licitador">
            @if ($subasta_abierta_P)
                <input id="tipo_puja_gestor" type="hidden" value="abiertaP">
            @endif
        </div>
    @endif

    {{-- Si el lote es NFT y el usuario está logeado pero no tiene wallet --}}
    @if ($lote_actual->es_nft_asigl0 == 'S' && !empty($data['usuario']) && empty($data['usuario']->wallet_cli))
        <div class="require-wallet">{!! trans(\Config::get('app.theme') . '-app.lot.require_wallet') !!}</div>
    @else
        <p>{{ trans("$theme-app.lot.quick_bid") }}</p>
        <div class="escalados-container d-flex justify-content-between gap-1">
            @foreach ($siguientesEscalados as $escalado)
                <button data-from="modal" data-escalado-position="{{ $loop->index }}" data-codcli="{{ $codCli }}"
                    data-ref="{{ $ref }}" data-codsub="{{ $codSub }}" type="button"
                    value="{{ $escalado }}" @class([
                        'btn btn-lb-primary w-100 js-lot-action_pujar_escalado',
                        'add_favs' => Session::has('user'),
                        'lot-action_pujar_on_line' => !$isNecesaryRequestDeposit,
                        'lot-action_pujar_no_licit' => $isNecesaryRequestDeposit,
                    ])>

                    <span id="button-escalado" value="{{ $escalado }}">{{ \Tools::moneyFormat($escalado) }}</span>
                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}
                </button>
            @endforeach
        </div>


        <p class="mt-2">{{ trans(\Config::get('app.theme') . '-app.lot.insert_max_puja') }}</p>
        <div class="input-group">
            <input class="form-control control-number" id="bid_amount" type="text"
                value="{{ $data['precio_salida'] }}" aria-describedby="button-bid"
                placeholder="{{ $data['precio_salida'] }}">
            <span
                class="input-group-text currency-input">{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>

            <button id="button-bid" data-from="modal" data-codcli="{{ $codCli }}" data-ref="{{ $ref }}"
                data-codsub="{{ $codSub }}" data-lang="{{ config('app.locale') }}" type="button"
                @class([
                    'btn btn-lb-primary',
                    'add_favs' => Session::has('user'),
                    'lot-action_pujar_on_line' => !$isNecesaryRequestDeposit,
                    'lot-action_pujar_no_licit' => $isNecesaryRequestDeposit,
                ]) ref="{{ $ref }}" codsub="{{ $codSub }}">
                {{ trans(\Config::get('app.theme') . '-app.lot.pujar') }}
            </button>
        </div>
    @endif

</div>
