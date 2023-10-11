
<?php

    $inf_subasta = new \App\Models\Subasta();
                if(!empty($data['sub_data'])){
                    $inf_subasta->cod = $data['sub_data']->cod_sub;
                }else{
                    $inf_subasta->cod = $data['cod_sub'];
                }
                $ficha_subasta=$inf_subasta->getInfSubasta();


    $subasta = new App\Models\Subasta();
    $subasta->select_filter = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.orden_ORTSEC0, COUNT(COD_SEC) cuantos";
    $subasta->select_filter .= " ,NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0 ";
    $subasta->select_filter .= " ,NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0 ";

    $subasta->join_filter = "JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1 ) ";
    $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 ) ";
    $subasta->join_filter .= "JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = HCES1.EMP_HCES1  and ORTSEC0.lin_ortsec0 =ORTSEC1.lin_ortsec1 ) ";
    $subasta->join_filter .= "LEFT JOIN FGORTSEC0_LANG ORTSEC0_LANG ON (ORTSEC0_LANG.sub_ORTSEC0_LANG = ORTSEC1.sub_ORTSEC1 AND ORTSEC0_LANG.EMP_ORTSEC0_LANG = HCES1.EMP_HCES1  and ORTSEC0_LANG.LIN_ORTSEC0_LANG =ORTSEC1.LIN_ORTSEC1  AND ORTSEC0_LANG.LANG_ORTSEC0_LANG = '". Config::get('app.language_complete')[Config::get('app.locale')]   . "')" ;
    $subasta->where_filter = " AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = '". Config::get('app.gemp')."' ";
    $subasta->group_by = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.orden_ORTSEC0, NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0),NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0)";
    $subasta->order_by_values = "ORTSEC0.orden_ORTSEC0";
     //para las subastas type category solo cogemos las ortsec de la subasta 0 y las que sean TIPO_SUB = p , para las normales cogemos la subasta que toca con la sesion
    $subasta->where_filter .= " AND ORTSEC1.SUB_ORTSEC1 = '0'  AND SUB.TIPO_SUB in (".\Config::get('app.auction_in_categories').")";

    $s = null;
    if(!empty(app('request')->input('s')) || !empty($data['id_auc_sessions'])){
        $s = !empty(app('request')->input('s'))?app('request')->input('s'):$data['id_auc_sessions'];
        $subasta->where_filter  .= " AND AUC.\"id_auc_sessions\" = ". $s." ";
        $getValue = array ('s' => $s);
        if(empty($data['sub_data']) && !empty($data["dataAuxSubasta"])){
            $subastaObj            = new \App\Models\Subasta();
            $subastaObj->cod       = $data["dataAuxSubasta"]->cod_sub;
            $subastaObj->id_auc_sessions = $s;
            $data['sub_data'] = $subastaObj->getInfSubasta();
        }
    }else{
        $getValue = array();
    }

	//dd($data);

    // Filtros por categoría
    /*
    if (isset($data['category']) && !empty($data['category'])) {
        $sec_obj =  new App\Models\Sec();
        $ortsec = $sec_obj->getOrtsecByKey('0',$data['category']);
        if ($ortsec->lin_ortsec0 != 15) {
            $subasta->where_filter  .= " AND (ORTSEC1.LIN_ORTSEC1 = '$ortsec->lin_ortsec0')";
            $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = HCES1.SEC_HCES1 AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = '0') ";
        }
    }
    */

    // Filtramos resultados por descripcion.
    if (isset($data['filterDescription']) && !empty($data['filterDescription'])) {
        $valid_words = false;
        $description = \Tools::replaceDangerqueryCharacter($data['filterDescription']);
        if (!empty($description)) {
            \Tools::linguisticSearch();

            $words = explode(" ",$description);
            $search ="(";
            $pipe = "";
            foreach ($words as $word){
                if(!empty($word) && strlen($word) > 1){
                    $valid_words = true;
                    if(\Config::get( 'app.desc_hces1' )){
                        $search .=$pipe." REGEXP_LIKE (NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.titulo_hces1), '$word') OR REGEXP_LIKE (NVL(HCES1_LANG.DESC_HCES1_LANG, HCES1.DESC_HCES1), '$word')";
                    }else{
                        $search .=$pipe." REGEXP_LIKE (NVL(HCES1_LANG.TITULO_HCES1_LANG, HCES1.titulo_hces1), '$word') OR REGEXP_LIKE (NVL(HCES1_LANG.DESCWEB_HCES1_LANG, HCES1.DESCWEB_HCES1), '$word')";
                    }
                    $pipe = ") AND (";
                }
            }
             $search .=") ";


            if($valid_words){
                $description_where_filter  = " AND ( $search) ";
            }
        }
    }




    // FIltramos resultados por los filtros de vendido, no vendido, en curso
    if (isset($data['filterNo_award']) && !empty($data['filterNo_award'])) {
        $no_award_where_filter = " AND CERRADO_ASIGL0 = 'S' AND CSUB.REF_CSUB IS NULL ";
    }
    if (isset($data['filterAward']) && !empty($data['filterAward'])) {
        $award_where_filter = " AND cerrado_asigl0 = 'S' and  CSUB.REF_CSUB IS NOT NULL ";
    }
    if (isset($data['filterOpen']) && !empty($data['filterOpen'])) {
        $open_where_filter = " AND cerrado_asigl0 = 'N' ";
    }

    if (isset($description_where_filter)) {
        $subasta->where_filter .= $description_where_filter;
    }
    if (isset($open_where_filter)) {
        $subasta->where_filter .= $open_where_filter;
    }
    if (isset($award_where_filter)) {
        $subasta->where_filter .= $award_where_filter;
    }
    if (isset($no_award_where_filter)) {
        $subasta->where_filter .= $no_award_where_filter;
    }

    $data['categories'] = $subasta->getLots("small",true);

    /*if (isset($data['categories']) && !empty($data['categories'])) {
        $data['categories'][0]->cuantos = $data['categories'][0]->cuantos/2;
    }*/



    $data['periodos'] = array();
    $data['periodosCount'] = array();

    if (\Config::get('app.filter_period')) {

        //if (!empty($data['categories']) && !empty($data['category']) && head($data['categories'])->lin_ortsec1 != 15) {

            $subasta = new App\Models\Subasta();
            $subasta->select_filter = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.orden_ORTSEC0";
            $subasta->select_filter .= " ,NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0 ";
            $subasta->select_filter .= " ,NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0 ";
            if (\Config::get('app.locale') == "es") {
                $subasta->select_filter .= ' ,"subperiod_1"';
            }
            else {
                $subasta->select_filter .= ' ,"subperiod_1_lang" subperiod_1';
            }

            $subasta->join_filter = "JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1 ) ";
            $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 ) ";
            $subasta->join_filter .= "JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = HCES1.EMP_HCES1  and ORTSEC0.lin_ortsec0 =ORTSEC1.lin_ortsec1 ) ";
            $subasta->join_filter .= "LEFT JOIN FGORTSEC0_LANG ORTSEC0_LANG ON (ORTSEC0_LANG.sub_ORTSEC0_LANG = ORTSEC1.sub_ORTSEC1 AND ORTSEC0_LANG.EMP_ORTSEC0_LANG = HCES1.EMP_HCES1  and ORTSEC0_LANG.LIN_ORTSEC0_LANG =ORTSEC1.LIN_ORTSEC1  AND ORTSEC0_LANG.LANG_ORTSEC0_LANG = '". Config::get('app.language_complete')[Config::get('app.locale')]   . "')" ;
            $subasta->where_filter = " AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = '". Config::get('app.gemp')."' ";
            if (\Config::get('app.locale') == "es") {
                $subasta->join_filter .= ' JOIN "object_types_values" ON "transfer_sheet_line" = HCES1.LIN_HCES1  AND "transfer_sheet_number" = HCES1.NUM_HCES1 AND "object_types_values"."company" = '.\Config::get("app.emp");
                 $subasta->where_filter .= " AND ORTSEC0.KEY_ORTSEC0 = '". $data['category']."' ";
                }
            else {
                $subasta->join_filter .= ' JOIN "object_types_values_lang" ON "transfer_sheet_line_lang" = HCES1.LIN_HCES1  AND "transfer_sheet_number_lang" = HCES1.NUM_HCES1 AND "company_lang" = '.\Config::get("app.emp")." and \"lang_object_types_values_lang\" = '". Config::get('app.language_complete')[Config::get('app.locale')] ."'";
                 $subasta->where_filter .= " AND ORTSEC0_LANG.KEY_ORTSEC0_LANG = '". $data['category']."' ";

            }


            $subasta->where_filter .= " AND ORTSEC1.SUB_ORTSEC1 = '0'  AND SUB.TIPO_SUB in (".\Config::get('app.auction_in_categories').")";



            $s = null;
            if(!empty(app('request')->input('s')) || !empty($data['id_auc_sessions'])){
                $s = !empty(app('request')->input('s'))?app('request')->input('s'):$data['id_auc_sessions'];
                $subasta->where_filter  .= " AND AUC.\"id_auc_sessions\" = ". $s." ";
                $getValue = array ('s' => $s);
            }else{
                $getValue = array();
            }

            if (isset($description_where_filter)) {
                $subasta->where_filter .= $description_where_filter;
            }
            if (isset($open_where_filter)) {
                $subasta->where_filter .= $open_where_filter;
            }
            if (isset($award_where_filter)) {
                $subasta->where_filter .= $award_where_filter;
            }
            if (isset($no_award_where_filter)) {
                $subasta->where_filter .= $no_award_where_filter;
            }

            $periods = $subasta->getLots("small",true);

            $urlPeriodo = 0;

            if (isset($_GET['subperiodo'])) {
                $urlPeriodo = $_GET['subperiodo'];
            }

            foreach($periods as $k => $item) {

                if (empty($item->subperiod_1)) {
                    $item->subperiod_1 = trans(\Config::get('app.theme').'-app.lot_list.sin_periodo');
                }

                $data['periodos'][\Tools::Seo_url($item->subperiod_1)] = $item;

                if (!isset($data['periodosCount'][\Tools::Seo_url($item->subperiod_1)]))
                    $data['periodosCount'][\Tools::Seo_url($item->subperiod_1)] = 0;
                $data['periodosCount'][\Tools::Seo_url($item->subperiod_1)] += 1;

                if (!empty($urlPeriodo) && $item->subperiod_1 == $urlPeriodo) {
                    $data['periodoSeleccionado'] = $item;
                }

            }


       // }
    }

