<div class="filters-auction-content">

	<div class="form-group">
		<div class="filters-auction-texts text-center">
			<label class="filters-auction-label"
				for="input_description"><span>{{ trans(\Config::get('app.theme').'-app.lot_list.search') }}</span></label>
			<input id="input_description"
				placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.search_placeholder') }}"
				name="description" type="text" class="form-control input-sm filter-auction-input"
				value="{{ app('request')->input('description') }}">
			<div class="filters-auction-divider-small"></div>
			<label class="filters-auction-label"
				for="input_reference">{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}</label>
			<input id="input_reference" placeholder="{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}"
				name="reference" type="text" class="form-control input-sm filter-auction-input"
				value="{{ app('request')->input('reference') }}">

			<div class="filters-auction-divider-small"></div>
			<label class="filters-auction-label"
				for="input_reference">Orden</label>
			<select id="order_selected" name="order" class="form-control input-sm submit_on_change">
				<option value="name" @if (app('request')->input('order') == 'name') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
					{{ trans(\Config::get('app.theme').'-app.lot_list.name') }}
				</option>
				<option value="price_asc" @if (app('request')->input('order') == 'price_asc') selected
					@endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
					{{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}
				</option>
				<option value="price_desc" @if (app('request')->input('order') == 'price_desc') selected
					@endif
					>
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
					{{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}
				</option>
				<option value="ref" @if (empty(app('request')->input('order')) ||
					app('request')->input('order')
					== 'ref') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
					{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}
				</option>
				@if(!empty( $data['subastas']) && ($data['subastas'][0]->tipo_sub == 'O' ||
				$data['subastas'][0]->tipo_sub == 'P'))
				<option value="ffin" @if (app('request')->input('order') == 'ffin') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}: <b>
						{{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }} </b>
				</option>

				<option value="mbids" @if (app('request')->input('order') == 'mbids') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}: <b>
						{{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }} </b>
				</option>

				<option value="hbids" @if (app('request')->input('order') == 'hbids') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}: <b>
						{{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }} </b>
				</option>


				<option value="fecalta" @if (app('request')->input('order') == 'fecalta') selected @endif >
					{{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:
					{{ trans(\Config::get('app.theme').'-app.lot_list.more_recent') }}
				</option>
				@endif
			</select>
		</div>


		<div class="filters-auction-divider-big"></div>
		<?php
				$lin_ortsec_selected = request('catpers');
                if(!is_numeric($lin_ortsec_selected)){
                    $lin_ortsec_selected = '';
				}
				if(empty($lin_ortsec_selected)){
					$lin_ortsec_selected = '1';
				}
                $subasta = new App\Models\Subasta();
                $subasta->select_filter = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.orden_ORTSEC0, COUNT(COD_SEC) cuantos";
                $subasta->select_filter .= " ,NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0) KEY_ORTSEC0 ";
                $subasta->select_filter .= " ,NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0 ";
                //$subasta->select_filter = "SEC.COD_SEC, COUNT(COD_SEC)";
                $subasta->join_filter = "JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1 ) ";
                $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 AND ORTSEC1.SUB_ORTSEC1 = '0') ";
                $subasta->join_filter .= "JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = HCES1.EMP_HCES1  and ORTSEC0.lin_ortsec0 =ORTSEC1.lin_ortsec1 ) ";
                $subasta->join_filter .= "LEFT JOIN FGORTSEC0_LANG ORTSEC0_LANG ON (ORTSEC0_LANG.sub_ORTSEC0_LANG = ORTSEC1.sub_ORTSEC1 AND ORTSEC0_LANG.EMP_ORTSEC0_LANG = HCES1.EMP_HCES1  and ORTSEC0_LANG.LIN_ORTSEC0_LANG =ORTSEC1.LIN_ORTSEC1  AND ORTSEC0_LANG.LANG_ORTSEC0_LANG = '". \Tools::getLanguageComplete(Config::get('app.locale'))   . "')" ;
                $subasta->where_filter = " AND \"id_auc_sessions\" =  ".  $data['id_auc_sessions']. " AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = ". Config::get('app.gemp');
                $subasta->group_by = "ORTSEC1.LIN_ORTSEC1, ORTSEC0.orden_ORTSEC0, NVL(ORTSEC0_LANG.KEY_ORTSEC0_LANG,  ORTSEC0.KEY_ORTSEC0),NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0)";
                $subasta->order_by_values = "ORTSEC0.orden_ORTSEC0";
				$categories= $subasta->getLots("small",true);

				if ( !empty($lin_ortsec_selected)){

					$subasta = new App\Models\Subasta();
					$subasta->select_filter = "COD_SEC, COUNT(COD_SEC) cuantos";
                	$subasta->select_filter .= " ,NVL(SEC_LANG.DES_SEC_LANG,  SEC.DES_SEC) DES_SEC ";
					$subasta->select_filter .= " ,NVL(SEC_LANG.KEY_SEC_LANG,  SEC.KEY_SEC) KEY_SEC  ";
					$subasta->join_filter = "JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1  ) ";
					$subasta->join_filter .= "LEFT JOIN FXSEC_LANG SEC_LANG ON (SEC_LANG.CODSEC_SEC_LANG = SEC.COD_SEC AND  SEC_LANG.GEMP_SEC_LANG = SEC.GEMP_SEC  AND SEC_LANG.LANG_SEC_LANG = '". \Tools::getLanguageComplete(Config::get('app.locale'))   . "')" ;
					$subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 ) ";
					$subasta->where_filter = " AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = '". Config::get('app.gemp')."' ";
					$subasta->where_filter .=" AND ORTSEC1.lin_ortsec1 = $lin_ortsec_selected  ";
					$subasta->where_filter .= " AND ORTSEC1.SUB_ORTSEC1 = '0' AND \"id_auc_sessions\" =  ".  $data['id_auc_sessions'];
                	$subasta->group_by = "COD_SEC,ORTSEC1.ORDEN_ORTSEC1, NVL(SEC_LANG.DES_SEC_LANG,  SEC.DES_SEC), NVL(SEC_LANG.KEY_SEC_LANG,  SEC.KEY_SEC)";
                	$subasta->order_by_values = "ORTSEC1.ORDEN_ORTSEC1 ASC";

					$subcategories= $subasta->getLots("small",true);
				}

            ?>
		@if( count($categories) > 0)
		<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
			<div class="filters-auction-title d-flex align-items-center justify-content-space-between">
				<span style="flex-grow:2;">{{ trans(\Config::get('app.theme').'-app.lot_list.categories') }}</span><span id="selects"
					data-active='open' class="filters-auction-plus" role="button">+</span>
			</div>

			<div class="filters-auction-selects">
				<ul>
					@foreach ($categories as $category)
				<li>

					@if(app('request')->input('cod_sec') == '')
						<a class="selected" onclick="javascript:addQuery('catpers', '{{ $category->lin_ortsec1 }}')">{{ $category->des_ortsec0 }} ({{$category->cuantos}})
						<input type="hidden" name="catpers" value="{{$category->lin_ortsec1}}">
					@else
						<a onclick="javascript:addQuery('catpers', '{{ $category->lin_ortsec1 }}')">{{ $category->des_ortsec0 }} ({{$category->cuantos}})
					@endif
					</a>
				</li>
					@endforeach

					@if( count($subcategories) > 0)
					@foreach ($subcategories as $subcategory)
						<li>
								@if( app('request')->input('cod_sec') == $subcategory->cod_sec)
									<a class="selected" onclick="javascript:addQuery('cod_sec', '{{ $subcategory->cod_sec }}')">{{ ucfirst(mb_strtolower(trim ($subcategory->des_sec)))}} ({{$subcategory->cuantos}})
									<input type="hidden" name="cod_sec" value="{{$subcategory->cod_sec}}">
								@else
									<a onclick="javascript:addQuery('cod_sec', '{{ $subcategory->cod_sec }}')">{{ ucfirst(mb_strtolower(trim ($subcategory->des_sec)))}} ({{$subcategory->cuantos}})
								@endif
							</a>
						</li>
					@endforeach
					@endif
				</ul>
			</div>

		</div>
		@endif
		<div class="filters-auction-divider-big"></div>
		<div id="select_filters">
			@include('includes.select_filters')
		</div>
		<div class="filters-auction-divider-big"></div>

		<button class="btn btn-filter color-letter"
			type="submit">{{ trans(\Config::get('app.theme').'-app.lot_list.filter') }}</button>

	</div>

