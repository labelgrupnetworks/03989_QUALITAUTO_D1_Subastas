

                        <div class="filters" style="@if(!empty(request('section')))display:block;@endif">
                            <div class="filters-wrapper">
                                <div class="filter-section">
                                    <div class="filter-section-head">
                                        <h4>{{ trans(\Config::get('app.theme').'-app.lot_list.status_lots') }}</h4>
                                    </div>
                                    <div class="filter-section-checks">
                                        <ul>
                                            <li>
                                                <input type="checkbox" id="en_curso" name="open" class="filled-in submit_on_change">
                                                <label for="en_curso">{{ trans(\Config::get('app.theme').'-app.lot_list.progress_lots') }}</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="no_vendidos" name="no_award" class="filled-in submit_on_change">
                                                <label for="no_vendidos">{{ trans(\Config::get('app.theme').'-app.lot_list.dont_sold_lots') }}</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" id="vendidos" name="award" class="filled-in submit_on_change">
                                                <label for="vendidos">{{ trans(\Config::get('app.theme').'-app.lot_list.sold_lots') }}</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                        <?php

                                            $subasta = new App\Models\Subasta();
                                            $subasta->select_filter = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.DES_ORTSEC0,ORTSEC0.orden_ORTSEC0, COUNT(COD_SEC) cuantos";
                                            //$subasta->select_filter = "SEC.COD_SEC, COUNT(COD_SEC)";
                                            $subasta->join_filter = "JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1 ) ";
                                            $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = ASIGL0.SUB_ASIGL0) ";
                                            $subasta->join_filter .= "JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = HCES1.EMP_HCES1  and ORTSEC0.lin_ortsec0 =ORTSEC1.lin_ortsec1 ) ";
                                            $subasta->where_filter = " AND \"id_auc_sessions\" =  ".  $data['id_auc_sessions']. " AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = ". Config::get('app.emp');
                                            $subasta->group_by = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.DES_ORTSEC0,ORTSEC0.orden_ORTSEC0";
                                            $subasta->order_by_values = "ORTSEC0.orden_ORTSEC0";
                                            $subcategories= $subasta->getLots("small");
                                        ?>
                                <div class="filter-section">
                                    <div class="filter-section-head">
                                        <h4>Periodos</h4>
                                    </div>
                                    <div class="filter-section-list">
                                        <ul>
                                            <li class="active">
                                                <span>Todos</span>
                                            </li>
                                            @foreach($subcategories as $subcategory)
                                                <li class="<?=   app('request')->input('lin_ortsec') == $subcategory->lin_ortsec1? "selected='active'" : ""  ?>" >{{ $subcategory->des_ortsec0 }} ({{$subcategory->cuantos}})</option>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>



 <div class="sidebar_lot hidden">
    <div class="sidebar_lot_title">
           {{ trans(\Config::get('app.theme').'-app.lot_list.filters') }}

    </div>
    <form method="get" action="{{ $data['url'] }}">
        <div class="form-group">
            <label for="input_description">{{ trans(\Config::get('app.theme').'-app.lot_list.search') }}</label>
            <input id="input_description" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}" name="description" type="text" class="form-control input-sm" value="{{ app('request')->input('description') }}">

            <label for="input_description">{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}</label>
            <input id="input_reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}" name="reference" type="text" class="form-control input-sm" value="{{ app('request')->input('reference') }}">
            <button class="btn btn-filter" type="submit">{{ trans(\Config::get('app.theme').'-app.lot_list.filter') }}</button>
            <div class="divider"></div>
            <select id="order_selected" name="order" class="form-control submit_on_change">
                <option value="name" @if (app('request')->input('order') == 'name') selected @endif >
                     {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   {{ trans(\Config::get('app.theme').'-app.lot_list.name') }}
                </option>
                <option value="price_asc" @if (app('request')->input('order') == 'price_asc') selected @endif >
                     {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}
                </option>
                <option value="price_desc" @if (app('request')->input('order') == 'price_desc') selected @endif >
                    {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:      {{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}
                </option>
                <option value="ref" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'ref') selected @endif >
                     {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}
                </option>
                @if(!empty( $data['subastas']) && ($data['subastas'][0]->tipo_sub == 'O' || $data['subastas'][0]->tipo_sub == 'P'))
                    <option value="ffin" @if (app('request')->input('order') == 'ffin') selected @endif >
                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }} </b>
                    </option>

                    <option value="mbids" @if (app('request')->input('order') == 'mbids') selected @endif >
                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }} </b>
                    </option>

                    <option value="hbids" @if (app('request')->input('order') == 'hbids') selected @endif >
                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }} </b>
                    </option>


                    <option value="fecalta" @if (app('request')->input('order') == 'fecalta') selected @endif >
                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.more_recent') }}
                    </option>
                @endif
                <?php //si son subastas presenciales y ya ha empezado que permita filtrar por lotes?>
                @if(!empty($data['sub_data']) && $data['sub_data']->tipo_sub == 'W' && ($data['sub_data']->subc_sub == 'S' ||   $data['sub_data']->subc_sub == 'A') && strtotime($data['sub_data']->start) < strtotime("now") )
                    <option value="fbuy" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'fbuy') selected @endif >
                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.filter_by_buy') }} </b>
                    </option>
                @endif
            </select>
        </div>

        <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
            <label>{{ trans(\Config::get('app.theme').'-app.lot_list.categories') }}  </label>
        <?php /*


        <select class="form-control" id="category" name="category" >

            @foreach($categories as $category)
                 <option value="{{$category->key_name}}" <?=  $data['category'] == $category->key_name? "selected='selected'" : ""  ?>>{{ $category->title }} </option>
            @endforeach
        </select>
        */
        ?>

        <?php

            $subasta = new App\Models\Subasta();
            $subasta->select_filter = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.DES_ORTSEC0,ORTSEC0.orden_ORTSEC0, COUNT(COD_SEC) cuantos";
            //$subasta->select_filter = "SEC.COD_SEC, COUNT(COD_SEC)";
            $subasta->join_filter = "JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1 ) ";
            $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = ASIGL0.SUB_ASIGL0) ";
            $subasta->join_filter .= "JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = HCES1.EMP_HCES1  and ORTSEC0.lin_ortsec0 =ORTSEC1.lin_ortsec1 ) ";
            $subasta->where_filter = " AND \"id_auc_sessions\" =  ".  $data['id_auc_sessions']. " AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = ". Config::get('app.emp');
            $subasta->group_by = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.DES_ORTSEC0,ORTSEC0.orden_ORTSEC0";
            $subasta->order_by_values = "ORTSEC0.orden_ORTSEC0";
            $subcategories= $subasta->getLots("small");
        ?>

        <select class="form-control submit_on_change" id="subcategory" name="lin_ortsec" >

             <option value="" >{{ trans(\Config::get('app.theme').'-app.lot_list.all_subcategory') }}</option>
            @foreach($subcategories as $subcategory)
                 <option value="{{$subcategory->lin_ortsec1}}" <?=   app('request')->input('lin_ortsec') == $subcategory->lin_ortsec1? "selected='selected'" : ""  ?> >{{ $subcategory->des_ortsec0 }} ({{$subcategory->cuantos}})</option>
            @endforeach
        </select>

    </div>

    <br>



    <?php
        $indices = App\Models\Amedida::indice($data['cod_sub'], $data['id_auc_sessions']);

    ?>

    @if(!empty($indices))
        <div class="block_filters text">
            <label for="input_description">{{ trans(\Config::get('app.theme').'-app.lot_list.indice_auction') }}</label>
            <div class="tcenter">
                <a title="{{ trans(\Config::get('app.theme').'-app.lot_list.open_indice') }}" href="javascript:;" class="btn btn-filter listaIndice" data-toggle="modal" data-target="#listaIndice">{{ trans(\Config::get('app.theme').'-app.lot_list.open_indice') }}</a>
            </div>
        </div>
        <input type='hidden' name="index_hide" value="1">
        <?php // hacer que los filtros funcionen con el indice
        /*
        @if (app('request')->input('first_lot'))
            <input type='hidden' name="first_lot" value="{{app('request')->input('first_lot')}}">
        @endif
        @if (app('request')->input('last_lot'))
            <input type='hidden' name="last_lot" value="{{app('request')->input('last_lot')}}">
        @endif
        */
        ?>
        @endif
    </form>