?>

<form id="form_lotlist" method="get" action="{{ $data['url'] }}">
	<input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
    <section class="principal-bar">
        <div class="container">
            <div class="row">

                <div class="col-xs-12">
                    <div class="principal-bar-wrapper flex valign">
                        <div class="principal-bar-title">
                            @if (!empty($data['sub_data']))
                                <h3>{{ $data['sub_data']->des_sub }}</h3>
                            @elseif(isset($data['dataAuxSubasta']->des_sub))
                                <h3>{{ $data['dataAuxSubasta']->des_sub }}</h3>
                            @endif
                        </div>
                        <div class="bar-filters flex">
                        <div class="input-order-reference">
                            <label class="hidden-xs">{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}</label>
                            <?php
                            $s = null;
                            if(!empty(app('request')->input('s'))){
                                $s = !empty(app('request')->input('s'))?app('request')->input('s'):$data['id_auc_sessions'];
                            }
                            ?>
                            @if(!empty($s))
                            <input type="hidden" name="s" value="{{$s}}">
                            @endif
                            <select id="order_selected" name="order" class="form-control submit_on_change">
                                <!-- Eloy: Desactivado por pettición del cliente 16/09/2019
                                <option value="name" @if (app('request')->input('order') == 'name') selected @endif >
                                    {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   {{ trans(\Config::get('app.theme').'-app.lot_list.name') }}
                               </option>
                                -->
                               <option value="price_asc" @if (app('request')->input('order') == 'price_asc') selected @endif >
                                    {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}
                               </option>
                               <option value="price_desc" @if (app('request')->input('order') == 'price_desc') selected @endif >
                                   {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:      {{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}
                               </option>
                               <option value="ref" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'ref') selected @endif >
                                    {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}
                               </option>
                              <?php /*
                                <option value="ffin" @if (app('request')->input('order') == 'ffin') selected @endif >
                                        {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }} </b>
                                </option>
                                */ ?>
                                <option value="mbids" @if (app('request')->input('order') == 'mbids') selected @endif >
                                        {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }} </b>
                                </option>

                                <option value="hbids" @if (app('request')->input('order') == 'hbids') selected @endif >
                                        {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }} </b>
                                </option>

