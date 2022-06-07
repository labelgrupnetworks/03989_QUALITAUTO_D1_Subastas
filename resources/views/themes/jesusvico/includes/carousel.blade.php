<?php
# al carrousel no deberían llegar lotes cerrados, devueltos, retirados ni Ocultos

$url = \Tools::url_lot($bann->sub_asigl0, $bann->id_auc_sessions, $bann->name, $bann->ref_asigl0, $bann->num_hces1, $bann->webfriend_hces1, $bann->titulo_hces1);
$url = "href='$url'";

$titulo = "$bann->titulo_hces1";
$hay_pujas = !empty($bann->max_puja) ? true : false;
$subasta_online = ($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O') ? true : false;
$subasta_abierta_P = $bann->subabierta_sub == 'P' ? true : false;

$devuelto = ($bann->fac_hces1 == 'D' || $bann->fac_hces1 == 'R' || $bann->cerrado_asigl0 == 'D') ? true : false;
$retirado = $bann->retirado_asigl0 != 'N' ? true : false;
$cerrado = $bann->cerrado_asigl0 == 'S' ? true : false;
$precio_venta = \Tools::moneyFormat($bann->max_puja);
$compra = $bann->compra_asigl0 == 'S' ? true : false;
$subasta_venta = $bann->tipo_sub == 'V' ? true : false;
$vendido = $hay_pujas && $cerrado;

$precio_salida = (!empty($bann->impsalweb_asigl0) && $bann->impsalweb_asigl0) != 0 ? \Tools::moneyFormat($bann->impsalweb_asigl0) : $bann->impsalhces_asigl0;
?>

<div style="position: relative; padding: 0 10px;">

    <a class="lote-destacado-link secondary-color-text" title="{{ $titulo}}" <?= $url ?>>

        <div class="item_lot">

            <div class="border_item_img">
                <div class="item_img">
                    <div data-loader="loaderDetacados" class='text-input__loading--line'></div>
                    <img class="lazy" data-src="{{Tools::url_img('lote_medium',$bann->num_hces1,$bann->lin_hces1)}}" alt="{{ $titulo}}" />
                </div>
            </div>

            <div class="lot_title">
                Lote {{ $bann->ref_asigl0 }}
            </div>

            <div class="data-container">

                <div class="title_item" style="text-align: justify; height: 75px; overflow: hidden;">
                    <span class="seo_h4">{{ $titulo}}</span>
                </div>


                <div class="data-price text-center">

                    <div class="row">

                        <div class="salida col-xs-12 mb-1">
                            <p class="salida-title">
                                <span style="float: left;">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
                                <span style="float: right">{{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                            </p>
                        </div>

                        <div class="salida col-xs-12">
                            @if ( ($subasta_online || $subasta_abierta_P) && $hay_pujas)

							<p class="salida-title mb-0">
								<span style="float: left;">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
								<span style="float: right">{{\Tools::moneyFormat($bann->max_puja,false,0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
							</p>

							@elseif($vendido)
							<p class="salida-title mb-0">
								<span style="float: left; text-transform: capitalize">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</span>
							</p>
							@else
							<p class="salida-title mb-0" style="min-height: 25.7px"></p>
                            @endif
                        </div>
                    </div>


                    @if($subasta_online)
                    <p class="mt-15 salida-time background-principal text-center  d-flex align-items-center justify-content-center">
                        <i class="fa fa-clock-o"></i>
                        <span data-countdown="{{strtotime($bann->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($bann->close_at); ?>" class="timer">
                        </span>
                    </p>
                    @endif

                </div>

                @if (!$devuelto && !$retirado)
                @if($cerrado && empty($precio_venta) && $compra)
                <p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
                @elseif($subasta_venta && !$cerrado )
                <p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
                @elseif(!$cerrado )
                <p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</p>
                @endif
                @endif

            </div>
        </div>
    </a>
</div>
