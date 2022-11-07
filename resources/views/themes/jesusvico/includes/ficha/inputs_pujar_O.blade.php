<div class="insert-bid-input mt-auto d-flex flex-column gap-2">

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
        <div class="escalados-container d-flex justify-content-between gap-1 flex-wrap">
            @foreach ($lote_actual->siguientes_escalados as $escalado)
                <button data-from="modal" data-escalado-position="{{ $loop->index }}" type="button"
                    value="{{ $escalado }}" @class([
                        'btn btn-lb-primary lot-action_pujar_on_line js-lot-action_pujar_escalado',
                        'add_favs' => Session::has('user'),
                    ])>

					<span>{{ trans("$theme-app.lot.bid_on") }}</span><br>
                    <b><span id="button-escalado" value="{{ $escalado }}">{{ \Tools::moneyFormat($escalado) }}</span>
                    {{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</b>
                </button>
            @endforeach
        </div>


        <p class="mt-3">{{ trans(\Config::get('app.theme') . '-app.lot.insert_max_puja') }}</p>
        <div class="input-group">
            <input class="form-control control-number" id="bid_amount" type="text"
                value="{{ $data['precio_salida'] }}" aria-describedby="button-bid"
                placeholder="{{ $data['precio_salida'] }}">
            <span
                class="input-group-text currency-input">{{ trans(\Config::get('app.theme') . '-app.subastas.euros') }}</span>
        </div>
        <button id="button-bid" data-from="modal" type="button" @class([
            'lot-action_pujar_on_line btn btn-lb-primary w-100',
            'add_favs' => Session::has('user'),
        ])
            ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">
            {{ trans(\Config::get('app.theme') . '-app.lot.pujar') }}
        </button>
    @endif

</div>
