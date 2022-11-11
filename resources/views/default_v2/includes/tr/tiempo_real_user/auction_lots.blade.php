<div class="tr_user_auction_lots">
    <div class="header_aucions_lots ">
        <button class="arrow-carrousel prev-arrow-carrousel btn btn-default">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
		</button>

        <h4 class="m-0">Siguientes lotes</h4>

        <button class="arrow-carrousel next-arrow-carrousel btn btn-default">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
        </button>
    </div>

    <div class="options d-none">
        <div class="carrousel-lots-check d-flex justify-content-end">
            <input class="check-input" id="j-followCarrousel" type="checkbox" value="" checked>
            <label class="check-label" for="j-followCarrousel">
                {{ trans(\Config::get('app.theme') . '-app.sheet_tr.follow') }}
            </label>
        </div>
    </div>

    <div class="lots-carrousel">
        @php
            $carrouselLotes = \App\Models\V5\FgAsigl0::JoinFghces1Asigl0()
                ->JoinSessionAsigl0()
                ->select('num_hces1', 'lin_hces1', 'ref_hces1', 'impsal_hces1', 'webfriend_hces1', 'titulo_hces1', 'ref_asigl0', 'cerrado_asigl0')
                ->where('SUB_ASIGL0', $data['subasta_info']->cod_sub)
                ->where('auc."reference"', $data['subasta_info']->reference)
                ->where('RETIRADO_ASIGL0', 'N')
                ->where('OCULTO_ASIGL0', 'N')
                ->get();
        @endphp

        @foreach ($carrouselLotes as $lote)
            @php
                $loteActual = $data['subasta_info']->lote_actual;
                $idAucSession = $loteActual->id_auc_sessions;
                $img = Tools::url_img('lote_medium', $lote->num_hces1, $lote->lin_hces1);
                $url = Tools::url_lot($data['subasta_info']->cod_sub, $idAucSession, $idAucSession, $lote->ref_asigl0, $lote->num_hces1, $lote->webfriend_hces1, $lote->titulo_hces1);
            @endphp
            <div class="card carrousel-lot-card border-0 j-active-info lots" data-ref_asigl0="{{ $lote->ref_asigl0 }}"
                data-cod_sub="{{ $data['subasta_info']->cod_sub }}" data-order="{{ $loop->index }}" style="order: {{ $loop->index }};">

                <img class="card-img-top py-1 img-contain border" src="{{ $img }}" loading="auto">

                <div class="card-body py-2 text-center">
                    <h4 class="card-lot-title">{{ trans("$theme-app.lot.lot-name") }} {{ $lote->ref_asigl0 }}</h4>

                    <h6 class="text-lb-gray m-0">
                        {{ trans("$theme-app.lot.lot-price") }}
                    </h6>
                    <p class="text-lb-gray">{{ $lote->impsal_hces1 }} {{ trans("$theme-app.subastas.euros") }}</p>
                </div>

                <div class="card-footer p-0 bg-transparent">
                    <a class="btn w-100 btn-outline-lb-primary lot-btn"
                        href="{{ $url }}">{{ trans("$theme-app.sheet_tr.view") }}</a>
                </div>
            </div>
            {{-- condicion para llamada ajax. por ahora se muestra siempre @if ($lote->cerrado_asigl0 != 'N' || !Session::has('user')) j-active-info @endif --}}
            {{-- <div class="lots j-active-info h-100 col-12 col-sm-6 col-md-3"
                        data-ref_asigl0="{{ $lote->ref_asigl0 }}" data-cod_sub="{{ $data['subasta_info']->cod_sub }}"
                        data-background-image="url({{ $img }})" style="order: {{ $loop->index }};">

                        <div class="lots-content">{{ $lote->ref_hces1 }}</div>

                        <div class="j-lots-data justify-content-center align-items-center">
                            <div class="loader" style="display: none"></div>
                            <div
                                class="j-lots-data-load h-100 d-flex flex-column justify-content-end align-items-center">
                                <p class="j-lots-price m-0">
                                    {{ trans(\Config::get('app.theme') . '-app.lot.lot-price') }}:
                                    <span>{{ $lote->impsalini_hces1 ?? 0 }} €</span>
                                </p>
                                <p class="j-lots-state">
                                    {{ trans(Config::get('app.theme') . '-app.sheet_tr.not_awarded') }}</p>
                                @if (!empty($url))
                                    <a class="btn btn-info j-btn-custom-add lots-btn" href="{{ $url }}"
                                        target="_blank">
                                        <span class="j-text-add"
                                            style="display: none">{{ trans(\Config::get('app.theme') . '-app.sheet_tr.buy') }}</span>
                                        <span
                                            class="j-text-view">{{ trans(\Config::get('app.theme') . '-app.lot.ver') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div> --}}
        @endforeach

    </div>

</div>
