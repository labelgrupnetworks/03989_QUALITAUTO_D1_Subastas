<div class="col-xs-12 info_single">
    <div class="info_single_title col-xs-12">

        <div class="sub-o">
                <p class="">{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}</p>
                <div class="date_top_side_small">
                                <span class="cierre_lote"></span>
          <?php /* no ponemos CET   <span id="cet_o"> {{ trans(\Config::get('app.theme').'-app.lot.cet') }}</span> */ ?>
                </div>
            </div>


        <div class="col-xs-12 no-padding col-sm-6 clock-ficha">
            <?php //como no habrá tiempo real la fecha es la de fin de ordenes ?>
                <span class="clock">
                    <i class="fa fa-clock-o"></i>
                    <span data-countdown="{{ strtotime($lote_actual->orders_end) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->orders_end); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
                </span>
            </div>
    </div>


    <div class="col-xs-10 col-sm-12 exit-price">
                        @if(!empty($lote_actual->formatted_imptas_asigl0) && !empty($lote_actual->formatted_imptash_asigl0))
                            <p class="pre text">{{ trans(\Config::get('app.theme').'-app.subastas.estimate') }}</p>
                            <div class="pre">
                                    {{$lote_actual->formatted_imptas_asigl0}} -  {{$lote_actual->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                            </div>
                        @endif
						@if ($lote_actual->ocultarps_asigl0 != 'S')
							<p class="pre text">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
							<div class="pre">
									{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
							</div>
						@endif
    </div>

    <div class="col-xs-12">
        <div class="col-xs-12 col-sm-6 categories no-padding">
            <p class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</p>
                <?php
                    $category = new \App\Models\Category();
                    $tipo_sec = $category->getSecciones($data['js_item']['lote_actual']->sec_hces1);
                ?>
            <p>
                @foreach($tipo_sec as $sec)
                    {{$sec->des_tsec}}
                @endforeach
            </p>
        </div>
        <div class="col-xs-12 col-sm-6 no-padding text-right">
            <p class="shared">{{ trans(\Config::get('app.theme').'-app.lot.share_lot') }}</p>
            @include('includes.ficha.share')
        </div>
    </div>

</div>
@if($lote_actual->fac_hces1!='D')
<div class="info_single col-xs-12 ficha-puja">
    <div class="col-lg-12">
        <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
        {{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
            <strong><span id="tuorden">
            @if ( !empty($data['js_item']['user']['ordenMaxima']))
            {{ $data['js_item']['user']['ordenMaxima']}}
            @endif
            </span>
        {{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>
        </div>
    </div>
    @if ($lote_actual->tipo_sub == 'W' && $lote_actual->subabierta_sub == 'S' && $lote_actual->cerrado_asigl0 == 'N'  )
    <div class="col-lg-12">
        <div class="info_single_title">
            <div id="text_actual_max_bid" class=" <?=  $lote_actual->open_price >0? '':'hidden' ?>">
                {{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }} <strong><span id="actual_max_bid" >{{\Tools::moneyFormat($lote_actual->open_price) }}  </span> {{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>

                @if (isset($data['js_item']['user']))
                    <span class="winner <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? '':'hidden' ?>" >{{ trans(\Config::get('app.theme').'-app.subastas.exceeded') }}</span>
                    <span  class="no_winner <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'hidden':'' ?>">{{ trans(\Config::get('app.theme').'-app.subastas.not_exceeded') }}</span>
                @endif
            </div>
            <div  id="text_actual_no_bid" class=" <?=  $lote_actual->open_price >0? 'hidden':'' ?>"> {{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </div>
        </div>
    </div>
    @endif

        <div class="col-xs-12">
            <div class="info_single_content">

                <?php
                    //no habrá tiempo real
                    /*
                    @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") > strtotime($lote_actual->start_session)  &&  strtotime("now")  < strtotime($lote_actual->end_session) )
                         <a href='{{  Routing::translateSeo('api/subasta').$data['subasta_info']->lote_actual->cod_sub."-".str_slug($data['subasta_info']->lote_actual->name)."-".$data['subasta_info']->lote_actual->id_auc_sessions }}'>
                                <button class="btn btn-lg btn-custom live-btn"><?=trans(\Config::get('app.theme').'-app.lot.bid_live')?></button>
                         </a>
                    @endif
                    */
                ?>
                @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' &&  strtotime("now") > strtotime($lote_actual->orders_start)  &&   strtotime("now") < strtotime($lote_actual->orders_end))
                    <p><strong><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></strong></p>
                    <div class="input-group col-xs-12">
                            <input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" value="{{ $data['precio_salida'] }}" type="text">
                            <button id="pujar_ordenes_w" data-from="modal" type="button" class="btn btn-custom" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</button>
                    </div>
                @endif
                @if(Session::has('user') &&  $lote_actual->retirado_asigl0 =='N')

                                <a  class="btn btn-add-fav <?= $data['subasta_info']->lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
                                    <p>{{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }} </p>
                                </a>
                                <a class="btn btn-del-fav <?= $data['subasta_info']->lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
                                    <p>{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}} </p>
                                </a>
                                @endif

            </div>
        </div>

</div>

@endif





<script>
   $(document).ready(function() {
        //calculamos la fecha de cierre, nos basamso en el cieere de las ordenes, ya que no habrá tiempo real
        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->orders_end}}".replace(/-/g, "/")),'{{ trans(\Config::get('app.theme').'-app.lot.from') }}'));
    });
</script>




