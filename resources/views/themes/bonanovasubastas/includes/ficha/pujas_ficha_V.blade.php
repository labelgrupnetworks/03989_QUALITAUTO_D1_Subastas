<div class="info_single ficha_V col-xs-12">
    <div class="info_single_title col-xs-12">
        <div class="sub-o">

           <?php // entraran tanto lotes de subastas V como cerrados de otra con posibilidad de compra  ?>

            <p>
                @if ($lote_actual->tipo_sub == 'V')
                    {{ trans($theme.'-app.subastas.lot_subasta_venta') }}
                @elseif($lote_actual->tipo_sub == 'W')
                    {{ trans($theme.'-app.subastas.lot_subasta_presencial') }}
                @elseif($lote_actual->tipo_sub == 'O')
                    {{ trans($theme.'-app.subastas.lot_subasta_online') }}
                @endif
            </p>

        </div>
        <div class="date_top_side_small">

            @if($lote_actual->cerrado_asigl0 == 'N')
                <span class="cierre_lote"></span>
            @endif
          <?php /* no ponemos CET   <span id="cet_o"> {{ trans($theme.'-app.lot.cet') }}</span> */ ?>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6">
        <p class="pre">{{ trans($theme.'-app.subastas.price_sale') }}</p>
        <div class="pre">{{$lote_actual->formatted_actual_bid}} {{ trans($theme.'-app.subastas.euros') }}</div>
        <div class="info_single_content info_single_button">
            @if (strtotime($lote_actual->start_session)< time()  && $lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) && ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A'))
                    <button data-from="modal" class="lot-action_comprar_lot btn btn-lg btn-custom" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {{ trans($theme.'-app.subastas.buy_lot') }}</button>
                @endif
        </div>
    </div>
        <div class="col-xs-12 col-sm-6">

            <p class="cat">{{ trans($theme.'-app.lot.categories') }}</p>
        <?php
           $category = new \App\Models\Category();
           $tipo_sec = $category->getSecciones($data['js_item']['lote_actual']->sec_hces1);
        ?>
        <p>
           @foreach($tipo_sec as $sec)
               {{$sec->des_tsec}}
           @endforeach
        </p>
        <p class="shared">{{ trans($theme.'-app.lot.share_lot') }}</p>
        @include('includes.ficha.share')
		<div class="row mt-2 mb-1">
			<?php
				$lotFotURL = $lote_actual->cod_sub . '-' . $lote_actual->ref_asigl0;
				$urlCompletePackengers = \Config::get('app.packengers').$lotFotURL
			?>
			{{--
			<div class="col-xs-12"><a class="packengers-button-ficha" href="{{$urlCompletePackengers}}" target="_blank"><i class="fa fa-truck" aria-hidden="true"></i> Calcula los gastos de envío</a></div>
				--}}
		</div>
    </div>

</div>

<script>
   $(document).ready(function() {
        //calculamos la fecha de cierre
     //   $("#cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/"))),"");
        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->start_session}}".replace(/-/g, "/")),''));

    });
</script>




