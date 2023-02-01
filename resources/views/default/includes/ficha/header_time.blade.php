<div class="d-flex flex-wrap col-xs-12 no-padding justify-content-space-between">

        <div class="info_single_title info-type-auction-title no-padding d-flex justify-content-space-between">
            <div class="info-type-auction">

                 @if($subasta_online)
                {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
                @elseif($subasta_web)
                {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
                @elseif($subasta_venta)
                {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}
				@elseif($subasta_make_offer ?? false)
                {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_make_offer') }}
				@elseif($subasta_inversa ?? false)
                {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_inversa') }}
				@elseif($lote_actual->tipo_sub === "E")
                {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_especial') }}
                @endif
            </div>


            <div class="no-padding ">
				@if(!($subasta_make_offer ?? false))
                    <div class="col-xs-12 ficha-info-close-lot ">
                        <div class="date_top_side_small">
                            <span id="cierre_lote"></span>

                        </div>
                    </div>
				@endif
            </div>




        </div>


@if($cerrado_N && !empty($timeCountdown) && strtotime($timeCountdown) > getdate()[0])
<div class=" ficha-info-clock">
   <span class="clock">
       <i class="fas fa-clock"></i>
       <span
           data-{{$nameCountdown}}="{{ strtotime($timeCountdown) - getdate()[0] }}"
           data-format="<?= \Tools::down_timer($timeCountdown); ?>"
            class="timer">
       </span>
   </span>
</div>
@elseif($cerrado)
<div class="info-type-auction">{{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}</div>

@endif

</div>