<?php /*
                                <option value="fecalta" @if (app('request')->input('order') == 'fecalta') selected @endif >
                                        {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.more_recent') }}
                                </option>
 */ ?>
                                <option value="lastbids" @if (app('request')->input('order') == 'lastbids') selected @endif >
                                        {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.last_bids') }}
                                </option>
                                <option value="360" @if (app('request')->input('order') == '360') selected @endif >
                                        {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.lots_360') }}
                                </option>

                               <?php //si son subastas presenciales y ya ha empezado que permita filtrar por lotes?>
                               @if(!empty($data['sub_data']) && $data['sub_data']->tipo_sub == 'W' && ($data['sub_data']->subc_sub == 'S' ||   $data['sub_data']->subc_sub == 'A') && strtotime($data['sub_data']->start) < strtotime("now") )
                                   <option value="fbuy" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'fbuy') selected @endif >
                                           {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.filter_by_buy') }} </b>
                                   </option>
                               @endif
                            </select>
                        </div>
                        <div class="input-order-quantity hidden-xs">
                            <label>{{ trans(\Config::get('app.theme').'-app.lot_list.to_show') }}</label>
                            <select id="total_selected" class="form-control submit_on_change">
                                @foreach (\Config::get('app.filter_total_shown_options') as $option)
                                    <option value="{{ $option }}" @if (app('request')->input('total') == $option) selected @endif >
                                            {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-lot">
                            <label>{{ trans(\Config::get('app.theme').'-app.lot.go_to_lot') }}</label>
                            <input id="input_reference" placeholder="" name="reference" type="text" class="form-control input-sm" value="{{ app('request')->input('reference') }}">
                            <button class="btn btn-color btn-bar-filters">{{ trans(\Config::get('app.theme').'-app.lot.ver') }}</button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="body-auctions">
    <section class="order">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4 col-lg-3 col-md-offset-5 col-lg-offset-6">
                    <?php
                            $subasta_finalizada = false;
                            if(!empty($data['sub_data'])){
                                //ver si la subasta está cerrada
                                $SubastaTR      = new \App\Models\SubastaTiempoReal();
                                $SubastaTR->cod = $data['sub_data']->auction;
                                $SubastaTR->session_reference =  $data['sub_data']->reference; //$subasta->get_reference_auc_session($subasta->id_auc_sessions);
                                $status  = $SubastaTR->getStatus();

                                if(!empty($status) && $status[0]->estado == "ended" ){
                                    $subasta_finalizada = true;

                                }
                            }
                        ?>
                        @if(!empty($data['sub_data']) && $data['sub_data']->tipo_sub =='W' && strtotime($data['sub_data']->end) > time() && strtotime($data['sub_data']->start) < time() && $subasta_finalizada == false)
                        <?php
                            //en caso de que este el tiempo real pujando en ese momento, activamos un texto que le avisa al cliente y lo dirige a pujar en vivo.
                            $url_tiempo_real=\Routing::translateSeo('api/subasta').$data['sub_data']->cod_sub."-".str_slug($data['sub_data']->name)."-".$data['sub_data']->id_auc_sessions;
                        ?>
                            <a href="{{ $url_tiempo_real }}" target="_blank" class="puja-online texto-puja-online">{{ trans(\Config::get('app.theme').'-app.subastas.bid_online_now') }}</a>

                        @elseif(!empty($data['cod_sub_aux']) && $data['cod_sub_aux']->tipo_sub =='W' && strtotime($data['cod_sub_aux']->end) > time() && strtotime($data['cod_sub_aux']->start) < time() && $subasta_finalizada == false)
                        <?php
                            //en caso de que este el tiempo real pujando en ese momento, activamos un texto que le avisa al cliente y lo dirige a pujar en vivo.
                            $url_tiempo_real=\Routing::translateSeo('api/subasta').$data['cod_sub_aux']->cod_sub."-".str_slug($data['cod_sub_aux']->name)."-".$data['cod_sub_aux']->id_auc_sessions;
                        ?>
                            <a href="{{ $url_tiempo_real }}" target="_blank" class="puja-online texto-puja-online">{{ trans(\Config::get('app.theme').'-app.subastas.bid_online_now') }}</a>

                        @endif
                </div>

            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 title-head-grid">
                <div class="col-xs-9 col-sm-4 col-md-3 col-lg-3 refresh text-right">
                     @if(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub && !empty($data['subastas'][0])) && $data['sub_data']->opcioncar_sub == 'S' && strtotime($data['subastas'][0]->start_session) > time())
                        @if(Session::has('user'))
                           <i class="fa fa-gavel  fa-1x"></i> <a href="{{ \Routing::slug('user/panel/modification-orders') }}?sub={{$data['sub_data']->cod_sub}}" ><?= trans(\Config::get('app.theme').'-app.lot_list.ver_ofertas') ?></a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 filter-panel">
                @if( !empty( $data['subastas']) && (!empty(app('request')->input('s')) || !empty($data['id_auc_sessions'])) && $data['subastas'][0]->tipo_sub == 'W'  && ($data['subastas'][0]->subc_sub == 'A' || $data['subastas'][0]->subc_sub == 'S' )  && strtotime($data['subastas'][0]->start_session) > time())
                    <div class="lot-count">
                        <div  class="text-center timeLeftOnLeft online-time">
                            <span data-countdown="{{ strtotime($data['subastas'][0]->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($data['subastas'][0]->start_session); ?>" data-closed="{{ $data['subastas'][0]->cerrado_asigl0 }}" class="timer"></span>
                            <span class="clock"></span>
                        </div>
                    </div>
                @endif
                @include('includes.subasta_filters')
            </div>

            @if( !empty($data['seo']))
                <div class="col-xs-12 col-sm-8 col-md-9 banner_mobile" style="padding: 0px;">
                    {!! \BannerLib::bannersPorKey('GRID_LOTES', 'banner_grid') !!}
                    <!--
                    @if(empty(app('request')->input('s')) )
                        <h1 class="titulo_cat_sub" style="margin-top: 0; font-weight: bold; color: #283747">
                            <?= empty($data['seo']->meta_title)? $data['name'] : $data['seo']->meta_title; ?>
                        </h1>
                    @else
                        <h1 class="titulo_cat_sub" style="margin-top: 0; font-weight: bold; color: #283747">
                            <?= empty($data['seo']->meta_title)? '' : $data['seo']->meta_title.", " ?> {{$data['name']}}
                        </h1>

                    @endif
                -->
                </div>
            @endif


            <div class="col-xs-12 col-sm-8 col-md-9">

                <div class="col-xs-12 col-md-11" style="text-align: right;">



                    @if(\Config::get('app.filter_period') && isset($data['periodos']) && !empty( $data['periodos']) && head($data['periodos'])->lin_ortsec1 != 15)

                        <span class="badge">
                            <a href="{!! \Routing::translateSeo('subastas') !!}{{ trans(\Config::get('app.theme').'-app.links.todas-categorias') }}{{\Tools::generateUrlGet($getValue)}}">
                                <i class="fa fa-times"></i>
                                &nbsp;&nbsp;
                                {{head($data['periodos'])->des_ortsec0}}
                            </a>
                        </span>

                    @endif

                    @if (isset($data['periodoSeleccionado']) && !empty($data['periodoSeleccionado']))

                        <span class="badge">
                            <a href="{!! \Routing::translateSeo('subastas') !!}{{$data['category']}}{{\Tools::generateUrlGet($getValue)}}">
                                <i class="fa fa-times"></i>
                                &nbsp;&nbsp;
                                {{$data['periodoSeleccionado']->subperiod_1}}

                            </a>
                        </span>

                    @endif

                    @if (isset($data['filterDescription']) && !empty($data['filterDescription']))

                        <span class="badge">
                            <a href="javascript:$('#filterDescription').val('');$('#filterDescriptionButton').trigger('click');">
                                <i class="fa fa-times"></i>
                                &nbsp;&nbsp;
                                {{ucfirst($data['filterDescription'])}}

                            </a>
                        </span>

                    @endif

                    @if (isset($data['filterOpen']) && !empty($data['filterOpen']))

                        <span class="badge">
                            <a href="javascript:$('#open').trigger('click');">
                                <i class="fa fa-times"></i>
                                &nbsp;&nbsp;
                                {{ trans(\Config::get('app.theme').'-app.lot_list.progress_lots') }}
                            </a>
                        </span>

                    @endif

                    @if (isset($data['filterAward']) && !empty($data['filterAward']))

                        <span class="badge">
                            <a href="javascript:$('#award').trigger('click');">
                                <i class="fa fa-times"></i>
                                &nbsp;&nbsp;
                                {{ trans(\Config::get('app.theme').'-app.lot_list.sold_lots') }}
                            </a>
                        </span>

                    @endif

                    @if (isset($data['filterNo_award']) && !empty($data['filterNo_award']))

                        <span class="badge">
                            <a href="javascript:$('#no_award').trigger('click');">
                                <i class="fa fa-times"></i>
                                &nbsp;&nbsp;
                                {{ trans(\Config::get('app.theme').'-app.lot_list.dont_sold_lots') }}
                            </a>
                        </span>

					@endif



					<div class="hidden-lg hidden-md hidden-sm" style="clear: both;"></div>

						<div class="switch-filt hidden-lg hidden-md hidden-sm">
                    	    <label class="switcher" for="onlyHistoric"><small></small></label>
							<small class="title-filt">{{ trans(\Config::get('app.theme').'-app.lot_list.show_filters') }}</small>
							<small class="title-filt" style="display: none;">{{ trans(\Config::get('app.theme').'-app.lot_list.hide_filters') }}</small>
                    	    <input type="checkbox" name="" class="js-switch" style="display:none"/>
                    	</div>

						<?php
							$paginator = $data['subastas.paginator'];
							//$paginator->setMaxPagesToShow(4);
							echo $paginator;
						?>

                	</div>

                <div class="order-icon col-xs-12 col-md-1">
                    <ul class="flex">
                        <li>
                            <a id="square" href="javascript:;"><i class="fa fa-2x fa-th fa-lot-list"></i></a>
                        </li>
                        <li>
                            <a id="large_square" href="javascript:;"><i class="fa fa-2x fa-lot-list-large fa-bars"></i></a>
                        </li>
					</ul>
                </div>


                <div class="clearfix"></div>

                <div class="list_lot">
                    @if (count($data['subastas']) == 0)
                        <h2>{{ trans(\Config::get('app.theme').'-app.msg_error.noHayLotes') }}</h2>
                    @endif
                    @foreach ($data['subastas'] as $key => $item)
                        <?php

							$url = "";
							$url_friendly = "";
                            //Si no esta retirado tendrá enlaces
                            if($item->retirado_asigl0 =='N'){
                                $webfriend = !empty($item->webfriend_hces1)? $item->webfriend_hces1 :  str_slug($item->titulo_hces1);
                                if($data['type'] == "theme"){
                                    $url_vars = "?theme=".$data['theme'];
                                }else{
                                    $url_vars ="";
                                }
                                $url_friendly = \Routing::translateSeo('lote').$item->cod_sub."-".str_slug($item->name).'-'.$item->id_auc_sessions."/".$item->ref_asigl0.'-'.$item->num_hces1.'-'.$webfriend.$url_vars;
                                $url = "href='$url_friendly'";
                            }
                            $titulo ="";
                            if(\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
                                $titulo ="$item->ref_asigl0  -  $item->titulo_hces1";
                            }elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
                                $titulo = $item->titulo_hces1;
                            }elseif(\Config::get('app.ref_asigl0')){
                                $titulo = trans(\Config::get('app.theme').'-app.lot.lot-name') ." ".$item->ref_asigl0 ;
                            }
                            $precio_venta=NULL;
                            if (!empty($item->himp_csub)){
                                    $precio_venta=$item->himp_csub;
                            }
                            //si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
                            elseif($item->subc_sub == 'H' && $item->cod_sub == $item->sub_hces1 && $item->lic_hces1 == 'S' and $item->implic_hces1 >0){
                                    $precio_venta = $item->implic_hces1;
                            }
                            $you_bid = false;

                            foreach($item->pujas as $bid){
                                if(!empty($data['js_item']['user']) && $bid->cod_licit == $data['js_item']['user']['cod_licit']){
                                    $you_bid = true;
                                }
                            }
                            $winner = "gold";
                            //si el usuario actual es el ganador

                            if(!empty($you_bid) && isset($data['js_item']['user']) && Session::has('user') && isset($item->max_puja) && ($item->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])){
                                $winner = "mine";
                            }
                            //si hay usuario conectado pero no es el ganador.
                            elseif(!empty($you_bid) && isset($data['js_item']['user']) && Session::has('user')){
                                $winner = "other";
                            }
                            $class_square = 'col-xs-12 col-sm-6 col-md-4 col-lg-4';
                        ?>
                        @include('includes.lotlist')
                        <?php
                            $class_square = 'col-xs-12';
                        ?>
                        @include('includes.lotlist_large')
                    @endforeach
                </div>
            </div>
             <div class="col-xs-12 col-sm-8 col-sm-offset-5 col-md-8 col-md-offset-3 col-xs-offset-0">
                <?php echo $data['subastas.paginator']; ?>
            </div>
        </div>
    </div>
    @if(!empty($data['seo']->meta_content) && $data['subastas.paginator']->currentPage() == 1 )
        <div class="container category">
            <div class="row">
                <div class="col-xs-12" style="margin-bottom: 40px;margin-left:10px">

                    <p><?= $data['seo']->meta_content?></p>
                </div>
            </div>
        </div>
    @endif
