@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<section class="principal-bar no-principal">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="princiapl-bar-wrapper">
                    <div class="principal-bar-title">
                        <h3>{{ trans($theme.'-app.user_panel.mi_cuenta') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="account">
    <div class="container">
        <div class="row">
            <div class="col-xs-2 col-md-3">
                <?php $tab="favorites";?>
                @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-10 col-md-9">
                <div role="tabpanel" class="user-datas-title">
                    <p>{{ trans($theme.'-app.user_panel.favorites') }}</p>
                    <small style="font-weight: 100;color: red;font-size: 12px;line-height: 0;">*{{ trans($theme.'-app.msg_neutral.noRT') }}</small>
                    <div class="col_reg_form"></div>
                </div>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        @foreach($data['favoritos'] as $key_sub => $all_inf)
                            <a data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                      {{$all_inf['inf']->name}}
                                    </h4>
                                    <i class="fas fa-sort-down"></i>
                                </div>
                            </a>
                            <div id="{{$all_inf['inf']->cod_sub}}" class=" panel-collapse collapse <?= count($data['favoritos']) == '1'? 'in':' ';?>">
                                <div class="custom-head-wrapper hidden-xs flex">
                                    <div class="img-data-custom flex "></div>
                                    <div class="lot-data-custon">
                                        <p>{{ trans($theme.'-app.user_panel.lot') }}</p>
                                    </div>
                                    <div class="name-data-custom">
                                        <p>{{ trans($theme.'-app.user_panel.name') }}</p>
                                    </div>

                                    <div class="remat-data-custom">
                                        <p>{{ trans($theme.'-app.lot.lot-price') }}</p>
                                    </div>
                                    <div class="auc-data-custom">
                                        <p>{{ trans($theme.'-app.lot.puja_actual') }}</p>
                                    </div>

                                    <div class="view-data view-fav"></div>
                                </div>
                                <?php $countBid=1; ?>
                                        @foreach($all_inf['lotes'] as $inf_lot)
                                            <?php
                                                $url_friendly = str_slug($inf_lot->titulo_hces1);
                                                $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                            ?>
                                            <div class="{{$inf_lot->ref_asigl0}}-{{$inf_lot->cod_sub}}">
                                                <div class="custom-wrapper-responsive hidden-sm hidden-md hidden-lg">
                                                    <div class="lot-data-custon">
                                                        <p>{{ trans($theme.'-app.user_panel.lot') }} {{$inf_lot->ref_asigl0}} - <span>{{$inf_lot->titulo_hces1}}</span></p>
                                                    </div>
                                                    <div class="flex" style="justify-content: space-between;">
                                                        <div class="auc-data-custom">
                                                            <p>{{ trans($theme.'-app.lot.lot-price') }}</p>
                                                            <p>{{$inf_lot->formatted_impsalhces_asigl0}} {{ trans($theme.'-app.lot.eur') }}</p>
                                                        </div>
                                                        <div class="auc-data-custom">
                                                            <p>{{ trans($theme.'-app.lot.puja_actual') }}</p>
                                                            <p>{{$inf_lot->formatted_impsalhces_asigl0}} {{ trans($theme.'-app.lot.eur') }}</p>
                                                        </div>

                                                        <div class="view-data flex">
                                                            <a  title="{{trans($theme.'-app.lot.del_from_fav')}}" class="delete-fav btn-del" href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')"><i class="fas fa-minus"></i></a>
                                                            <a href="{{$url_friendly}}"><i class="fas fa-eye"></i></a>
                                                        </div>
                                                    </div>
                                                    @if($countBid != count($all_inf['lotes']))
                                                        <div class="divider-prices hidden-sm hidden-md hidden-lg"></div>
                                                        <?php $countBid++; ?>
                                                    @else
                                                        <?php $countBid=1; ?>
                                                    @endif
                                                </div>
                                                <div class="custom-wrapper flex  valign hidden-xs">
                                                    <div class="img-data-custom ">
                                                        <img class="img-responsive" src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}">
                                                    </div>
                                                    <div class="lot-data-custon">
                                                        <p>{{$inf_lot->ref_asigl0}}</p>
                                                    </div>
                                                    <div class="name-data-custom">
                                                        <p>{{$inf_lot->titulo_hces1}} </p>
                                                    </div>
                                                    <div class="auc-data-custom">
                                                        <p>{{$inf_lot->formatted_impsalhces_asigl0}} {{ trans($theme.'-app.lot.eur') }}</p>
                                                    </div>
                                                    <div class="auc-data-custom">
                                                        <p>{{$inf_lot->actual_bid}} {{ trans($theme.'-app.lot.eur') }}</p>
                                                    </div>
                                                    <div class="view-data view-fav flex hidden-xs" >
                                                        <a  title="{{trans($theme.'-app.lot.del_from_fav')}}" class="delete-fav btn-del" href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')"><i class="fas fa-minus"></i></a>
                                                        <a href="{{$url_friendly}}"><i class="fas fa-eye"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@stop













