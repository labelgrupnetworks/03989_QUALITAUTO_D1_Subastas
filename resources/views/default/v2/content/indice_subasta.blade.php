<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 title-head-grid">
            <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3">
                <h3 class="titlePage-custom">{{trans(\Config::get('app.theme').'-app.lot_list.indice_auction')}}</h3>
            </div>
            <div class="col-xs-7 col-sm-6 col-md-4 col-lg-6 lot-count"></div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                <?php $in_indice_subasta = true ?> @include('includes.subasta_filters')
            </div>
            <div class="col-xs-12 col-sm-8 col-md-9">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="first-column-orders btn-color">{{ trans(\Config::get('app.theme').'-app.lot_list.name') }}</th>
                            <th class="second-column-orders btn-color">{{ trans(\Config::get('app.theme').'-app.lot_list.from') }}</th>
                            <th class="third-column-orders btn-color">{{ trans(\Config::get('app.theme').'-app.lot_list.to') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="order-row-tpl" style="display: table-row;">
                            <td class="class-content-orders" onclick="window.location='{{$data['url']}}'">
                                <div class="nt-block-th left">
                                    <span>
                                       {{ trans(\Config::get('app.theme').'-app.subastas.see-all') }}
                                    </span>
                                </div>
                            </td>
                            <td class="class-content-orders">
                                <div class="nt-block-th">
                                    <span>-</span>
                                </div>

                            </td>
                            <td class="class-content-orders class-content-orders-max">
                                <div class="nt-block-th">
                                    <span>-</span>

                                </div>

                            </td>
                        </tr>
                        <?php 
                            $indices = App\Models\Amedida::indice($data['cod_sub'], $data['id_auc_sessions']);
                        ?> @foreach($indices as $indice)
                        <tr id="order-row-tpl" style="display: table-row;">
                            <td class="class-content-orders" onclick="window.location='{{$data['url']}}?first_lot={{$indice->dref_subind}}&last_lot={{$indice->href_subind}}&index_hide=1'">
                                <div class="nt-block-th left">
                                    <span style="<?= $indice->nivel_subind == 2? " padding-left:30px;font-size:0.9em; " : " "    ?> <?= $indice->nivel_subind == 3? "padding-left:60px;font-size:0.9em; " : " "    ?>">
                                        {{$indice->des_subind}}
                                    </span>
                                </div>
                            </td>
                            <td class="class-content-orders">
                                <div class="nt-block-th">
                                    <span>{{$indice->dref_subind}}</span>
                                </div>

                            </td>
                            <td class="class-content-orders class-content-orders-max">
                                <div class="nt-block-th">
                                    <span>{{$indice->href_subind}}</span>

                                </div>

                            </td>
                        </tr>
                        @endforeach
                        <tbody>
                </table>
            </div>

        </div>
    </div>

</div>