</div>


<div class="sidebar_lot">



	<br>


	<?php
                $indices = App\Models\Amedida::indice($data['cod_sub'], $data['id_auc_sessions']);

        ?>
	@if(!isset($in_indice_subasta) || $in_indice_subasta == false)

	<?php
                $indices = App\Models\Amedida::indice($data['cod_sub'], $data['id_auc_sessions']);
            ?>

	@if(!empty($indices))
	<div class="block_filters text">
		<label for="input_description">{{ trans(\Config::get('app.theme').'-app.lot_list.indice_auction') }}</label>
		<div class="tcenter">
			<a title="{{ trans(\Config::get('app.theme').'-app.lot_list.open_indice') }}" href="{{$data['url_indice']}}"
				class="btn btn-filter listaIndice btn-color">{{ trans(\Config::get('app.theme').'-app.lot_list.open_indice') }}</a>
		</div>
	</div>

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
	@endif

</div>



<script>
		$("#category").on('change', function(){
            //borrar los names de los selectores para que no se envien al cambiar de subcategoria
            $("[name$='_select']").attr("name","");
            $("#form_lotlist").submit();
        });

        $("#subcategory").on('change', function(){
            //borrar los names de los selectores para que no se envien al cambiar de subcategoria
            $("[name$='_select']").attr("name","");
            $("#form_lotlist").submit();
        });

        $('.filters-auction-plus').click(function(){
            var id= $(this).attr('id')
            var degrees = 0
            if($(this).attr('data-active') === 'open'){
                $(`.filters-auction-${id}`).css('height', degrees)
                $(this).css({'-webkit-transform' : 'rotate('+ degrees +'deg)',
                    '-moz-transform' : 'rotate('+ degrees +'deg)',
                    '-ms-transform' : 'rotate('+ degrees +'deg)',
                    'transform' : 'rotate('+ degrees +'deg)'});
                    $(this).attr('data-active', 'close')

            }else{
                degrees = 45
                $(`.filters-auction-${id}`).css('height', '116px')
                $(this).css({'-webkit-transform' : 'rotate('+ degrees +'deg)',
                    '-moz-transform' : 'rotate('+ degrees +'deg)',
                    '-ms-transform' : 'rotate('+ degrees +'deg)',
                    'transform' : 'rotate('+ degrees +'deg)'});
                    $(this).attr('data-active', 'open')

            }
		})


		function addQuery(param, value){

			var href = window.location.href;
			var searchParams = new URLSearchParams(href);

			console.log(param);

			if(param == 'catpers'){
				searchParams.delete('cod_sec');
				console.log(decodeURIComponent(searchParams.toString()));
			}

			searchParams.set(param, value);
			var url = decodeURIComponent(searchParams.toString());

			window.location = url;
		}

</script>