</div>

@if(!empty($indices) && 1!=1)
<!-- Modal -->
<div id="listaIndice" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ trans(\Config::get('app.theme').'-app.lot_list.indice_auction') }}</h4>
      </div>
      <div class="modal-body">

        <div class="table-responsive">
            <table class="table table-striped table-indice">
                <thead>
                        <th style="text-transform: capitalize;">{{ trans(\Config::get('app.theme').'-app.lot_list.name') }}</th>
                        <th style="text-transform: capitalize;">{{ trans(\Config::get('app.theme').'-app.lot_list.from') }}</th>
                        <th style="text-transform: capitalize;">{{ trans(\Config::get('app.theme').'-app.lot_list.to') }}</th>
                </thead>

                @foreach($indices as $indice)
                    <tr>
                        <td style="<?= $indice->nivel_subind == 2? "padding-left:30px;font-size:0.9em;" : ""    ?> <?= $indice->nivel_subind == 3? "padding-left:60px;font-size:0.9em;" : ""    ?>"><strong> <a href="{{$data['url']}}?first_lot={{$indice->dref_subind}}&last_lot={{$indice->href_subind}}&index_hide=1">{{$indice->des_subind}}</a> </strong></td>
                            <td><strong>{{$indice->dref_subind}}</strong></td>
                            <td><strong>{{$indice->href_subind}}</strong></td>
                    </tr>
                @endforeach

            </table>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans(\Config::get('app.theme').'-app.lot_list.close') }}</button>
      </div>
    </div>

</div>
</div>
    @if (!app('request')->input('index_hide') && empty(Route::current()->parameter('page')) )
    <script>
         //si pasan la variable view_login = true se mostrara el login
          $( document ).ready(function() {
             $( ".listaIndice" ).click();
        });

    </script>

    @endif
@endif











