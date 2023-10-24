@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<script>
  var url_orden = '<?= $data['node']['ol'] ?>';
</script>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 titlePage">
			<h1 {{-- class="titlePage" --}}><?= trans(\Config::get('app.theme').'-app.lot_list.ver_ofertas') ?></h1>
			<p class="mini-underline"></p>
		</div>
	</div>
</div>
<div id="prova2">
</div>
<div class="container panel">
	<div class="row">
		<div class="col-xs-12 col-sm-12">

		<div class="">
                @if(!empty($data['sub']))
                    <?php
                        $sub = $data['sub'];
                        $total = 0;
                         $url_lotes=\Routing::translateSeo('subasta').$sub[0]->cod_sub."-".str_slug($sub[0]->session_name)."-".$sub[0]->id_auc_session;
                    ?>
                <div class='title_orders'>
                    <p class="text-uppercase text-center sub">{{$sub[0]->des_sub}}</p>
                    <p class='text-right  order'><a href="javascript:history.back(1)">{{ trans(\Config::get('app.theme').'-app.user_panel.return_subastas') }}</a></p>
                </div>
                <div class="table-responsive">
                            <table class="table table-striped table-custom">
                                <thead>
                                    <tr>
                                        <tr>
                                            <th> </th>
                                            <th>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                            <th>{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
                                            <th>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</th>
                                        </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($sub as $inf_lot)
                                <form class="save_orders">
                                        {{ csrf_field() }}
                                           <tr id="{{$inf_lot->cod_sub}}-{{$inf_lot->ref_asigl0}}">
                                               <td scope="row"><img src="/img/load/lote_small/{{Config::get('app.emp').'-'.$inf_lot->numhces_asigl0. '-' .$inf_lot->linhces_asigl0.'.jpg'}}" height="42"></td>


                                               <td >{{$inf_lot->ref_asigl0}}</td>
                                               <td >{{$inf_lot->titulo_hces1}}</td>
                                               <td>
                                                    <input class="form-control hidden" name="cod_sub" value="{{$inf_lot->cod_sub}}"/>
                                                    <input class="form-control hidden" name="ref" value="{{$inf_lot->ref_asigl0}}"/>
                                                    <div class="form-group-custom form-group">
                                                        <input class="form-control " name="order" value="{{$inf_lot->himp_orlic}}"/>
                                                    </div>
                                               </td>
                                               <td><input class="btn btn-success" type="submit" value="{{ trans(\Config::get('app.theme').'-app.user_panel.save') }}"></td>
                                               <td><input class="btn btn-danger delete_order" type="button" ref="{{$inf_lot->ref_asigl0}}" sub="{{$inf_lot->cod_sub}}" value="{{ trans(\Config::get('app.theme').'-app.user_panel.delete') }}"></td>
                                           </tr>
                                        </form>
                                  <?php $total = $total + $inf_lot->himp_orlic?>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                                    <p class="text-uppercase text-center"> {{ trans(\Config::get('app.theme').'-app.user_panel.total_price') }}  <span id="change_price"><?= \Tools::moneyFormat($total,'',true) ?></span> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>

                 @endif
		</div>

		</div>
	</div>
</div>
<div id="modalMensajeDelete" class="container modal-block mfp-hide ">
    <div   data-to="pujarLoteFicha" class="modal-sub-w">
            <section class="panel">
                    <div class="panel-body">
                            <div class="modal-wrapper">
                                    <div class=" text-center single_item_content_">
                                        <span class="class_h1"></span><br/>
                                       <p id="insert_msg_delete"></p><br/>

                                            <button  class=" btn confirm_delete modal-dismiss btn-custom " ref='' sub=''>{{ trans(\Config::get('app.theme').'-app.lot.accept') }}</button>

                                    </div>
                            </div>
                    </div>
            </section>
    </div>
</div>
<div id="changeOrden" class="container modal-block mfp-hide ">
            <div   data-to="pujarLoteFicha" class="modal-sub-w">
                    <section class="panel">
                            <div class="panel-body">
                                    <div class="modal-wrapper">
                                            <div class=" text-center single_item_content_">
                                                <p class="class_h1">{{ trans(\Config::get('app.theme').'-app.lot.confirm_bid') }}</p><br/>
                                                <span for="bid" class='desc_auc'>{{ trans(\Config::get('app.theme').'-app.lot.you_are_bidding') }} </span> <strong><span class="precio_orden"></span> €</strong><br/>
                                                <span class="ref_orden hidden"></span>
                                                </br>
                                                    <button id="save_change_orden" class="btn button_modal_confirm btn-custom">{{ trans(\Config::get('app.theme').'-app.lot.confirm') }}</button>
                                                    <div class='mb-10'></div>
                                                     <div class='mb-10'></div>
                                                    <ul class="items_list">
                                                        <li><?=trans(\Config::get('app.theme').'-app.lot.tax_not_included')?> </li>

                                                    </ul>
                                            </div>
                                    </div>
                            </div>
                    </section>
            </div>
</div>
@stop
