
<div class="{{$class_square}} small_square">
     <div class="item_lot_mini col">
         <div class="content_item_mini">

                    <a title="{{ $titulo }}" <?= $url;?>  >
                        <div class="img_lot">
                            <img class="img-responsive"  src="{{Tools::url_img('lote_small',$item->num_hces1,$item->lin_hces1)}}" xoriginal="{{Tools::url_img('lote_large',$item->num_hces1,$item->lin_hces1)}}" alt="{{ $titulo }}">
                        </div>
                        @if( $item->retirado_asigl0 !='N')
                            <div class="retired ">
                                {{ trans($theme.'-app.lot.retired') }}
                            </div>
                        @elseif(\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta)||$item->desadju_asigl0 =='S' ))
                            <div class="retired" style ="background:#777777;text-transform: lowercase;">
                                {{ trans($theme.'-app.subastas.buy') }}
                            </div>
                        @endif
                    </a>
                    <div class="title_lot text-center">
                    <a title="{{ $titulo }}" <?= $url;?>>
                       {{ $item->ref_asigl0 }}
                    </a>

                </div>
             <div class="text-center">
                @if( $item->retirado_asigl0 =='N')
                    <?php //si no hay ordenes mostramos el precio de salida, ya que solo  ?>
                    @if ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 == 'N' && !empty($item->open_price) && $item->open_price > $item->impsalhces_asigl0 )

                            <span class="{{$winner}}">{{ \Tools::moneyFormat($item->open_price) }} {{ trans($theme.'-app.subastas.euros') }}</span>
                    @elseif($item->tipo_sub == 'V' || \Config::get('app.impsalhces_asigl0'))
                        <span > {{$item->formatted_impsalhces_asigl0}} {{ trans($theme.'-app.subastas.euros') }}</span>
                    @elseif( \Config::get('app.estimacion'))
                            <span > {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans($theme.'-app.subastas.euros') }}</span>
                    @endif
                @endif
             </div>
         </div>

        <div class="capaOculta " style="top: 200px;position: absolute; background: white;   border: 1px solid #ccc;display: none;z-index:999;">
            <img style="max-width:638px;max-height: 400px;" src="{{Tools::url_img('lote_large',$item->num_hces1,$item->lin_hces1)}}">

        </div>
    </div>
</div>