</section>



@if (isset($ficha_subasta))

<div id="modal-current-auction_{{$ficha_subasta->cod_sub }}" class="modal fade modal-current-auctions" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-current-auction d-flex align-items-center flex-wrap">
        <div class="img-modal-current col-md-4 col-xs-12">
            <div class="alert-ball"></div>
            <img src="/img/load/subasta_medium/AUCTION_{{ $ficha_subasta->emp_sub }}_{{$ficha_subasta->cod_sub }}.jpg"  class="img-responsive img-auction-new"  />
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="modal-name-aution mb-2 title-modal-current-auction">
                {{ trans(\Config::get('app.theme').'-app.lot_list.la') }} {{ $ficha_subasta->name }} {{ trans(\Config::get('app.theme').'-app.lot_list.isBegin') }}
            </div>
            <div class="modal-desc-auction mb-2 title-modal-current-auction text-underline">
                    {{ $ficha_subasta->description }}

            </div>
            <div class="modal-desc-auction mb-2 title-modal-current-auction">
                    {{ trans(\Config::get('app.theme').'-app.lot_list.begin_auction') }}
            </div>
            <div class="modal-button-auction mb-3 d-flex align-items-center justify-content-space-bettween flex-wrap">
                <div class="btn-current-action gotoauction col-md-6 col-xs-12 ">
                    <?php
                        if($data['type'] !== 'category'){
                            $url_tiempo_real=\Routing::translateSeo('api/subasta').$data['sub_data']->cod_sub."-".str_slug($data['sub_data']->name)."-".$data['sub_data']->id_auc_sessions;
                        ?>
                        <a href="{{ $url_tiempo_real }}" target="_blank" class="puja-online texto-puja-online">{{ trans(\Config::get('app.theme').'-app.subastas.bid_online_now') }}</a>
                    <?php } ?>

                    <?php
                        if(isset($_GET['s'])){
                            $url_tiempo_real=\Routing::translateSeo('api/subasta').$data['cod_sub_aux']->cod_sub."-".str_slug($data['cod_sub_aux']->name)."-".$data['cod_sub_aux']->id_auc_sessions;
                        ?>
                        <a href="{{ $url_tiempo_real }}" target="_blank" class="puja-online texto-puja-online">{{ trans(\Config::get('app.theme').'-app.subastas.bid_online_now') }}</a>
                    <?php } ?>

                </div>
                <div class="btn-current-action continue-here col-md-6 col-xs-12">
                    <a href="" class="bid-large-button-view view">{{ trans(\Config::get('app.theme').'-app.lot_list.continue_here') }}</a>
                </div>
            </div>


        </div>
    </div>
  </div>
</div>

@endif;
<script>
   $(document).ready(function(){
        $("ul.slick-dots").hide();

        $('.banner_grid').css('margin-bottom', '0px');
   });
</script>
